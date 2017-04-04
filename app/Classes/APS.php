<?php

namespace App\Classes;


use App\Traits\ToolsForFilesController;
use App\Models\DiagnosticoCiex;
use App\Models\ProcedimientoCup;
use App\Models\ProcedimientoHomologo;
use App\Models\ProcedimientosQNq;
use App\Models\DiagnosticoCiex;

class APS {

  use ToolsForFilesController;
  private $handle;
  private $folder;
  private $detail_erros;
  private $wrong_rows;
  private $success_rows;



  function __construct($pathfolder, $pathFile) {
    if(!($this->handle = fopen($pathFile, 'r'))) throw new Exception("Error al abrir el archivo APS");
    $this->folder = $pathfolder;
    $this->detail_erros = array(['No. línea archivo original', 'No. linea en archivo de errores','Campo', 'Detalle']);
    $this->wrong_rows =  array();
    $this->success_rows =  array();
  }

  public function manageContent() {

    try {

      $lineCount = 1;
      $lineCountWF = 1;
      $firtsRow = fgetcsv($this->handle, 0, "|");
      $firtsRowValidation = $this->validateFirstRow($firtsRow);

      if ($firtsRowValidation->isValidRow) {
        
        while($data = fgetcsv($this->handle, 0, "|")){
          $isValidRow = true;
          $this->validateEntitySection($isValidRow, $this->detail_erros, $lineCount, $lineCountWF, array_slice($data,0,6));
          $this->validateUserSection($isValidRow, $this->detail_erros, $lineCount, $lineCountWF, array_slice($data,6,9,true));
          $this->validateAPS($isValidRow, $this->detail_erros, $lineCount, $lineCountWF, array_slice($data,15,7,true));

          if(!$isValidRow){
            $lineCount++;
            $lineCountWF++;
            array_push($this->wrong_rows, $data);
          }else{
            //save info
            $lineCount++;
          }
        }

        $this->generateFiles();

      }else{
        $response = new \stdClass();
        $response->error = $firtsRowValidation->msj;
        echo json_encode($response);
      }
    
    } catch (\Exception $e) {
      $response = new \stdClass();
      $response->error = $e->getMessage();
      echo json_encode($response);
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
          $exists = Ambito::where('ambito',$consultSection[21])->first();
          if(!$exists){
            array_push($detail_erros, [$lineCount, $lineCountWF, 22, "El valor del campo no correponde a un Ambito valido"]);
          }
        }
    }else{
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 22, "El campo no debe ser nulo"]);
    }

  }

  private function generateFiles() {

    if(count($this->wrong_rows) > 0){
             
      $filewrongname = $this->folder.'RegistrosErroneos';
      
      $wrongfile = fopen($filewrongname, 'w');                              
      fprintf($wrongfile, chr(0xEF).chr(0xBB).chr(0xBF)); // darle formato unicode utf-8
      foreach ($this->wrong_rows as $row) {
          fputcsv($wrongfile, $row,'|');              
      }
      fclose($wrongfile);
      
      //----se genera el archivo de detalles de error
      $detailsFilename =  $this->folder.'DetallesErrores';
      
      $detailsFileHandler = fopen($detailsFilename, 'w');
      fprintf($detailsFileHandler, chr(0xEF).chr(0xBB).chr(0xBF)); // darle formato unicode utf-8
      foreach ($this->detail_erros as $row) {
          fputcsv($detailsFileHandler, $row,'|');              
      }
      fclose($detailsFileHandler);
      
    }

    if(count($this->success_rows) > 0){ //porque la primera fila es corresponde a los titulos no datos
        $arrayIdsFilename = $this->folder.'registrosExitosos';
        
        $arrayIdsFileHandler = fopen($arrayIdsFilename, 'w');
        fprintf($arrayIdsFileHandler, chr(0xEF).chr(0xBB).chr(0xBF)); // darle formato unicode utf-8
        foreach ($this->success_rows as $row) {
            fputcsv($arrayIdsFileHandler, $row, '|');              
        }
        fclose($arrayIdsFileHandler);
        
        $response = new \stdClass();
        
        if(count($this->wrong_rows) > 0){
            $response->warning = 'Archivo cargado con inconsistencias<br> Para mayor informacion descargar la carpeta con los detalles de inconsitencias.'; 
        }else{
            $response->success = 'Archivo cargado satisfactoriamente';
        }
        
        $zipname = 'detalles'.time().'.zip';
        $zipsavePath = storage_path('archivos').'/../../public/zips/'.$zipname;
        $this->createZip($this->folder, $zipname);
        
        $response->urlzip = "<a href='".$zipname."'>Descargar detalles</a>";
        
        echo json_encode($response);
        
    }else{
        $response = new \stdClass();
        $response->error = "No se cargo el archivo. Para mayor informacion descargar la carpeta con los detalles de inconsitencias.";
        
        $zipname = 'detalles'.time().'.zip';
        $zipsavePath = storage_path('archivos').'/../../public/zips/'.$zipname;
        //dd($zipsavePath);
        $this->createZip($this->folder, $zipsavePath);
        
        $response->urlzip = "<a href='".asset('zips/'.$zipname)."'>Descargar detalles</a>";
        
        echo json_encode($response);
    }

  }


}