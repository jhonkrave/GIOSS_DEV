<?php

namespace App\Classes;


use App\Traits\ToolsForFilesController;
use App\Models\FileStatus;
use App\Models\Archivo;
use App\Models\UserIp;
use App\Models\Registro;
use App\Models\Eapb;
use App\Models\EntidadesSectorSalud;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\Vacunacion;
use App\Models\VacunaCup;
use App\Models\VacunaHomologo;
use App\Models\GiossArchivoAvaCfvl;


class AVA {

	use ToolsForFilesController;
  private $handle;
  private $folder;
  private $fileName;
  private $version;
  private $consecutive;
  private $detail_erros;
  private $wrong_rows;
  private $success_rows;
  private $file_status;
  private $archivo;



  function __construct($pathfolder, $fileName,$consecutive) {
    if(!($this->handle = fopen($pathfolder.$fileName, 'r'))) throw new Exception("Error al abrir el archivo AEH");
    //dd($fileName);
    $this->folder = $pathfolder;

    $fileNameToken = explode('.',$fileName);
    $this->fileName =  substr($fileNameToken[0],0,58);
    $this->version = substr($fileNameToken[0],58);

    $this->consecutive = $consecutive;
    $this->detail_erros = array(['No. línea archivo original', 'No. linea en archivo de errores','Campo', 'Detalle']);
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
          $this->archivo->id_tema_informacion = 'AEH';
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
          $this->validateAEH($isValidRow, $this->detail_erros, $lineCount, $lineCountWF, array_slice($data,15,14,true));

          if(!$isValidRow){
            
            array_push($this->wrong_rows, $data);
            $this->updateStatusFile($lineCount); //se acatualiza la lienea ya tratada
            $lineCount++;
            $lineCountWF++;
            continue;
          }else{
              
            //se valida duplicidad en la informacion
            $exists = DB::table('vacunacion')
            ->join('registro', 'vacunacion.id_registro', '=', 'registro.id_registro_seq')
            ->join('archivo', 'registro.id_registro_seq', '=', 'archivo.id_archivo_seq')
            ->join('eapbs', 'registro.id_registro_seq', '=', 'eapbs.id_entidad')
            ->join('user_ips', 'registro.id_user', '=', 'user_ips.id_user')
              ->where('archivo.fecha_ini_periodo', strtotime($firstRow[2]))
              ->where('archivo.fecha_fin_periodo', strtotime($firstRow[3]))
              ->where('user_ips.num_identificacion', $data[8])
              ->where('eapbs.num_identificacion', ltrim($data[3],'0'))
              ->where('vacunacion.fecha_aplicacion', $data[15])
              ->where('vacunacion.tipo_codificacion', $data[17])
              ->where('vacunacion.codigo_tipo_vacuna', $data[16])
              ->where('vacunacion.numero_dosis', $data[18])
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
                $tabla = new GiossArchivoAvaCfvl();
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

              //se almacena la información correpondiente a la vacunacion
              $vacunacion = new Vacunacion();
              $vacunacion->id_registro = $register->id_registro_seq;
              $vacunacion->fecha_aplicacion = $data[15];
              $vacunacion->tipo_codificacion = $data[17];
              $vacunacion->codigo_tipo_vacuna = $data[16];
              $vacunacion->numero_dosis = $data[18];

              $vacunacion->save();

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

  private function updateStatusFile($lineCount)
  {
    $line = $lineCount;
    //se actualiza el porcentaje
    $register_num = $this->archivo->numero_registros;
    $Porcent =  $this->file_status->porcent;
    $currentPorcent = ( ($lineCount - 1)  / $register_num)*100;

    Log::info("Número de registros " . $register_num);
    Log::info("porcentaje almacenado " . $Porcent);
    Log::info("porcentaje actual " . $currentPorcent);

    $dif = $currentPorcent -  $Porcent;
    if ($dif >= 1){
      $this->file_status->current_line = $line;
      $this->file_status->porcent = intval($currentPorcent);
      $this->file_status->save();
    }
    return true;
  }

  private function generateFiles() {

    if(count($this->wrong_rows) > 0){
      
      $filewrongname = $this->folder.'RegistrosErroneos';
      //dd('entro');
      $wrongfile = fopen($filewrongname, 'w');                              
      fprintf($wrongfile, chr(0xEF).chr(0xBB).chr(0xBF)); // darle formato unicode utf-8
      foreach ($this->wrong_rows as $row) {
          fputcsv($wrongfile, $row,'|');              
      }
      fclose($wrongfile);
      
      
    }

    if(count($this->detail_erros) > 1){
      //----se genera el archivo de detalles de error
      $detailsFilename =  $this->folder.'DetallesErrores';
      
      $detailsFileHandler = fopen($detailsFilename, 'w');
      fprintf($detailsFileHandler, chr(0xEF).chr(0xBB).chr(0xBF)); // darle formato unicode utf-8
      foreach ($this->detail_erros as $row) {
          fputcsv($detailsFileHandler, $row,'|');              
      }
      fclose($detailsFileHandler);
    }

    if(count($this->success_rows) > 0){
        $arrayIdsFilename = $this->folder.'registrosExitosos';
        
        $arrayIdsFileHandler = fopen($arrayIdsFilename, 'w');
        fprintf($arrayIdsFileHandler, chr(0xEF).chr(0xBB).chr(0xBF)); // darle formato unicode utf-8
        foreach ($this->success_rows as $row) {
            fputcsv($arrayIdsFileHandler, $row, '|');              
        }
        fclose($arrayIdsFileHandler);
        

        
        if(count($this->wrong_rows) > 0){
          $this->file_status->final_status = 'REGULAR';
          
        }else{
          $this->file_status->final_status = 'SUCCESS';
        }
        
        $zipname = 'detalles'.time().'.zip';
        $zipsavePath = storage_path('archivos').'/../../public/zips/'.$zipname;
        $this->createZip($this->folder, $zipsavePath);
        
        $this->file_status->zipath = asset('zips/'.$zipname);
        $this->file_status->current_status = 'COMPLETED';
        $this->file_status->save();

        return true;
        
    }else{
        
        $this->file_status->final_status = 'FAILURE';

        
        $zipname = 'detalles'.time().'.zip';
        $zipsavePath = storage_path('archivos').'/../../public/zips/'.$zipname;
        //dd($zipsavePath);
        $this->createZip($this->folder, $zipsavePath);
        
        $this->file_status->zipath = asset('zips/'.$zipname);
        $this->file_status->current_status = 'COMPLETED';
        $this->file_status->save();

        return true;
    }

  }


  private function validateAVA(&$isValidRow, &$detail_erros, $lineCount, $lineCountWF,$consultSection) {

    //validacion campo 16
    if(isset($consultSection[15])) {
        if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $consultSection[15])){
          $isValidRow = false;
        array_push($detail_erros, [$lineCount, $lineCountWF, 16, "El campo debe terner el formato AAAA-MM-DD"]);
        }
    }else{
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 16, "El campo no debe ser nulo"]);
    }

    //validacion campo 17
    if(isset($consultSection[16])) {
        if(strlen($consultSection[16]) != 8){
          $isValidRow = false;
        array_push($detail_erros, [$lineCount, $lineCountWF, 17, "El campo de tener una longitud igual a 8"]);
        }
    }else{
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 17, "El campo no debe ser nulo"]);
    }

    //validacion campo 18
    if(isset($consultSection[17])) {
        if(!is_numeric($consultSection[17]) || strlen($consultSection[17]) != 1){
          $isValidRow = false;
        array_push($detail_erros, [$lineCount, $lineCountWF, 18, "El campo debe ser un número de un dígito"]);
        }else{
          switch ($consultSection[17]) {
            case '1':
              $exists = VacunaCup::where('codigo_tipo_vacuna',$consultSection[16])->first();
              if(!$exists){
                $isValidRow = false;
                array_push($detail_erros, [$lineCount, $lineCountWF, 17, "El valor del campo no correspondea un codigo de vacuna cups válido"]);
              }
              break;
            
            case '4':
              $exists = VacunaHomologo::where('codigo_tipo_vacuna',$consultSection[16])->first();
              if(!$exists){
                $isValidRow = false;
                array_push($detail_erros, [$lineCount, $lineCountWF, 17, "El valor del campo no corresponde a un codigo de vacuna homologa válida"]);
              }
              break;
          }

        }
    }else{
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 18, "El campo no debe ser nulo"]);
    }

    

    //validacion campo 19
    if(isset($consultSection[18])) {
        if(!is_numeric($consultSection[18]) && ($consultSection[18] > 5 || $consultSection[18] < 1) ){
          $isValidRow = false;
        array_push($detail_erros, [$lineCount, $lineCountWF, 19, "El campo debe tener un valor entre 1 y 5"]);
        }
      
        
    }else{
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 19, "El campo no debe ser nulo"]);
    }

   
  }

}