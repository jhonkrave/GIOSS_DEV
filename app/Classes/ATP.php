<?php

namespace App\Classes;

use App\Classes\FileValidator;
use App\Traits\ToolsForFilesController;
use App\Models\FileStatus;
use App\Models\Archivo;
use App\Models\UserIp;
use App\Models\Registro;
use App\Models\Eapb;
use App\Models\EntidadesSectorSalud;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\Ambito;
use App\Models\PesoTallaTension;
use App\Models\GiossArchivoAtpCfvl;


class ATP extends FileValidator {



  function __construct($pathfolder, $fileName,$consecutive) {
    $filePath = $pathfolder.$fileName;
    $this->countLine($filePath);
    if(!($this->handle = fopen($filePath, 'r'))) throw new Exception("Error al abrir el archivo ATP");
    
    //dd($fileName);
    $this->folder = $pathfolder;

    $fileNameToken = explode('.',$fileName);
    $this->fileName =  substr($fileNameToken[0],0,58);
    $this->version = substr($fileNameToken[0],58);

    $this->consecutive = $consecutive;
    $this->detail_erros = array(['No. línea archivo original', 'No. linea en archivo de errores','Campo', 'Descripción']);
    $this->wrong_rows =  array();
    $this->success_rows =  array();

  }



  public function manageContent() {

		try {

      // se validad la existencia del archivo
      $isValidFile = true;
      $fileid = 0;

      $exists = Archivo::where('nombre', $this->fileName)
                ->where('version', $this->version)
                ->first(); 

      if($exists){
        $isValidFile = false;
        array_push($this->detail_erros, [0, 0, '', "El archivo ya se fue gestionado. Por favor actualizar la version"]);
        $fileid = $exists->id_archivo_seq;
      }else{
          //se define en primera instancia el objeto archivo
      
          $this->archivo = new Archivo();
          $this->archivo->modulo_informacion = 'SGD';
          $this->archivo->nombre = $this->fileName;
          $this->archivo->version = $this->version;
          $this->archivo->id_tema_informacion = 'ATP';
          $this->archivo->save();

          $fileid = $this->archivo->id_archivo_seq;

      }

      // se inicializa el objeto file_status 
      $this->file_status =  new FileStatus();
      $this->file_status->consecutive = $this->consecutive;
      $this->file_status->archivoid = $fileid;
      $this->file_status->current_status = 'WORKING';

      $this->file_status->save();  


      $isValidFirstRow = true ;
      
      
      
			$firstRow = fgetcsv($this->handle, 0, "|");
      
			$this->validateFirstRow($isValidFirstRow, $this->detail_erros, $firstRow);

      if ($isValidFirstRow && $isValidFile) {

        //se adicionan terminan de definir los prametros el archivo
        $this->archivo->fecha_ini_periodo = strtotime($firstRow[2]);
        $this->archivo->fecha_fin_periodo = strtotime($firstRow[3]);
        $entidad = EntidadesSectorSalud::where('cod_habilitacion', $firstRow[0])->first();
        $this->archivo->id_entidad = $entidad->id_entidad;
        $this->archivo->numero_registros = $firstRow[4];
        $this->archivo->save();

        $this->file_status->total_registers =  $firstRow[4];
        $this->file_status->save();

        $lineCount = 2;
        $lineCountWF = 2;
        //se valida cada línea
        while($data = fgetcsv($this->handle, 10000, "|"))
        {
          $this->dropWhiteSpace($data); // se borran los espcaios en de cada campo
          $isValidRow = true;

          $this->validateEntitySection($isValidRow, $this->detail_erros, $lineCount, $lineCountWF, array_slice($data,0,6));
          $this->validateUserSection($isValidRow, $this->detail_erros, $lineCount, $lineCountWF, array_slice($data,6,9,true));
          $this->validateATP($isValidRow, $this->detail_erros, $lineCount, $lineCountWF, array_slice($data,15,14,true));

          if ($isValidRow) // se validan cohenrencia entre fechas
          { 
            $this->validateDates($isValidRow, $this->detail_erros, $lineCount, $lineCountWF, $firstRow,$data);
          }

          if(!$isValidRow){
            
            array_push($this->wrong_rows, $data);
            $this->updateStatusFile($lineCount); //se acatualiza la lienea ya tratada
            $lineCount++;
            $lineCountWF++;
            continue;
          }else{
              
            //se valida duplicidad en la informacion
            $exists = DB::table('peso_talla_tension')
            ->join('registro', 'peso_talla_tension.id_registro', '=', 'registro.id_registro_seq')
            ->join('archivo', 'registro.id_registro_seq', '=', 'archivo.id_archivo_seq')
            ->join('eapbs', 'registro.id_registro_seq', '=', 'eapbs.id_entidad')
            ->join('user_ips', 'registro.id_user', '=', 'user_ips.id_user')
              ->where('archivo.fecha_ini_periodo', strtotime($firstRow[2]))
              ->where('archivo.fecha_fin_periodo', strtotime($firstRow[3]))
              ->where('eapbs.num_identificacion', ltrim($data[3],'0'))
              ->where('user_ips.num_identificacion', $data[8])
              ->where('peso_talla_tension.ambito', $data[15])
              ->where('peso_talla_tension.fecha_med_peso', strtotime($data[16]))
              ->where('peso_talla_tension.valor_peso', $data[17])
              ->where('peso_talla_tension.fecha_med_talla', strtotime($data[18]))
              ->where('peso_talla_tension.valor_talla', $data[19])
              ->where('peso_talla_tension.fecha_med_tension',strtotime($data[20]))
              ->where('peso_talla_tension.valor_tension_sistolica', $data[21])
              ->where('peso_talla_tension.valor_tension_diastolica', $data[22])
            ->first();

            if($exists){
              
              array_push($this->detail_erros, [$lineCount, $lineCountWF, '', "Registro duplicado"]);
              array_push($this->wrong_rows, $data);
              $this->updateStatusFile($lineCount);
              $lineCountWF++;
              $lineCount++;
              continue;
            }else
            {
              //se guarda todo el registro en en la tabla soporte
                $tabla = new GiossArchivoAtpCfvl();
                $tabla->fecha_periodo_inicio = $this->archivo->fecha_ini_periodo;
                $tabla->fecha_periodo_fin = $this->archivo->fecha_fin_periodo;
                $tabla->nombre_archivo = $this->fileName;;
                $tabla->numero_registro = $lineCount;
                $tabla->contenido_registro_validado = implode('|', $data);
                $tabla->fecha_hora_validacion = time() ;
                $tabla->save();

              //
              // alamacena en la dimension
              $exists = UserIp::where('num_identificacion', $data[8])->orderBy('created_at', 'desc')->first();

              $createNewUserIp = true;
              $useripsid = 0;

              if($exists){
                if($exists->num_historia_clinica ==  $data[6] || $exists->tipo_identificacion ==  $data[7] || $exists->primer_apellido ==  $data[9] || $exists->segundo_apellido ==  $data[10] || $exists->primer_nombre ==  $data[11] || $exists->segundo_nombre ==  $data[12] || $exists->fecha_nacimiento ==  $data[13] || $exists->sexo ==  $data[14])
                {
                  $createNewUserIp = false;
                  $useripsid = $exists->id_user;
                }
              }

              if($createNewUserIp)
              {
                $ipsuser = new UserIp();
                $ipsuser->num_historia_clinica = $data[6];
                $ipsuser->tipo_identificacion = $data[7];
                $ipsuser->num_identificacion = $data[8];
                $ipsuser->primer_apellido = $data[9];
                $ipsuser->segundo_apellido = $data[10];
                $ipsuser->primer_nombre = $data[11];
                $ipsuser->segundo_nombre = $data[12];
                $ipsuser->fecha_nacimiento = $data[13];
                $ipsuser->sexo = $data[14];

                $ipsuser->save();
                $useripsid = $ipsuser->id_user;
              }

              //se alamcena la informacion de la relacion registro
              $register = new Registro();
              $register->id_archivo = $this->archivo->id_archivo_seq;
              $register->id_user = $useripsid;

              $eapb  = Eapb::where('num_identificacion',  ltrim($data[3],'0'))
                              ->where('cod_eapb', $data[4])->first();

              $register->id_eapb = $eapb->id_entidad;
              $register->save();

              //se almacena la información correpondiente a la consulta
              $consult = new PesoTallaTension();
              $consult->id_registro = $register->id_registro_seq;
              $consult->ambito = $data[15];
              $consult->fecha_med_peso = strtotime($data[16]);
              $consult->valor_peso = $data[17];
              $consult->fecha_med_talla = strtotime($data[18]);
              $consult->valor_talla = $data[19];
              $consult->fecha_med_tension = strtotime($data[20]);
              $consult->valor_tension_sistolica = $data[21];
              $consult->valor_tension_diastolica = $data[22];

              $consult->save();

              array_push($this->success_rows, $data);
              $this->updateStatusFile($lineCount);
              $lineCount++;

            }  
          }
        }

        fclose($this->handle);
        $this->generateFiles();

      }else{
        $this->generateFiles();
      }
    
		} catch (\Exception $e) {
		  print_r($e->getMessage());
		}

  }

