$(document).ready(function(){
	var count_files = 0;
	var interval = null;
	$('#add_file').on('click', function(){

		$('#alert').fadeOut();
		count_files+=1;

		var html = '<div class="form-group well " id="particular_file_div"> <button type="button" id="close_div_file" class="close" aria-hidden="true">&times;</button> <label for="tipo_file" class="form-control-label">Tipo de archivo No.'+count_files+'</label><select id="tipo_file" name="tipo_file[]"><option value="AAC">Archivo Atencion en Consulta AAC</option> <option value="AEH" >Archivo Egresos Hospitalarios AEH</option><option value="AMS">Archivo Medicamentos Suministrados AMS</option><option value="AVA">Archivo Vacunas Aplicadas AVA</option><option value="APS">Archivo Procedimientos APS</option></select><div><input type="file" name="archivo[]" id="archivo"  accept=".txt"></div></div>';
		$('#files_div').append(html);
	});

	$('#files_div').on('click','#close_div_file', function(){
		$('#alert').fadeOut();
		$(this).parent().fadeOut(function(){
		
			$(this).remove();
		});
		
	});

	$('#btnUpload').on('click', function(){
		$('#alert').fadeOut();
		$('#error_area').empty();
		var validatorNames = validateNameFiles();
		var validatorPeriodo = validatePeriodo();


		if(!validatorNames['isValid'] || !validatorPeriodo.isValid){
			if(!validatorNames['isValid']){
				$('#error_area').append(validatorNames['detalle']);
			}
			
			if( !validatorPeriodo.isValid){
				$('#error_area').append(validatorPeriodo['detalle']);
			}
			
			$('#alert').fadeIn();
		}else{
			uploadFile();
		}

		//
	});
});

function validatePeriodo(){

	var isValid = true;
	var detalle = '<hr><hr><strong>Error en el periodo a reportar</strong><br>';
	var startDt=$('#fecha_ini').val();
	var endDt=$('#fecha_fin').val();
	console.log(startDt);
	if(startDt == "" ){
		 isValid = false;
		detalle += '<p>- Por favor selecciona una fecha inicio de periodo valida.</p>';
	}

	if(endDt == "" ){
		isValid = false;
		 detalle += '<p>- Por favor selecciona una fecha de fin de periodo valida.</p>';
	}

	if( (new Date(startDt).getTime() > new Date(endDt).getTime()))
	{
	    var isValid = false;
		 detalle += '<p>El periodo incial debe ser menor al final</p>';
	}

	return {isValid:isValid, detalle:detalle};
}

function validateNameFiles(){

	var isValid = true;
	var detalle = '<hr><hr><strong>Error en el formato del nombre de un archivo</strong>';

	if($('#files_div #particular_file_div')){
		$('#files_div #particular_file_div').each(function(i, item){
			var type_file = $(this).find('#tipo_file').val();

			var label = $(this).find('label').text();


			var file_path = $(this).find('#archivo').val();

			var file_array_split = file_path.split("\\");
			var file_name_array =  file_array_split[file_array_split.length -1];

			var file_name = file_name_array.split(".")[0];
			var file_ext = file_name_array.split(".")[1];

			

			var modulo  = file_name.substring(0,3);
			var tipo_fuente = file_name.substring(3,6);
			var tema = file_name.substring(6,9);
			var MesRepor = file_name.substring(9,15);
			var fecha_ini = file_name.substring(15,23);
			var fecha_fin = file_name.substring(23,31);
			var tipo_identificaion = file_name.substring(31,34);
			var id_entidad = file_name.substring(34,46);
			var cod_habilitacion = file_name.substring(46,58);

			//validaciones

			var mnj = '';

			if(file_ext |= 'txt'){
				isValid = false;
				mnj += '<p>- La extensión del archivo debe ser txt</p>';
			}

			if(modulo != 'SGD') {
				isValid = false;
				mnj += '<p>- La sección del modulo no correponde al al modulo SGD</p>';
			}

			if(!$.isNumeric(tipo_fuente)){
				isValid = false;
				mnj += '<p>- La sección del tipo de fuente debe ser numérico</p>';
			}

			if(tema != type_file ){
				isValid = false;
				mnj += '<p>- La sección del tipo de archivo no coincide con el tipo seleccionado</p>';
			}

			if(!MesRepor.match(/^(19|20)\d\d(0[1-9]|1[012])$/)){
				isValid = false;
				mnj += '<p>- La sección del del mes reportado no coincide con el formato de fecha YYYYMM</p>';
			}
			
			if(!fecha_ini.match(/^(19|20)\d\d(0[1-9]|1[012])(0[1-9]|[12][0-9]|3[01])$/)){
				isValid = false;
				mnj += '<p>- La sección del inicio de periodo no corresponde al formato de fecha YYYYMMDD</p>';
			}

			if(!fecha_fin.match(/^(19|20)\d\d(0[1-9]|1[012])(0[1-9]|[12][0-9]|3[01])$/)){
				isValid = false;
				mnj += '<p>- La sección del fin de periodo no corresponde al formato de fecha YYYYMMDD</p>';
			}

			if(typeof tipo_identificaion != "string" || tipo_identificaion.length != 3 ){
				isValid = false;
				mnj += '<p>- la seccion del tipo de indentificacion debe ser un caracter de tamaño 3</p>';
			}

			

			// var dateini = fecha_ini.substring(0,4)+'-'+fecha_ini.substring(4,6)+'-'+fecha_ini.substring(6);
			// var datefin = fecha_fin.substring(0,4)+'-'+fecha_fin.substring(4,6)+'-'+fecha_fin.substring(6);
			// var mesr = MesRepor.substring(0,4)+'-'+MesRepor.substring(4,6)+'-01';
			// console.log(dateini);
			// console.log(datefin);
			// console.log(mesr);

			// var startDt=$('#fecha_ini').val();
			// var endDt=$('#fecha_fin').val();

			// if(startDt != dateini)
			// {
			//      isValid = false;
			// 	 mnj += '<p>- El periodo incial debe ser equivalente con el periodo inial del archivo reportado</p>';
			// }

			// if(endDt != datefin)
			// {
			//     isValid = false;
			// 	 mnj += '<p>- El periodo final debe ser equivalente con el periodo final del archivo reportado</p></p>';
			// }

			if(!isValid){
				mnj = '<hr>Error en el archivo <strong>'+ label+'</strong>:<br>'+mnj;
			}

			detalle+=mnj;

		});

	}

		

	return {'isValid': isValid, 'detalle':detalle};

}

