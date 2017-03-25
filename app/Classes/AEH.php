<?php

namespace App\Classes;


use App\Traits\ToolsForFilesController;
use App\Models\DiagnosticoCiex;
use App\Models\TipoDiagnostico;
use App\Models\FinalidadConsultum;

class AEH {

  use ToolsForFilesController;
  private $handle;
  private $folder;
  private $detail_erros;
  private $wrong_rows;
  private $success_rows;



  function __construct($pathfolder, $pathFile) {
    if(!($this->handle = fopen($pathFile, 'r'))) throw new Exception("Error al abrir el archivo AEH");
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
          $this->validateAEH($isValidRow, $this->detail_erros, $lineCount, $lineCountWF, array_slice($data,15,14,true));

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


  private function validateAEH(&$isValidRow, &$detail_erros, $lineCount, $lineCountWF,$consultSection) {

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
        if(!preg_match("/^(2[0-3]|[01][0-9]):([0-5][0-9])$/", $consultSection[16])){
          $isValidRow = false;
          array_push($detail_erros, [$lineCount, $lineCountWF, 17, "El campo debe terner el formato HH:MM(Hora Militar)"]);
        }
    }else{
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 17, "El campo no debe ser nulo"]);
    }

    //validacion campo 18
    if(isset($consultSection[17])) {
        if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $consultSection[17])){
          $isValidRow = false;
          array_push($detail_erros, [$lineCount, $lineCountWF, 18, "El campo debe terner el formato AAAA-MM-DD"]);
        }
    }else{
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 18, "El campo no debe ser nulo"]);
    }

    //validacion campo 19
    if(isset($consultSection[18])) {
        if(!preg_match("/^(2[0-3]|[01][0-9]):([0-5][0-9])$/", $consultSection[18])){
          $isValidRow = false;
          array_push($detail_erros, [$lineCount, $lineCountWF, 19, "El campo debe terner el formato HH:MM(Hora Militar)"]);
        }
    }else{
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 19, "El campo no debe ser nulo"]);
    }

    //validacion campo 20
    if(isset($consultSection[19])) {
        if(strlen($consultSection[19] != 4)){
          $isValidRow = false;
          array_push($detail_erros, [$lineCount, $lineCountWF, 20, "El campo debe tener un longitud de 4 caracteres."]);
        }else{
          $exists = DiagnosticoCiex::where('cod_diagnostico',$consultSection[19])->first();
          if(!$exists){
            $isValidRow = false;
            array_push($detail_erros, [$lineCount, $lineCountWF, 20, "El valor no corresponde a un valor código de diagnóstico valido"]);
          }
        }
    }else{
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 20, "El campo no debe ser nulo"]);
    }

    //validacion campo 21
    if(isset($consultSection[20])) {
        if(strlen($consultSection[20]) > 50 && $consultSection[20] == ''){
          $isValidRow = false;
        array_push($detail_erros, [$lineCount, $lineCountWF, 21, "El campo no dede ser vacio y debe tener una longitud menor o igual a 50"]);
        }
        
    }else{
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 21, "El campo no debe ser nulo"]);
    }

    //validacion campo 22
    if(isset($consultSection[21])) {
        if(strlen($consultSection[21] != 4)){
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
        if(strlen($consultSection[22]) > 50 && $consultSection[22] == ''){
          $isValidRow = false;
        array_push($detail_erros, [$lineCount, $lineCountWF, 23, "El campo no dede ser vacio y debe tener una longitud menor o igual a 50"]);
        }
    }else{
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 23, "El campo no debe ser nulo"]);
    }

    //validacion campo 24
    if(isset($consultSection[23])) {
        if(strlen($consultSection[23]) != ''){

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
        if(strlen($consultSection[24]) != ''){

          if(strlen($consultSection[24]) != 4){
            $isValidRow = false;
            array_push($detail_erros, [$lineCount, $lineCountWF, 25, "El campo debe tener un longitud de 4 caracteres."]);
          }else{
            $exists = DiagnosticoCiex::where('cod_diagnostico',$consultSection[24])->first();
            if(!$exists){
              $isValidRow = false;
              array_push($detail_erros, [$lineCount, $lineCountWF, 25, "El valor no corresponde a un valor código de diagnóstico valido"]);
            }
          }

        }
        
    }else{
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 25, "El campo no debe ser nulo"]);
    }

    //validacion campo 26
    if(isset($consultSection[25])) {
        if(!preg_match("/^[1-2]$/", $consultSection[25])){
          $isValidRow = false;
          array_push($detail_erros, [$lineCount, $lineCountWF, 26, "El valor del campo no correponde a un valor valido: 1(vivo) - 2(muerto)"]);
        }
    }else{
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 26, "El campo no debe ser nulo"]);
    }

    //validacion campo 27
    if(isset($consultSection[26])) {
        if(strlen($consultSection[25]) == 2){ //si el anterior registro lo reporta como muerto (2)

          if(strlen($consultSection[26]) != 4){
            $isValidRow = false;
            array_push($detail_erros, [$lineCount, $lineCountWF, 27, "El campo debe tener un longitud de 4 caracteres."]);
          }else{
            $exists = DiagnosticoCiex::where('cod_diagnostico',$consultSection[26])->first();
            if(!$exists){
              $isValidRow = false;
              array_push($detail_erros, [$lineCount, $lineCountWF, 27, "El valor no corresponde a un valor código de diagnóstico valido"]);
            }
          }

        }
        
    }else{
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 27, "El campo no debe ser nulo"]);
    }

    //validacion campo 28
    if(isset($consultSection[27])) {
        if(strlen($consultSection[27]) > 50 && $consultSection[27] == ''){
          $isValidRow = false;
          array_push($detail_erros, [$lineCount, $lineCountWF, 28, "El campo no dede ser vacio y debe tener una longitud menor o igual a 50"]);
        }
    }else{
      $isValidRow = false;
      array_push($detail_erros, [$lineCount, $lineCountWF, 28, "El campo no debe ser nulo"]);
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