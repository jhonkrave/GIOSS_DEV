<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVacunacionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('vacunacion', function(Blueprint $table)
		{
			$table->bigInteger('ident_vacunacion_seq', true);
			$table->bigInteger('id_registro');
			$table->bigInteger('fecha_aplicacion');
			$table->smallInteger('tipo_codificacion');
			$table->string('codigo_tipo_vacuna', 40)->nullable();
			$table->smallInteger('numero_dosis')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('vacunacion');
	}

}
