<?php

namespace App\Classes;

use App\Models\EntidadesSectorSalud;
use App\Models\TipoEntidad;
use App\Models\TipoIdentificacionEntidad;
use App\Models\TipoIdentificacionUser;
use App\Models\GenerosUser;
use App\Models\Eapb;
use App\Models\TipoEapb;
use App\Models\TipoIdentEapb;
use App\Models\FileStatus;
use Illuminate\Support\Facades\Log;

class FileValidator {

	protected $handle;
	protected $folder;
	protected $fileName;
	protected $version;
	protected $consecutive;
	protected $detail_erros;
	protected $wrong_rows;
	protected $success_rows;
	protected $file_status;
	protected $archivo;
	protected $totalRegistros;

	protected function countLine($filePath){
		$handle = null;
		if(!($handle = fopen($filePath, 'r'))) throw new Exception("Error al abrir el archivo. countline");
		$lineCount = 0;
		while (!feof($handle)) {
			fgets($handle);
			$lineCount++;
		}
		$this->totalRegistros = $lineCount - 1;
		fclose($handle);
	}

	function validateFirstRow(&$isValidRow, &$detail_erros, $firstRow) {
		//campo 1
		if(isset($firstRow[0]) || is_numeric($firstRow[0] )){
			$exists = EntidadesSectorSalud::where('cod_habilitacion', $firstRow[0])->first();
			if(!$exists){
				$isValidRow = false;
				array_push($detail_erros, [1, 0, 1, "NO existe un  código de habilitación para la entidad"]);
			}
			
		}else{
			$isValidRow = false;
			array_push($detail_erros, [1, 0, 1, "Debe ser un valor numérico no nulo"]);
		}

		//Log::info("termino validacion 1");

		if(isset($firstRow[1])){
			if(preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])$/", $firstRow[1])){
				$date = explode('-', $firstRow[1]);
				if(!checkdate($date[1], 01, $date[0])){
					$isValidRow = false;
					array_push($detail_erros, [1, 0, 1, "El campo debe corresponder a un fecha válida."]);
				}
			}
			else{
				$isValidRow = false;
				array_push($detail_erros, [1, 0, 2, "El campo debe tener el formato AAAA-MM"]);
			}
		}else{
			$isValidRow = false;
			array_push($detail_erros, [1, 0, 2, "El campo no debe ser nulo"]);
			
		}

		if(isset($firstRow[2])) {
			if(preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $firstRow[2])){
				$date = explode('-', $firstRow[2]);
				if(!checkdate($date[1], $date[2], $date[0])){
					$isValidRow = false;
					array_push($detail_erros, [1, 0, 3, "El campo debe corresponder a un fecha válida."]);
				}
			}
			else
			{
				$isValidRow = false;
				array_push($detail_erros, [1, 0, 3, "El campo debe tener el formato AAAA-MM-DD"]);
			}
		}else{
			$isValidRow = false;
			array_push($detail_erros, [1, 0, 3, "El campo no debe ser nulo"]);
		}

		if(isset($firstRow[3]) ){
			if(preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $firstRow[3]))
			{
				$date = explode('-', $firstRow[3]);
				if(!checkdate($date[2], $date[1], $date[0]))
				{
					$isValidRow = false;
					array_push($detail_erros, [1, 0, 3, "El campo debe corresponder a un fecha válida."]);
				}
			}
			else
			{
				$isValidRow = false;
				array_push($detail_erros, [1, 0, 4, "El campo debe tener el formato AAAA-MM-DD"]);
			}
		}else{
			$isValidRow = false;
			array_push($detail_erros, [1, 0, 4, "El campo no debe ser nulo"]);
		}

		if($isValidRow){
			if (strtotime($firstRow[2]) > strtotime($firstRow[3]) ){
				$isValidRow = false;
				array_push($detail_erros, [1, 0, '3 y 4', "El periodo incial debe ser menor que el periodo final"]);
			}
		}
		

		if(!isset($firstRow[4]) || !is_numeric($firstRow[4])){
			$isValidRow = false;
			array_push($detail_erros, [1, 0, 5, "Debe ser un valor numérico no nulo"]);
		}elseif ($this->totalRegistros != intval($firstRow[4])) {
			$isValidRow = false;
			array_push($detail_erros, [1, 0, 5, "El valor no coincide con el número de registros del archivo actual: No. registros encontrados = ".$this->totalRegistros." - valor del campo = ".intval($firstRow[4])]);
		}



	}

    function validateEntitySection(&$isValidRow, &$detail_erros, $lineCount, $lineCountWF, $entitySection) {
    	
    	//validacion campo 1
    	if(isset($entitySection[0]) && preg_match('/^\d{12}$/', $entitySection[0])){
			$exists = EntidadesSectorSalud::where('cod_habilitacion', $entitySection[0])->first();
			if(!$exists){
				$isValidRow = false;
				array_push($detail_erros, [$lineCount, $lineCountWF, 1, "El valor del campo no corresponde a un código de habilitación de entidad registrado"]);
			}
		}else{
			$isValidRow = false;
			array_push($detail_erros, [$lineCount, $lineCountWF, 1, "El campo debe ser valor numérico de 12 dígitos"]);
		}


		//validacion campo 2
    	if(isset($entitySection[1])){
    		if(strlen($entitySection[1]) == 1){
    			$tipo = TipoEapb::where('id_tipo_ent',$entitySection[1])->first();
    			if(!$tipo){
    				$isValidRow = false;
					array_push($detail_erros, [$lineCount, $lineCountWF, 2, "El  valor del campo no corresponde a un código de tipo entidad"]);
    			}
    		}else{
    			$isValidRow = false;
				array_push($detail_erros, [$lineCount, $lineCountWF, 2, "El campo debe terner un logitud igual a 1"]);
    		}
		}else{
			$isValidRow = false;
			array_push($detail_erros, [$lineCount, $lineCountWF, 2, "El campo no debe ser nulo"]);
		}


		//validacion campo 3
    	if(isset($entitySection[2])){
    		if(strlen($entitySection[2]) == 2){
    			
    			$tipo_ident = TipoIdentEapb::where('id_tipo_ident',$entitySection[2])->first();
    			if(!$tipo_ident){
    				$isValidRow = false;
					array_push($detail_erros, [$lineCount, $lineCountWF, 3, "El valor del campo no corresponde a un código de tipo identificacion entidad"]);
    			}
    		}else{
    			$isValidRow = false;
				array_push($detail_erros, [$lineCount, $lineCountWF, 3, "El campo debe terner un logitud igual a 1"]);
    		}
		}else{
			$isValidRow = false;
			array_push($detail_erros, [$lineCount, $lineCountWF, 3, "El campo no debe ser nulo"]);
		}

		//validacion campo 4
    	if(isset($entitySection[3])){
    		if(preg_match('/^\d{12}$/', $entitySection[3])){
    			$tipo = Eapb::where('num_identificacion', ltrim($entitySection[3],'0'))->first();
    			if(!$tipo){
    				$isValidRow = false;
					array_push($detail_erros, [$lineCount, $lineCountWF, 4, "El  valor del campo no corresponde a un número de identificación de entidad registrado"]);
    			}
    		}else{
    			$isValidRow = false;
				array_push($detail_erros, [$lineCount, $lineCountWF, 4, "El campo debe ser un valor numérico igual de 12 dígitos"]);
    		}
		}else{
			$isValidRow = false;
			array_push($detail_erros, [$lineCount, $lineCountWF, 4, "El campo no debe ser nulo"]);
		}

		//validacion campo 5
    	if(isset($entitySection[4])){
    		if(strlen($entitySection[4]) == 6){
    			$tipo = Eapb::where('cod_eapb',$entitySection[4])->first();
    			if(!$tipo){
    				$isValidRow = false;
					array_push($detail_erros, [$lineCount, $lineCountWF, 5, "El  valor del campo no corresponde a un código de habilitación válido"]);
    			}
    		}else{
    			$isValidRow = false;
				array_push($detail_erros, [$lineCount, $lineCountWF, 5, "El campo debe terner un logitud igual a 6"]);
    		}
		}else{
			$isValidRow = false;
			array_push($detail_erros, [$lineCount, $lineCountWF, 5, "El campo no debe ser nulo"]);
		}

		//validacion campo 6
    	if(isset($entitySection[5]) ) {
    		if(strlen($entitySection[5]) > 100 || $entitySection[5] != "" ){
    			$isValidRow = false;
				array_push($detail_erros, [$lineCount, $lineCountWF, 6, "El campo debe terner un logitud igual a 100 y no debe ser vacio."]);
    		}
		}else{
			$isValidRow = false;
			array_push($detail_erros, [$lineCount, $lineCountWF, 6, "El campo no debe ser nulo"]);
		}

    }


    function validateUserSection(&$isValidRow, &$detail_erros, $lineCount, $lineCountWF, $userSection){

    	//validación campo 7
    	if(isset($userSection[6])){
    		if(strlen($userSection[6]) != 12){
    			$isValidRow = false;
				array_push($detail_erros, [$lineCount, $lineCountWF, 7, "El campo debe terner un logitud igual a 12"]);
    		}
		}else{
			$isValidRow = false;
			array_push($detail_erros, [$lineCount, $lineCountWF, 7, "El campo no debe ser nulo"]);
		}

		//validación campo 8
    	if(isset($userSection[7])) {
    		if(strlen($userSection[7]) == 2){
    			$tipo_ident = TipoIdentificacionUser::where('id_tipo_ident', $userSection[7])->first();
    			if(!$tipo_ident){
    				$isValidRow = false;
					array_push($detail_erros, [$lineCount, $lineCountWF, 8, "tipo de identificación no valido"]);
    			}
    			
    		}else{
    			$isValidRow = false;
				array_push($detail_erros, [$lineCount, $lineCountWF, 8, "El campo debe terner un logitud igual a 2"]);
    		}
		}else{
			$isValidRow = false;
			array_push($detail_erros, [$lineCount, $lineCountWF, 8, "El campo no debe ser nulo"]);
		}

		//validación campo 9
    	if(isset($userSection[8])) {
    		if(strlen($userSection[8]) > 12 || !is_numeric($userSection[8])) {
    			$isValidRow = false;
				array_push($detail_erros, [$lineCount, $lineCountWF, 9, "El campo debe un numerico con una logitud igual o menor a 12 dígitos."]);
    		}
		}else{
			$isValidRow = false;
			array_push($detail_erros, [$lineCount, $lineCountWF, 9, "El campo no debe ser nulo"]);
		}
		
		//validación campo 10
    	if(isset($userSection[9])) {
    		if(strlen($userSection[9]) > 30 || $userSection[9] == '' ){
    			$isValidRow = false;
				array_push($detail_erros, [$lineCount, $lineCountWF, 10, "El campo no de be ser vacio y  debe terner un logitud menor o igual a 30 caracteres."]);
    		}
		}else{
			$isValidRow = false;
			array_push($detail_erros, [$lineCount, $lineCountWF, 10, "El campo no debe ser nulo"]);
		}

		//validación campo 11
    	if(isset($userSection[10])) {
    		if(strlen($userSection[10]) > 30 ){
    			$isValidRow = false;
				array_push($detail_erros, [$lineCount, $lineCountWF, 11, "El campo debe terner un logitud menor a 30 caracteres."]);
    		}
		}else{
			$isValidRow = false;
			array_push($detail_erros, [$lineCount, $lineCountWF, 11, "El campo no debe ser nulo"]);
		}

		//validación campo 12
    	if(isset($userSection[11]) ) {
    		if(strlen($userSection[11]) > 30 || $userSection[11] == '' ){
    			$isValidRow = false;
				array_push($detail_erros, [$lineCount, $lineCountWF, 12, "El campo debe terner un logitud menor o igual a 30 caracteres."]);
    		}
		}else{
			$isValidRow = false;
			array_push($detail_erros, [$lineCount, $lineCountWF, 12, "El campo no debe ser nulo"]);
		}

		//validación campo 13
    	if(isset($userSection[12])) {
    		if(strlen($userSection[12]) > 30 ){
    			$isValidRow = false;
				array_push($detail_erros, [$lineCount, $lineCountWF, 13, "El campo debe terner un logitud menor o igual a 30 caracteres."]);
    		}
		}else{
			$isValidRow = false;
			array_push($detail_erros, [$lineCount, $lineCountWF, 13, "El campo no debe ser nulo"]);
		}

		//validación campo 14
    	if(isset($userSection[13])) {
    		if(preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $userSection[13])){
    			$date = explode('-', $userSection[13]);
				if(!checkdate($date[2], $date[1], $date[0]))
				{
					$isValidRow = false;
					array_push($detail_erros, [$lineCount, $lineCountWF, 14, "El campo debe corresponder a un fecha válida."]);
				}	
    		}
    		else{
    			$isValidRow = false;
				array_push($detail_erros, [$lineCount, $lineCountWF, 14, "El campo debe terner el formato AAAA-MM-DD"]);
    		}
		}else{
			$isValidRow = false;
			array_push($detail_erros, [$lineCount, $lineCountWF, 14, "El campo no debe ser nulo"]);
		}

		//validación campo 15
    	if(isset($userSection[14])) {
    		if(strlen($userSection[14]) != 1 ){
    			$isValidRow = false;
				array_push($detail_erros, [$lineCount, $lineCountWF, 15, "El campo debe terner un logitud igual a 1 caracteres."]);
    		}else{
    			$exists =GenerosUser::where('id_genero',$userSection[14])->first();
    			if(!$exists){
    				$isValidRow = false;
					array_push($detail_erros, [$lineCount, $lineCountWF, 15, "El valor del campo no correponde a un gérenero definido."]);
    			}
    		}
		}else{
			$isValidRow = false;
			array_push($detail_erros, [$lineCount, $lineCountWF, 15, "El campo no debe ser nulo"]);
		}


    }
    
    function createZip($patchFolder,$patchStorageZip){
	    // Get real path for our folder
	    $rootPath = realpath($patchFolder);
	    
	    // Initialize archive object
	    $zip = new \ZipArchive();
	    $zip->open($patchStorageZip, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
	    
	    // Create recursive directory iterator
	    /** @var SplFileInfo[] $files */
	    $files = new \RecursiveIteratorIterator(
	        new \RecursiveDirectoryIterator($rootPath),
	        \RecursiveIteratorIterator::LEAVES_ONLY
	    );
	    
	    foreach ($files as $name => $file)
	    {
	        // Skip directories (they would be added automatically)
	        if (!$file->isDir())
	        {
	            // Get real and relative path for current file
	            $filePath = $file->getRealPath();
	            $relativePath = substr($filePath, strlen($rootPath) + 1);
	    
	            // Add current file to archive
	            $zip->addFile($filePath, $relativePath);
	        }
	    }
	    
	    // Zip archive will be created only after closing object
	    $zip->close();
	}

	public function dropWhiteSpace(&$array)
	{
		foreach ($array as $key => $field) {
			$array[$key] = trim($field);
		}

	}

	protected function updateStatusFile($lineCount)
	{
		$line = $lineCount;
		//se actualiza el porcentaje
		$register_num = $this->archivo->numero_registros;
		$Porcent =  $this->file_status->porcent;
		$currentPorcent = ( ($lineCount - 1)  / $register_num)*100;

		$dif = $currentPorcent -  $Porcent;
		if ($dif >= 1){
		  $this->file_status->current_line = $line;
		  $this->file_status->porcent = intval($currentPorcent);
		  $this->file_status->save();
		}
		return true;
	}

	protected function generateFiles() 
	{

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
		      fputcsv($detailsFileHandler, $row,',');              
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

}