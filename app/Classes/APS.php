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

use App\Models\DiagnosticoCiex;
use App\Models\ProcedimientoCup;
use App\Models\ProcedimientoHomologo;
use App\Models\ProcedimientosQNq;
use App\Models\GiossArchivoApsCfvl;

class APS {

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
    if(!($this->handle = fopen($pathfolder.$fileName, 'r'))) throw new Exception("Error al abrir el archivo APS");
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
          $this->archivo->id_tema_informacion = 'APS';
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
      
      
      
      $firtsRow = fgetcsv($this->handle, 0, "|");
      
      $this->validateFirstRow($isValidFirstRow, $this->detail_erros, $firtsRow);

      if ($isValidFirstRow && $isValidFile) {

        //se adicionan terminan de definir los prametros el archivo
        $this->archivo->fecha_ini_periodo = strtotime($firtsRow[2]);
        $this->archivo->fecha_fin_periodo = strtotime($firtsRow[3]);
        $entidad = EntidadesSectorSalud::where('cod_habilitacion', $firtsRow[0])->first();
        $this->archivo->id_entidad = $entidad->id_entidad;
        $this->archivo->numero_registros = $firtsRow[4];
        $this->archivo->save();

        $this->file_status->total_registers =  $firtsRow[4];
        $this->file_status->save();

        $lineCount = 2;
        $lineCountWF = 2;
        //se valida cada línea
        while($data = fgetcsv($this->handle, 10000, "|"))
        {
          $isValidRow = true;
          $this->validateEntitySection($isValidRow, $this->detail_erros, $lineCount, $lineCountWF, array_slice($data,0,6));
          $this->validateUserSection($isValidRow, $this->detail_erros, $lineCount, $lineCountWF, array_slice($data,6,9,true));
          $this->validateAPS($isValidRow, $this->detail_erros, $lineCount, $lineCountWF, array_slice($data,15,7,true));

          if(!$isValidRow){
            
            array_push($this->wrong_rows, $data);
            $this->updateStatusFile($lineCount); //se acatualiza la lienea ya tratada
            $lineCount++;
            $lineCountWF++;
            continue;
          }else{
            //se guarda todo el registro en en la tabla soporte
                $tabla = new GiossArchivoApsCfvl();
                $tabla->fecha_periodo_inicio = $this->archivo->fecha_ini_periodo;
                $tabla->fecha_periodo_fin = $this->archivo->fecha_fin_periodo;
                $tabla->nombre_archivo = $this->fileName;;
                $tabla->numero_registro = $lineCount;
                $tabla->contenido_registro_validado = implode('|', $data);
                $tabla->fecha_hora_validacion = tiem() ;
                $tabla->save();

              //
              // alamacena en la dimension
            //se valida duplicidad en la informacion
            $exists = DB::table('procedimientos_q_nq')
            ->join('registro', 'procedimientos_q_nq.id_registro', '=', 'registro.id_registro_seq')
            ->join('archivo', 'registro.id_registro_seq', '=', 'archivo.id_archivo_seq')
            ->join('eapbs', 'registro.id_registro_seq', '=', 'eapbs.id_entidad')
            ->join('user_ips', 'registro.id_user', '=', 'user_ips.id_user')
              ->where('archivo.fecha_ini_periodo', strtotime($firtsRow[2]))
              ->where('archivo.fecha_fin_periodo', strtotime($firtsRow[3]))
              ->where('eapbs.num_identificacion', $data[3])
              ->where('user_ips.num_identificacion', $data[8])
              ->where('procedimientos_q_nq.fecha_procedimiento', $data[15])
              ->where('procedimientos_q_nq.tipo_codificacion', $data[17])
              ->where('procedimientos_q_nq.cod_procedimiento', $data[16])
              ->where('procedimientos_q_nq.cod_diagnostico_principal', $data[18])
              ->where('procedimientos_q_nq.cod_diagnostico_rel1', $data[20])
              ->where('procedimientos_q_nq.cod_diagnostico_rel2', $data[21])
            ->firts();

            if($exists){
              
              array_push($this->detail_erros, [$lineCount, $lineCountWF, '', "Registro duplicado"]);
              array_push($this->wrong_rows, $data);
              $this->updateStatusFile($lineCount);
              $lineCountWF++;
              $lineCount++;
              continue;
            }else
            {
              
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

              $eapb  = Eapb::where('num_identificacion', $data[3])
                              ->where('cod_eapb', $data[4])->first();

              $register->id_eapb = $eapb->id_entidad;
              $register->save();

              //se almacena la información correpondiente al procedimiento
              $procedimiento = new ProcedimientosQNq();
              $procedimiento->id_registro = $register->id_registro_seq;
              $procedimiento->fecha_procedimiento = $data[15];
              $procedimiento->tipo_codificacion = $data[17];
              $procedimiento->cod_procedimiento = $data[16];
              $procedimiento->cod_diagnostico_principal = $data[18];
              $procedimiento->cod_diagnostico_rel1 = $data[20];
              $procedimiento->ambito_procedimiento = $data[21];
            
              $procedimiento->save();

            }  
          }
          $this->updateStatusFile($lineCount);
          $lineCount++;
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
    //se actualiza el porcentaje
    $register_num = $this->archivo->numero_registros;
    $Porcent =  $this->file_status->porcent;
    $currentPorcent = ( ($lineCount - 1)  / $register_num)*100;

    Log::info("Número de registros " . $register_num);
    Log::info("porcentaje almacenado " . $Porcent);
    Log::info("porcentaje actual " . $currentPorcent);

    $dif = $currentPorcent -  $Porcent;
    if ($dif >= 1){
      $this->file_status->current_line = $lineCount - 1;
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
        $this->createZip($this->folder, $zipname);
        
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


  private function validateAPS(&$isValidRow, &$detail_erros, $lineCount, $lineCountWF,$consultSection) {

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
          array_push($detail_erros, [$lineCount, $lineCountWF, 17, "El campo debe terner una longitud igual a 8 caracteres"]);
        }
        
    }else{
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 17, "El campo no debe ser nulo"]);
    }

