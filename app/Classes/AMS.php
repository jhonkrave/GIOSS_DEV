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
use App\Models\MedicamentosAtc;
use App\Models\MedicamentosCum;
use App\Models\MedicamentosHomologo;
use App\Models\MedicamentosR;
use App\Models\Medicamento;
use App\Models\GiossArchivoAmsCfvl;

class AMS extends FileValidator {


  function __construct($pathfolder, $fileName,$consecutive) {
    if(!($this->handle = fopen($pathfolder.$fileName, 'r'))) throw new Exception("Error al abrir el archivo AMS");
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
      Log::info("entro managecontent");
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
          $this->archivo->id_tema_informacion = 'AMS';
          $this->archivo->save();

          $fileid = $this->archivo->id_archivo_seq;

      }

      // se inicializa el objeto file_status 
      $this->file_status =  new FileStatus();
      $this->file_status->consecutive = $this->consecutive;
      $this->file_status->archivoid = $fileid;
      $this->file_status->current_status = 'WORKING';

      $this->file_status->save();  
      Log::info("entro archivos creados");

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
          Log::info("valido entidad: ".$lineCount);
          $this->validateUserSection($isValidRow, $this->detail_erros, $lineCount, $lineCountWF, array_slice($data,6,9,true));
          Log::info("valido user: ".$lineCount);
          $this->validateAMS($isValidRow, $this->detail_erros, $lineCount, $lineCountWF, array_slice($data,15,5,true));
          Log::info("valido ams: ".$lineCount);
          if(!$isValidRow){
            Log::info("linea invalida".$lineCount);
            array_push($this->wrong_rows, $data);
            $this->updateStatusFile($lineCount); //se acatualiza la lienea ya tratada
            $lineCount++;
            $lineCountWF++;
            continue;
          }else{
              Log::info("entro a guardar: ".$lineCount);
            //se valida duplicidad en la informacion
            $exists = DB::table('medicamento')
            ->join('registro', 'medicamento.id_registro', '=', 'registro.id_registro_seq')
            ->join('archivo', 'registro.id_registro_seq', '=', 'archivo.id_archivo_seq')
            ->join('eapbs', 'registro.id_registro_seq', '=', 'eapbs.id_entidad')
            ->join('user_ips', 'registro.id_user', '=', 'user_ips.id_user')
              ->where('archivo.fecha_ini_periodo', strtotime($firstRow[2]))
              ->where('archivo.fecha_fin_periodo', strtotime($firstRow[3]))
              ->where('user_ips.num_identificacion', $data[8])
              ->where('eapbs.num_identificacion', ltrim($data[3],'0'))
              ->where('medicamento.fecha_entrega', strtotime($data[15]))
              ->where('medicamento.tipo_codificacion', $data[17])
              ->where('medicamento.codigo_medicamento', $data[16])
              ->where('medicamento.catidad', $data[18])
              ->where('medicamento.ambito_suministro', $data[19])
            ->first();

            if($exists){
              
              array_push($this->detail_erros, [$lineCount, $lineCountWF, '', "Registro duplicado"]);
              array_push($this->wrong_rows, $data);
              $this->updateStatusFile($lineCount);
              $lineCountWF++;
              $lineCount++;
              continue;
            }
            else
            {
              //se guarda todo el registro en en la tabla soporte
                $tabla = new GiossArchivoAmsCfvl();
                $tabla->fecha_periodo_inicio = $this->archivo->fecha_ini_periodo;
                $tabla->fecha_periodo_fin = $this->archivo->fecha_fin_periodo;
                $tabla->nombre_archivo = $this->fileName;
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
                Log::info("crea nuevo user");
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

              $eapb  = Eapb::where('num_identificacion', ltrim($data[3],'0'))
                              ->where('cod_eapb', $data[4])->first();

              $register->id_eapb = $eapb->id_entidad;
              $register->save();

              Log::info("se cre anuevo medicamento ");
              //se almacena la informración correpondiente al Medicamento suministrado
              $medicamento = new Medicamento();
              $medicamento->id_registro = $register->id_registro_seq;
              $medicamento->fecha_entrega = strtotime($data[15]);
              $medicamento->tipo_codificacion = $data[17];
              $medicamento->codigo_medicamento = $data[16];
              $medicamento->catidad = $data[18];
              $medicamento->ambito_suministro = $data[19];
  
              $medicamento->save();

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

  private function validateAMS(&$isValidRow, &$detail_erros, $lineCount, $lineCountWF,$consultSection) {

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
    Log::info("termino validacion camp 16 ");

    //validacion campo 17
    if(isset($consultSection[16])) {
        if(strlen($consultSection[16]) > 20){
          $isValidRow = false;
          array_push($detail_erros, [$lineCount, $lineCountWF, 17, "El campo debe terner una longitud igual a 20 caracteres"]);
        }
        
    }else{
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 17, "El campo no debe ser nulo"]);
    }
    Log::info("termino validacion camp 17 ");

    //validacion campo 18
     if(isset($consultSection[17])) {
        
        switch ($consultSection[17]) {
          case '1':
            $exists = MedicamentosCum::where('codigo_medicamento',$consultSection[16])->first();
            if(!$exists){
              $isValidRow = false;
              array_push($detail_erros, [$lineCount, $lineCountWF, 17, "El valor del campo no correspondea un codigo de medicamentos CUM válido"]);
            }
            break;
          
          case '2':
            $exists = MedicamentosAtc::where('codigo_medicamento',$consultSection[16])->first();
            if(!$exists){
              $isValidRow = false;
              array_push($detail_erros, [$lineCount, $lineCountWF, 17, "El valor del campo no correspondea un codigo de medicamentos atc válido"]);
            }
            break;

          case '3':
            $exists = MedicamentosR::where('codigo_medicamento',$consultSection[16])->first();
            if(!$exists){
              $isValidRow = false;
              array_push($detail_erros, [$lineCount, $lineCountWF, 17, "El valor del campo no correspondea un codigo de medicamentos registro sanitario válido"]);
            }
            break;

          case '4':
            $exists = MedicamentosHomologo::where('codigo_medicamento',$consultSection[16])->first();
            if(!$exists){
              $isValidRow = false;
              array_push($detail_erros, [$lineCount, $lineCountWF, 17, "El valor del campo no correspondea un codigo de medicamentos homologo válido"]);
            }
            break;

          default:
            $isValidRow = false;
            array_push($detail_erros, [$lineCount, $lineCountWF, 19, "El campo debe ser un número con un valor entre 1 y 4"]);
            break;
        }

        
    }else{
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 19, "El campo no debe ser nulo"]);
    }
    Log::info("termino validacion camp 18 ");

    //validacion campo 19
    if(isset($consultSection[18])) {
        if(!is_numeric(trim($consultSection[18]))){
          $isValidRow = false;
          array_push($detail_erros, [$lineCount, $lineCountWF, 19, "El campo debe ser un valor entero ".$consultSection[18]]);
        }
    }else{
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 19, "El campo no debe ser nulo"]);
    }

    Log::info("termino validacion camp 19 ");

    //validacion campo 20
    if(isset($consultSection[19])) {
        if(strlen($consultSection[19]) != 1){
          $isValidRow = false;
        array_push($detail_erros, [$lineCount, $lineCountWF, 20, "El campo de tener una longitud igual a 1"]);
        }else{
          $exists = Ambito::where('cod_ambito',$consultSection[19])->first();
          if(!$exists){
            array_push($detail_erros, [$lineCount, $lineCountWF, 20, "El valor del campo no correponde a un Ambito valido"]);
          }
        }
    }else{
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 20, "El campo no debe ser nulo"]);
    }
    Log::info("termino validacion camp 20 ");


  }


}