$(document).ready(function(){
	$("#tipo_usuario").on('change', function () {
		if ($(this).val() == 2) {
			$("#info_entidad").fadeIn();
		};
	});
});