    //validacion campo 18
     if(isset($consultSection[17])) {
        
        switch ($consultSection[17]) {
          case '1':
            $exists = ProcedimientoCup::where('cod_procedimiento',$consultSection[16])->first();
            if(!$exists){
              $isValidRow = false;
              array_push($detail_erros, [$lineCount, $lineCountWF, 17, "El valor del campo no correspondea un codigo de procedimiento cups válido"]);
            }
            break;

          case '4':
            $exists = ProcedimientoHomologo::where('cod_procedimiento',$consultSection[16])->first();
            if(!$exists){
              $isValidRow = false;
              array_push($detail_erros, [$lineCount, $lineCountWF, 17, "El valor del campo no correspondea a un codigo de procedimiento homologo válido"]);
            }
            break;

          default:
            $isValidRow = false;
            array_push($detail_erros, [$lineCount, $lineCountWF, 18, "El campo debe ser un número con un valor de 1 ó 4"]);
            break;
        }

        
    }else{
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 18, "El campo no debe ser nulo"]);
    }

    //validacion campo 19
    if(isset($consultSection[18])) {
        
        $exists = DiagnosticoCiex::where('cod_diagnostico',$consultSection[18])->first();
        if(!$exists){
          $isValidRow = false;
          array_push($detail_erros, [$lineCount, $lineCountWF, 19, "El valor no corresponde a un valor código de diagnóstico valido"]);
        }
        
    }else{
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 19, "El campo no debe ser nulo"]);
    }

    //validacion campo 20
    if(isset($consultSection[19])) {
        
        if(strlen($consultSection[19]) > 50 ){
          $isValidRow = false;
        array_push($detail_erros, [$lineCount, $lineCountWF, 20, "El campo  debe tener una longitud menor o igual a 50"]);
        }
    }else{
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 20, "El campo no debe ser nulo"]);
    }

    //validación campo 21
    if(isset($consultSection[20])) {
        if(strlen($consultSection[18]) != ''){
          $exists = DiagnosticoCiex::where('cod_diagnostico',$consultSection[20])->first();
          if(!$exists){
            $isValidRow = false;
            array_push($detail_erros, [$lineCount, $lineCountWF, 21, "El valor no corresponde a un valor código de diagnóstico valido"]);
          }
        }
        
        
    }else{
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 21, "El campo no debe ser nulo"]);
    }

    //validación campo 22
    if(isset($consultSection[21])) {

      if(strlen($consultSection[21]) != 1){
          $isValidRow = false;
        array_push($detail_erros, [$lineCount, $lineCountWF, 22, "El campo de tener una longitud igual a 1"]);
        }else{
          $exists = Ambito::where('cod_ambito',$consultSection[21])->first();
          if(!$exists){
            array_push($detail_erros, [$lineCount, $lineCountWF, 22, "El valor del campo no correponde a un Ambito valido"]);
          }
        }
    }else{
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 22, "El campo no debe ser nulo"]);
    }

  }


}