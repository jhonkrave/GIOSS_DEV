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
            $exists = DB::table('consulta')
            ->join('registro', 'consulta.id_registro', '=', 'registro.id_registro_seq')
            ->join('archivo', 'registro.id_registro_seq', '=', 'archivo.id_archivo_seq')
            ->join('eapbs', 'registro.id_registro_seq', '=', 'eapbs.id_entidad')
            ->join('user_ips', 'registro.id_user', '=', 'user_ips.id_user')
              ->where('archivo.fecha_ini_periodo', strtotime($firstRow[2]))
              ->where('archivo.fecha_fin_periodo', strtotime($firstRow[3]))
              ->where('eapbs.num_identificacion', ltrim($data[3],'0'))
              ->where('user_ips.num_identificacion', $data[8])
              ->where('consulta.fecha_consulta', $data[15])
              ->where('consulta.ambito_consulta', $data[16])
              ->where('consulta.tipo_codificacion', $data[18])
              ->where('consulta.cod_consulta', $data[17])
              ->where('consulta.cod_consulta_esp', $data[19])
              ->where('consulta.cod_diagnostico_principal', $data[21])
              ->where('consulta.cod_diagnostico_rel1', $data[23])
              ->where('consulta.cod_diagnostico_rel2', $data[25])
              ->where('consulta.tipo_diagnostico_principal', $data[27])
              ->where('consulta.finalidad_consulta', $data[28])
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
                $tabla = new GiossArchivoAacCfvl();
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
              $consult = new Consultum();
              $consult->id_registro = $register->id_registro_seq;
              $consult->fecha_consulta = $data[15];
              $consult->ambito_consulta = $data[16];
              $consult->tipo_codificacion = $data[18];
              $consult->cod_consulta = $data[17];
              $consult->cod_consulta_esp = $data[19];
              $consult->cod_diagnostico_principal = $data[21];
              $consult->cod_diagnostico_rel1 = $data[23];
              $consult->cod_diagnostico_rel2 = $data[25];
              $consult->tipo_diagnostico_principal = $data[27];
              $consult->finalidad_consulta = $data[28];

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
        if(strlen($consultSection[16]) != 1){
          $isValidRow = false;
          array_push($detail_erros, [$lineCount, $lineCountWF, 17, "El campo de tener una longitud igual a 1"]);
        }else{
          $exists = Ambito::where('cod_ambito',$consultSection[16])->first();
          if(!$exists){
            array_push($detail_erros, [$lineCount, $lineCountWF, 17, "El valor del campo no correponde a un Ambito valido"]);
          }
        }
    }else{
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 17, "El campo no debe ser nulo"]);
    }

    //validacion campo 18
    if(isset($consultSection[17])) {
        if(strlen($consultSection[17]) != 8){
          $isValidRow = false;
        array_push($detail_erros, [$lineCount, $lineCountWF, 18, "El campo debe tener una longitud de 8 caracteres"]);
        }
    }else{
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 18, "El campo no debe ser nulo"]);
    }

    //validacion campo 20
    if(isset($consultSection[19])) {
        if(!preg_match("/^\d{2}$/", $consultSection[19])){
          $isValidRow = false;
        array_push($detail_erros, [$lineCount, $lineCountWF, 20, "El campo debe terner un vlor numérico de 2 dígitos"]);
        }
    }else{
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 20, "El campo no debe ser nulo"]);
    }

    //validacion campo 18(2), 19(3) y 20(4)
    if(isset($consultSection[18])) {
        if(!is_numeric($consultSection[18]) || strlen($consultSection[18]) != 1){
          $isValidRow = false;
        array_push($detail_erros, [$lineCount, $lineCountWF, 19, "El campo debe ser un número de un dígito"]);
        }else{
          switch ($consultSection[18]) {
            case '1':
              $exists = ConsultaCup::where('cod_consulta',intval($consultSection[17]))->first();
              if(!$exists){
                $isValidRow = false;
                array_push($detail_erros, [$lineCount, $lineCountWF, 18, "El valor del campo no correspondea un codigo de consutlas cups válido"]);
              }
              break;
            
            case '4':
              $exists = ConsultaHomologo::where('cod_consulta',$consultSection[19])->first();
              if(!$exists){
                $isValidRow = false;
                array_push($detail_erros, [$lineCount, $lineCountWF, 20, "El valor del campo no corresponde a un codigo de consutla especializado válido válido"]);
              }
              break;

            default:
                $isValidRow = false;
                array_push($detail_erros, [$lineCount, $lineCountWF, 19, "El campo debe ser 1 o 4."]);
              break;
          }

        }
    }else{
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 19, "El campo no debe ser nulo"]);
    }

    

    //validacion campo 21
    if(isset($consultSection[20])) {
      if($consultSection[19] != 99){
        if(strlen($consultSection[20]) >50 && $consultSection[20] == ''){
          $isValidRow = false;
        array_push($detail_erros, [$lineCount, $lineCountWF, 21, "El campo no dede ser nulo y debe tener una longitud menor o igual a 50"]);
        }
      }
        
    }else{
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 21, "El campo no debe ser nulo"]);
    }

    //validacion campo 22
    if(isset($consultSection[21])) {
        if(strlen($consultSection[21]) != 4){
          $isValidRow = false;
          array_push($detail_erros, [$lineCount, $lineCountWF, 22, "El campo debe tener un longitud de 4 caracteres."]);
        }else{
          $exists = DiagnosticoCiex::where('cod_diagnostico',$consultSection[21])->first();
          if(!$exists){
            $isValidRow = false;
            array_push($detail_erros, [$lineCount, $lineCountWF, 22, "El valor no corresponde a un valor código de diagnóstico valido"]);
          }
        }
    }else{
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 22, "El campo no debe ser nulo"]);
    }

    //validacion campo 23
    if(isset($consultSection[22])) {
        if(strlen($consultSection[22]) > 50 || $consultSection[22] == ''){
          $isValidRow = false;
        array_push($detail_erros, [$lineCount, $lineCountWF, 23, "El campo no debe ser vacio y debe tener una longitud menor o igual a 50 caracteres."]);
        }
    }else{
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 23, "El campo no debe ser nulo"]);
    }

    //validacion campo 24
    if(isset($consultSection[23])) {
      if(strlen(trim($consultSection[23])) != '')
      {
        if(strlen($consultSection[23]) != 4){
          $isValidRow = false;
          array_push($detail_erros, [$lineCount, $lineCountWF, 24, "El campo debe tener un longitud de 4 caracteres."]);
        }else{
          $exists = DiagnosticoCiex::where('cod_diagnostico',$consultSection[23])->first();
          if(!$exists){
            $isValidRow = false;
            array_push($detail_erros, [$lineCount, $lineCountWF, 24, "El valor no corresponde a un valor código de diagnóstico valido"]);
          }
        }
      }
        
    }else{
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 24, "El campo no debe ser nulo"]);
    }

    //validacion campo 25
    if(isset($consultSection[24])) {
      if(strlen(trim($consultSection[23])) != '')
      {
        if(strlen($consultSection[24]) > 50 || $consultSection[24] == ''){
          $isValidRow = false;
        array_push($detail_erros, [$lineCount, $lineCountWF, 25, "El campo no debe ser vacio y debe tener una longitud menor o igual a 50 caracteres."]);
        }
      }
    }else{
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 25, "El campo no debe ser nulo"]);
    }

    //validacion campo 26
    if(isset($consultSection[25])) {
      if(strlen(trim($consultSection[25])) != '')
      {
        if(strlen($consultSection[25]) != 4){
          $isValidRow = false;
          array_push($detail_erros, [$lineCount, $lineCountWF, 26, "El campo debe tener un longitud de 4 caracteres."]);
        }else{
          $exists = DiagnosticoCiex::where('cod_diagnostico',$consultSection[25])->first();
          if(!$exists){
            $isValidRow = false;
            array_push($detail_erros, [$lineCount, $lineCountWF, 26, "El valor no corresponde a un valor código de diagnóstico valido"]);
          }
        }
      }
    }else{
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 26, "El campo no debe ser nulo"]);
    }

    //validacion campo 27
    if(isset($consultSection[26])) {
      if(strlen(trim($consultSection[25])) != '')
      {
        if(strlen($consultSection[26]) > 50 || $consultSection[26] == ''){
          $isValidRow = false;
        array_push($detail_erros, [$lineCount, $lineCountWF, 27, "El campo no debe ser vacio y debe tener una longitud menor o igual a 50 caracteres."]);
        }
      }
    }else{
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 27, "El campo no debe ser nulo"]);
    }

    //validacion campo 28
    if(isset($consultSection[27])) {
        if(strlen($consultSection[27]) != 1){
          $isValidRow = false;
          array_push($detail_erros, [$lineCount, $lineCountWF, 28, "El campo debe terner una longitud igual a 1 caracter."]);
        }else{
          $exists = TipoDiagnostico::where('cod_tipo',$consultSection[27])->first();
          if (!$exists) {
            $isValidRow = false;
            array_push($detail_erros, [$lineCount, $lineCountWF, 28, "El campo no corresponde a la identificación tipo de diagnóstico válido."]);
          }
        }
    }else{
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 28, "El campo no debe ser nulo"]);
    }

    //validacion campo 29
    if(isset($consultSection[28])) {
        if(!preg_match("/^\d{2}$/", $consultSection[28])){
          $isValidRow = false;
          array_push($detail_erros, [$lineCount, $lineCountWF, 29, "El campo debe ser un valor numérico de 2 dígitos"]);
        }else{
          $exists = FinalidadConsultum::where('cod_finalidad',$consultSection[28])->first();
          if (!$exists) {
            $isValidRow = false;
            array_push($detail_erros, [$lineCount, $lineCountWF, 29, "El valor del campo no corresponde a un número de identificación de finalidad de consulta válido."]);
          }
        }
    }else{
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 29, "El campo no debe ser nulo"]);
    }
  }

  protected function validateDates(&$isValidRow, &$detail_erros, $lineCount, $lineCountWF,$firstRow ,$data)
  {

    if (strtotime($firstRow[3]) < strtotime($data[13]) ){
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 14, "La fecha de nacimiento (campo 14) debe ser inferior a la fecha final del periodo reportado  (línea 1, campo 4)"]);
    }

    //se valida que la fecha de nacimiento sa inferior a la fecha de consulta
    if (strtotime($data[15]) < strtotime($data[13]) ){
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 14, "La fecha de nacimiento (campo 14) debe ser inferior a la fecha de consulta (campo 16)"]);
    }

    //se valida que la fecha de consulta esté entre la fecha de los periodos
    if ( (strtotime($firstRow[2]) > strtotime($data[15])) || (strtotime($firstRow[3]) < strtotime($data[15])) ){
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 16, "La fecha de consulta (campo 16) debe estar registrada entre el periodo reportado. fecha incial(línea 1, campo 3) y fecha final (línea 1, campo 4) "]);
    }

  }

}