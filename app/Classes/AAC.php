<?php

namespace App\Classes;


use App\Traits\ToolsForFilesController;

class AAC {

	use ToolsForFilesController;
	private $handle;
	private $folder;



   function __construct($pathfolder, $pathFile) {
       if(!($this->handle = fopen($pathFile, 'r'))) throw new Exception("Error al abrir el archivo AAC");
       $this->folder = $pathfolder;
   }

   public function manageContent() {
   		
   		try {

   			$isValidRow = true;
   			$detail_erros = array(['No. línea archivo original', 'No. linea en archivo de errores','Campo', 'Detalle']);
   			$lineCount = 1;
   			$lineCountWF = 1;
   			$wrong_rows =  array();
   			$success_rows =  array();



   			//$firtsRow = fgetcsv($this->handle, 0, "|");
   			//$this->validateFirstRow($firtsRow);
   		
   			while($data = fgetcsv($this->handle, 0, "|")){
            	$isValidRow = true;
            	$this->validateEntitySection($isValidRow, $detail_erros, $lineCount, $lineCountWF, $data);

            	if(!$isValidRow){
            		$lineCount++;
   					$lineCountWF++;
   					array_push($wrong_rows, $data);
            	}
        	}


        	
        
        if(count($wrong_rows) > 0){
           
            $filewrongname = $this->folder.'RegistrosErroneos';
            
            $wrongfile = fopen($filewrongname, 'w');                              
            fprintf($wrongfile, chr(0xEF).chr(0xBB).chr(0xBF)); // darle formato unicode utf-8
            foreach ($wrong_rows as $row) {
                fputcsv($wrongfile, $row,'|');              
            }
            fclose($wrongfile);
            
            //----
            $detailsFilename =  $this->folder.'DetallesErrores';
            
            $detailsFileHandler = fopen($detailsFilename, 'w');
            fprintf($detailsFileHandler, chr(0xEF).chr(0xBB).chr(0xBF)); // darle formato unicode utf-8
            foreach ($detail_erros as $row) {
                fputcsv($detailsFileHandler, $row,'|');              
            }
            fclose($detailsFileHandler);
            
        }
        
        if(count($success_rows) > 0){ //porque la primera fila es corresponde a los titulos no datos
            $arrayIdsFilename = $this->folder.'registrosExitosos';
            
            $arrayIdsFileHandler = fopen($arrayIdsFilename, 'w');
            fprintf($arrayIdsFileHandler, chr(0xEF).chr(0xBB).chr(0xBF)); // darle formato unicode utf-8
            foreach ($success_rows as $row) {
                fputcsv($arrayIdsFileHandler, $row, '|');              
            }
            fclose($arrayIdsFileHandler);
            
            $response = new \stdClass();
            
            if(count($wrong_rows) > 0){
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


   			
   		} catch (\Exception $e) {
   			$response = new \stdClass();
   			$response->error = $e->getMessage();
   			echo json_encode($response);
   		}

   }
}