function uploadFile() {
    
    var consecutive = Date.now();
    var formData = new FormData($('#cargaArchivos')[0]);
    formData.append('consecutive', consecutive);
    
    $.ajax({
        url: route,
        data: formData,
        type: 'POST',
        dataType: 'json',
        cache: false,
        // parametros necesarios para la carga de archivos
        contentType: false,
        processData: false,
        beforeSend: function() {
           $('#divgif').append(loadGif);
           interval = setInterval(function(){
				consultStatusFiles(consecutive);
			}, 5000);
        },
        error: function (msj) {
            console.log(msj);
        }
        // ... Other options like success and etc
    });
     
}

function consultStatusFiles(consecutive) {


	$.ajax({
        url: status_file_route,
        data: {'consecutive':consecutive},
        type: 'GET',
        dataType: 'json',
        cache: false,
        success : function (msj) {
        	console.log(msj);
            $('#div_file_statuses').empty();
            $('#div_file_statuses').append(' <h3>Estado de archivos</h3>')
            
            var finish = true;
            for (x in msj){
            	if (msj[x].current_status == 'COMPLETED'){
            		finish = true;
            	}else{
            		finish = false;
            	}

            	var html = '<div class="form-group well "> <div class="row"> <label class="col-md-4">Nombre:</label> <label class="col-md-8" style="display: inline-block; width: 300px; overflow: hidden; text-overflow: ellipsis;">'+msj[x].nombre+'0'+msj[x].version+'.txt</label></div> <div class="row"> <label class="col-md-4">Estado:</label> <label class="col-md-8">'+msj[x].current_status+'</label></div>';

            	html += '<div class="row"> <label class="col-md-4">% cargado: </label> <label class="col-md-8">'+msj[x].porcent+'% => '+msj[x].current_line+'/'+msj[x].total_registers+'</label></div>';
            	if(msj[x].current_status == 'COMPLETED')
            	{
            		switch(msj[x].final_status)
            		{
	            		case 'REGULAR':
	            			html+= '<div class="row"> <label class="col-md-4">Cal. Global:</label> <label class="col-md-8" >REGULAR</label></div>';
	            			break;
	            		case 'SUCCESS':
	            			html+= '<div class="row"> <label class="col-md-4">Cal. Global:</label> <label class="col-md-8">EXITOSO</label></div>';
	            			break;
	            		case 'FAILURE':
	            			html+= '<div class="row"> <label class="col-md-4">Cal. Global:</label> <label class="col-md-8">FALLIDO</label></div>';
	            			break;
	            	}

	            	html+= '<div class="row"> <label class="col-md-4">Detalle:</label> <a href="'+msj[x].zipath+'" class="col-md-8">Descargar</a></div>';

            	}
            	
            	html+= ' </div>';
            	$('#div_file_statuses').append(html);
            }

            if(finish){
            	$('#divgif').empty();
            	$('#files_div').empty();
            	clearInterval(interval);
            }

            if (!$('#div_file_statuses').is(':visible')) $('#div_file_statuses').fadeIn();
            
        },
        error: function (msj) {
            console.log(msj);
        }
        // ... Other options like success and etc
    });
}