  private function validateATP(&$isValidRow, &$detail_erros, $lineCount, $lineCountWF,$consultSection) {

    //validacion campo 16
    if(isset($consultSection[15])) {
        if(strlen($consultSection[15]) != 1){
          $isValidRow = false;
          array_push($detail_erros, [$lineCount, $lineCountWF, 16, "El campo de tener una longitud igual a 1"]);
        }else{
          $exists = Ambito::where('cod_ambito',$consultSection[15])->first();
          if(!$exists){
            array_push($detail_erros, [$lineCount, $lineCountWF, 16, "El valor del campo no correponde a un Ambito valido"]);
          }
        }
    }else{
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 16, "El campo no debe ser nulo"]);
    }

    //validacion campo 17
    if(isset($consultSection[16])) {
      if(preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $consultSection[16]))
      {
        $date = explode('-', $consultSection[16]);
        if(!checkdate($date[2], $date[1], $date[0]))
        {
          $isValidRow = false;
          array_push($detail_erros, [$lineCount, $lineCountWF, 17, "El campo debe corresponder a un fecha válida."]);
        }
      }
      else{
        $isValidRow = false;
        array_push($detail_erros, [$lineCount, $lineCountWF, 17, "El campo debe tener el formato AAAA-MM-DD"]);
      }
    }else{
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 17, "El campo no debe ser nulo"]);
    }

    //validacion campo 18
    if(isset($consultSection[17])) {
        if(strlen($consultSection[17]) > 4 || $consultSection[17] == ''){
          $isValidRow = false;
        array_push($detail_erros, [$lineCount, $lineCountWF, 18, "El campo debe no debe ser vacio y debe tener un longitud no mayor a 4 caracteres"]);
        }elseif(!is_numeric($consultSection[17] )){
          array_push($detail_erros, [$lineCount, $lineCountWF, 18, "campo debe ser un valor numerico(las cifras decimales deben estar separadas por el caracter punto '.')"]);
        }
    }else{
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 18, "El campo no debe ser nulo"]);
    }

    //validacion campo 19
    if(isset($consultSection[18])) {
      if(preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $consultSection[18]))
      {
        $date = explode('-', $consultSection[18]);
        if(!checkdate($date[2], $date[1], $date[0]))
        {
          $isValidRow = false;
          array_push($detail_erros, [$lineCount, $lineCountWF, 19, "El campo debe corresponder a un fecha válida."]);
        }
      }
      else{
        $isValidRow = false;
        array_push($detail_erros, [$lineCount, $lineCountWF, 19, "El campo debe tener el formato AAAA-MM-DD"]);
      }
    }else{
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 19, "El campo no debe ser nulo"]);
    }

    //validacion campo 20
    if(isset($consultSection[19])) {
        if(strlen($consultSection[19]) > 3 || $consultSection[19] == ''){
          $isValidRow = false;
        array_push($detail_erros, [$lineCount, $lineCountWF, 20, "El campo debe no debe ser vacio y debe tener un longitud no mayor a 4 caracteres"]);
        }elseif(!(int)$consultSection[19] == $consultSection[19]){
          array_push($detail_erros, [$lineCount, $lineCountWF, 18, "El campo debe ser un valor entero."]);
        }
    }else{
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 20, "El campo no debe ser nulo"]);
    }

    

    //validacion campo 21
    if(isset($consultSection[20])) {
      if(preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $consultSection[20]))
      {
        $date = explode('-', $consultSection[20]);
        if(!checkdate($date[2], $date[1], $date[0]))
        {
          $isValidRow = false;
          array_push($detail_erros, [$lineCount, $lineCountWF, 21, "El campo debe corresponder a un fecha válida."]);
        }
      }
      else{
        $isValidRow = false;
        array_push($detail_erros, [$lineCount, $lineCountWF, 21, "El campo debe tener el formato AAAA-MM-DD"]);
      }
        
    }else{
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 21, "El campo no debe ser nulo"]);
    }

    //validacion campo 22
    if(isset($consultSection[21])) {
        if(strlen($consultSection[21]) > 3 || $consultSection[21] == ''){
          $isValidRow = false;
        array_push($detail_erros, [$lineCount, $lineCountWF, 22, "El campo debe no debe ser vacio y debe tener un longitud no mayor a 4 caracteres"]);
        }elseif(!(int)$consultSection[21] == $consultSection[21]){
          array_push($detail_erros, [$lineCount, $lineCountWF, 22, "El campo debe ser un valor entero."]);
        }
    }else{
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 22, "El campo no debe ser nulo"]);
    }

    //validacion campo 23
    if(isset($consultSection[22])) {
        if(strlen($consultSection[22]) > 3 || $consultSection[22] == ''){
          $isValidRow = false;
        array_push($detail_erros, [$lineCount, $lineCountWF, 23, "El campo debe no debe ser vacio y debe tener un longitud no mayor a 4 caracteres"]);
        }elseif(!(int)$consultSection[22] == $consultSection[22]){
          array_push($detail_erros, [$lineCount, $lineCountWF, 23, "El campo debe ser un valor entero."]);
        }
    }else{
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 23, "El campo no debe ser nulo"]);
    }

    
  }

  protected function validateDates(&$isValidRow, &$detail_erros, $lineCount, $lineCountWF,$firstRow ,$data)
  {

    if (strtotime($firstRow[3]) < strtotime($data[13]) ){
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 14, "La fecha de nacimiento (campo 14) debe ser inferior a la fecha final del periodo reportado  (línea 1, campo 4)"]);
    }

    //se valida que la fecha de nacimiento sa inferior a la fecha de Medición de peso
    if (strtotime($data[16]) < strtotime($data[13]) ){
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 14, "La fecha de nacimiento (campo 14) debe ser inferior a la fecha de Medicion de peso (campo 17)"]);
    }

    //se valida que la fecha de medion de peso esté entre la fecha de los periodos
    if ( (strtotime($firstRow[2]) > strtotime($data[16])) || (strtotime($firstRow[3]) < strtotime($data[16])) ){
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 16, "La fecha de Medición de peso (campo 17) debe estar registrada entre el periodo reportado. fecha incial(línea 1, campo 3) y fecha final (línea 1, campo 4) "]);
    }


    //se valida que la fecha de nacimiento sa inferior a la fecha de Medición de la Talla
    if (strtotime($data[18]) < strtotime($data[13]) ){
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 14, "La fecha de nacimiento (campo 14) debe ser inferior a la fecha de Medición de la Talla (campo 19)"]);
    }

    //se valida que la fecha de Medición de la Talla esté entre la fecha de los periodos
    if ( (strtotime($firstRow[2]) > strtotime($data[18])) || (strtotime($firstRow[3]) < strtotime($data[18])) ){
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 16, "La fecha de Medición de la Talla (campo 19) debe estar registrada entre el periodo reportado. fecha incial(línea 1, campo 3) y fecha final (línea 1, campo 4) "]);
    }


    //se valida que la fecha de nacimiento sa inferior a la fecha de Medición de la Tensión 
    if (strtotime($data[20]) < strtotime($data[13]) ){
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 14, "La fecha de nacimiento (campo 14) debe ser inferior a la fecha de Medición de la Tensión (campo 21)"]);
    }

    //se valida que la fecha de Medición de la Tensión  esté entre la fecha de los periodos
    if ( (strtotime($firstRow[2]) > strtotime($data[20])) || (strtotime($firstRow[3]) < strtotime($data[20])) ){
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 16, "La fecha de Medición de la Tensión  (campo 21) debe estar registrada entre el periodo reportado. fecha incial(línea 1, campo 3) y fecha final (línea 1, campo 4) "]);
    }


  }

}