<?php

namespace App\Traits;

use App\Models\EntidadesSectorSalud;
use App\Models\TipoEntidad;
use App\Models\TipoIdentificacionEntidad;
use App\Models\TipoIdentificacionUser;

trait ToolsForFilesController {

	function validateFirstRow($firstRow) {

		if($firstRow[0] !== null || is_numeric($firstRow[0] )){
			$exists = EntidadesSectorSalud::where('cod_habilitacion', $firstRow[0])->first();
			if(!$exists){
				throw new \Exception("Error en la línea 1 No existe una entidad con el código de habilitación ".$firstRow[0]);
			}
			
		}else{
			throw new \Exception("Error en la línea 1. El campo 1 debe ser un valor númerico no nulo");
		}

		if($firstRow[1] !== null){
			if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])$/", $firstRow[1])){
				throw new \Exception("Error en la línea 1. El campo 2 debe tener el formato YYYY-MM");	
			}
		}else{
			throw new \Exception("Error en la línea 1. El campo 2 no debe ser  nulo");
		}

		if($firstRow[2] !== null){
			if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $firstRow[2])){
				throw new \Exception("Error en la línea 1. El campo 3 debe tener el formato YYYY-MM-DD");	
			}
		}else{
			throw new \Exception("Error en la línea 1. El campo 3 no debe ser nulo");
		}

		if($firstRow[3] !== null){
			if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $firstRow[2])){
				throw new \Exception("Error en la línea 1. El campo 4 debe tener el formato YYYY-MM-DD");	
			}
		}else{
			throw new \Exception("Error en la línea 1. El campo 4 no debe ser nulo");
		}

		if (strtotime($firstRow[2] > strtotime($firstRow[3]))){
			throw new \Exception("Error en la línea 1. El periodo incial (campo 3) debe ser menor que el final (campo 4)");
		}

		if($firstRow[4] === null || !is_numeric($firstRow[4])){
			throw new \Exception("Error en la línea 1. El campo 5 debe ser un valor númerico no nulo");
		}

		return true;

	}

    function validateEntitySection(&$isValidRow, &$detail_erros, $lineCount, $lineCountWF, $entitySection) {

    	$initField = 0;
    	//validacion campo 1
    	if(isset($entitySection[0]) && preg_match('/^\d{12}$/', $entitySection[0])){
			$exists = EntidadesSectorSalud::where('cod_habilitacion', $entitySection[0])->first();
			if(!$exists){
				$isValidRow = false;
				array_push($detail_erros, [$lineCount, $lineCountWF,$initField + 1, "El valor del campo no corresponde a un código de habilitación de entidad registrado"]);
			}
		}else{
			$isValidRow = false;
			array_push($detail_erros, [$lineCount, $lineCountWF,$initField + 1, "El campo debe ser valor numérico de 12 dígitos"]);
		}


		//validacion campo 2
    	if(isset($entitySection[1])){
    		if(strlen($entitySection[1] == 1)){
    			$tipo = TipoEntidad::where('tipo_entidad',$entitySection[1])->first();
    			if(!$tipo){
    				$isValidRow = false;
					array_push($detail_erros, [$lineCount, $lineCountWF,$initField + 2, "El  valor del campo no corresponde a un código de tipo entidad"]);
    			}
    		}else{
    			$isValidRow = false;
				array_push($detail_erros, [$lineCount, $lineCountWF,$initField + 2, "El campo debe terner un logitud igual a 1"]);
    		}
		}else{
			$isValidRow = false;
			array_push($detail_erros, [$lineCount, $lineCountWF,$initField + 2, "El campo no debe ser nulo"]);
		}


		//validacion campo 3
    	if(isset($entitySection[2])){
    		if(strlen($entitySection[2] == 2)){
    			$tipo_ident = TipoIdentificacionEntidad::where('id_tipo_ident',$entitySection[2])->first();
    			if(!$tipo_ident){
    				$isValidRow = false;
					array_push($detail_erros, [$lineCount, $lineCountWF,$initField + 3, "El valor del campo no corresponde a un código de tipo identificacion entidad"]);
    			}
    		}else{
    			$isValidRow = false;
				array_push($detail_erros, [$lineCount, $lineCountWF,$initField + 3, "El campo debe terner un logitud igual a 1"]);
    		}
		}else{
			$isValidRow = false;
			array_push($detail_erros, [$lineCount, $lineCountWF,$initField + 3, "El campo no debe ser nulo"]);
		}

		//validacion campo 4
    	if(isset($entitySection[3])){
    		if(preg_match('/^\d{12}$/', $entitySection[3])){
    			$tipo = EntidadesSectorSalud::where('num_identificacion',$entitySection[3])->first();
    			if(!$tipo){
    				$isValidRow = false;
					array_push($detail_erros, [$lineCount, $lineCountWF,$initField + 4, "El  valor del campo no corresponde a un número de identificación de entidad registrado"]);
    			}
    		}else{
    			$isValidRow = false;
				array_push($detail_erros, [$lineCount, $lineCountWF,$initField + 4, "El campo debe ser un valor numérico igual de 12 dígitos"]);
    		}
		}else{
			$isValidRow = false;
			array_push($detail_erros, [$lineCount, $lineCountWF,$initField + 4, "El campo no debe ser nulo"]);
		}

		//validacion campo 5
    	if(isset($entitySection[4])){
    		if(strlen($entitySection[4] == 6)){
    			$tipo = EntidadesSectorSalud::where('cod_habilitacion',$entitySection[4])->first();
    			if(!$tipo){
    				$isValidRow = false;
					array_push($detail_erros, [$lineCount, $lineCountWF,$initField + 5, "El  valor del campo no corresponde a un código de habilitación válido"]);
    			}
    		}else{
    			$isValidRow = false;
				array_push($detail_erros, [$lineCount, $lineCountWF,$initField + 5, "El campo debe terner un logitud igual a 6"]);
    		}
		}else{
			$isValidRow = false;
			array_push($detail_erros, [$lineCount, $lineCountWF,$initField + 5, "El campo no debe ser nulo"]);
		}

		//validacion campo 6
    	if(isset($entitySection[5]) ) {
    		if(strlen($entitySection[5] > 100)){
    			$isValidRow = false;
				array_push($detail_erros, [$lineCount, $lineCountWF,$initField + 6, "El campo debe terner un logitud igual a 100"]);
    		}
		}else{
			$isValidRow = false;
			array_push($detail_erros, [$lineCount, $lineCountWF,$initField + 6, "El campo no debe ser nulo"]);
		}

    }


    function validateUserSection(&$isValidRow, &$detail_erros, $lineCount, $lineCountWF, $userSection){
    	$initField = 6;
    	//validación campo 7
    	if($userSection[0] !== null){
    		if(strlen($userSection[0] != 12)){
    			$isValidRow = false;
				array_push($detail_erros, [$lineCount, $lineCountWF,$initField + 1, "El campo debe terner un logitud igual a 12"]);
    		}
		}else{
			$isValidRow = false;
			array_push($detail_erros, [$lineCount, $lineCountWF,$initField + 1, "El campo no debe ser nulo"]);
		}

		//validación campo 8
    	if($userSection[1] !== null){
    		if(strlen($userSection[1] == 2)){
    			$tipo_ident = TipoIdentificacionUser::where('id_tipo_ident', $userSection[1])->first();
    			if(!$tipo_ident){
    				$isValidRow = false;
					array_push($detail_erros, [$lineCount, $lineCountWF,$initField + 2, "tipo de identificación no valido"]);
    			}
    			
    		}else{
    			$isValidRow = false;
				array_push($detail_erros, [$lineCount, $lineCountWF,$initField + 2, "El campo debe terner un logitud igual a 2"]);
    		}
		}else{
			$isValidRow = false;
			array_push($detail_erros, [$lineCount, $lineCountWF,$initField + 2, "El campo no debe ser nulo"]);
		}

		//validación campo 9
    	if($userSection[0] !== null){
    		if(strlen($userSection[0] != 12)){
    			$isValidRow = false;
				array_push($detail_erros, [$lineCount, $lineCountWF,$initField + 1, "El campo debe terner un logitud igual a 12"]);
    		}
		}else{
			$isValidRow = false;
			array_push($detail_erros, [$lineCount, $lineCountWF,$initField + 1, "El campo no debe ser nulo"]);
		}

		//validación campo 10
    	if($userSection[0] !== null){
    		if(strlen($userSection[0] != 12)){
    			$isValidRow = false;
				array_push($detail_erros, [$lineCount, $lineCountWF,$initField + 1, "El campo debe terner un logitud igual a 12"]);
    		}
		}else{
			$isValidRow = false;
			array_push($detail_erros, [$lineCount, $lineCountWF,$initField + 1, "El campo no debe ser nulo"]);
		}

		//validación campo 11
    	if($userSection[0] !== null){
    		if(strlen($userSection[0] != 12)){
    			$isValidRow = false;
				array_push($detail_erros, [$lineCount, $lineCountWF,$initField + 1, "El campo debe terner un logitud igual a 12"]);
    		}
		}else{
			$isValidRow = false;
			array_push($detail_erros, [$lineCount, $lineCountWF,$initField + 1, "El campo no debe ser nulo"]);
		}

		//validación campo 12
    	if($userSection[0] !== null){
    		if(strlen($userSection[0] != 12)){
    			$isValidRow = false;
				array_push($detail_erros, [$lineCount, $lineCountWF,$initField + 1, "El campo debe terner un logitud igual a 12"]);
    		}
		}else{
			$isValidRow = false;
			array_push($detail_erros, [$lineCount, $lineCountWF,$initField + 1, "El campo no debe ser nulo"]);
		}

		//validación campo 13
    	if($userSection[0] !== null){
    		if(strlen($userSection[0] != 12)){
    			$isValidRow = false;
				array_push($detail_erros, [$lineCount, $lineCountWF,$initField + 1, "El campo debe terner un logitud igual a 12"]);
    		}
		}else{
			$isValidRow = false;
			array_push($detail_erros, [$lineCount, $lineCountWF,$initField + 1, "El campo no debe ser nulo"]);
		}

		//validación campo 14
    	if($userSection[0] !== null){
    		if(strlen($userSection[0] != 12)){
    			$isValidRow = false;
				array_push($detail_erros, [$lineCount, $lineCountWF,$initField + 1, "El campo debe terner un logitud igual a 12"]);
    		}
		}else{
			$isValidRow = false;
			array_push($detail_erros, [$lineCount, $lineCountWF,$initField + 1, "El campo no debe ser nulo"]);
		}

		//validación campo 15
    	if($userSection[0] !== null){
    		if(strlen($userSection[0] != 12)){
    			$isValidRow = false;
				array_push($detail_erros, [$lineCount, $lineCountWF,$initField + 1, "El campo debe terner un logitud igual a 12"]);
    		}
		}else{
			$isValidRow = false;
			array_push($detail_erros, [$lineCount, $lineCountWF,$initField + 1, "El campo no debe ser nulo"]);
		}

		dd($isValidRow);

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



}