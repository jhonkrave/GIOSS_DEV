$(document).ready(function(){
	$('#add_file').on('click', function(){

		$('#alert').fadeOut();

		var html = '<div class="form-group well col-md-6"> <button type="button" id="close_div_file" class="close" aria-hidden="true">&times;</button> <label for="tipo_file" class="form-control-label">Tipo de archivo</label><select id="tipo_file" name="tipo_file"><option class="ACC">Archivo Atencion en Consulta</option> <option>Archivo Egresos Hospitalarios AEH</option><option>Archivo Medicamentos Suministrados AMS</option><option>Archivo Vacunas Aplicadas AVA</option><option>Archivo Procedimientos APS</option></select><div><input type="file" name="archivo" id="archivo"></div></div>';
		$('#files_div').append(html);
	});

	$('#files_div').on('click','#close_div_file', function(){
		$(this).parent().remove();
	});

	$('#btnUpload').on('click', function(){
		$('#alert').fadeIn();
	})
});