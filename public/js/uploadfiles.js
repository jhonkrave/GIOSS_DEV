$(document).ready(function(){
	var count_files = 0;
	$('#add_file').on('click', function(){

		$('#alert').fadeOut();
		count_files+=1;

		var html = '<div class="form-group well col-md-6" id="particular_file_div"> <button type="button" id="close_div_file" class="close" aria-hidden="true">&times;</button> <label for="tipo_file" class="form-control-label">Tipo de archivo No.'+count_files+'</label><select id="tipo_file" name="tipo_file[]"><option value="AAC">Archivo Atencion en Consulta AAC</option> <option value="AEH" >Archivo Egresos Hospitalarios AEH</option value="AMS"><option>Archivo Medicamentos Suministrados AMS</option><option value="AVA">Archivo Vacunas Aplicadas AVA</option><option value="APS">Archivo Procedimientos APS</option></select><div><input type="file" name="archivo[]" id="archivo"  accept=".txt"></div></div>';
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
    
    
    var formData = new FormData($('#cargaArchivos')[0]);
    
    
    

    
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
           
        },
        success : function (msj) {
            $('#error_area').empty();

            if (msj.error){

            	$('#error_area').append(msj.error);
            	$('#error_area').append(msj.urlzip);
            	
            }else if(msj.warning){
            	$('#error_area').append(msj.warning);
            	$('#error_area').append(msj.urlzip);
            }else if(msj.success){
            	$('#error_area').append(msj.success);
            	$('#error_area').append(msj.urlzip);
            	
            }
            $('#alert').fadeIn();
            console.log(msj);
        },
        error: function (msj) {
            console.log(msj);
        }
        // ... Other options like success and etc
    });
     
}