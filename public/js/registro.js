$(document).ready(function(){
	//console.log('funfs');
	$('#cod_dept').val(0);
	getMunicipios(0);

	var ut = $("#tipo_usuario").val();
	if (ut == 2) {
			$("#info_entidad").fadeIn();
			
	}else{
		
		$("#info_entidad").fadeOut();
	}

	$("#tipo_usuario").on('change', function () {
		if ($(this).val() == 2) {
			$("#info_entidad").fadeIn();
			
		}else{
			
			$("#info_entidad").fadeOut();
		}
	});

	

	$('#cod_dept').on('change',function(){
		var deptid = $(this).val();
		getMunicipios(deptid);
	});
});

function getMunicipios(deptid){
	$.ajax({
		type: "get",
        data: {'departamento': deptid},
        url: routeGetMunicipios,
        success: function(msg)
        {	
        	$('#cod_muni').empty();
            for (x in msg){
            	$('#cod_muni').append('<option value="'+msg[x].cod_divipola+'">'+msg[x].cod_divipola+' - '+msg[x].nombre+'</option>');
            }
        },
        dataType: "json",
        cache: "false",
        error: function(msg){console.log( msg)},
	});
}

