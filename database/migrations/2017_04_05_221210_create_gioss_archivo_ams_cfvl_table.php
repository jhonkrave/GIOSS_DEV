<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGiossArchivoAmsCfvlTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('gioss_archivo_ams_cfvl', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('fecha_periodo_inicio');
			$table->bigInteger('fecha_periodo_fin');
			$table->string('nombre_archivo', 320);
			$table->bigInteger('numero_registro');
			$table->text('contenido_registro_validado');
			$table->bigInteger('fecha_hora_validacion');
			$table->unique(['fecha_periodo_inicio','fecha_periodo_fin','nombre_archivo','numero_registro'], 'gioss_archivo_ams_cfvl_uniquekey');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('gioss_archivo_ams_cfvl');
	}

}
