<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInconsistenciasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('inconsistencias', function(Blueprint $table)
		{
			$table->string('cod_inconsistencia', 3)->primary('inconsistencias_pkey');
			$table->string('descripcion', 500)->nullable();
			$table->string('cod_grupo', 2)->nullable();
			$table->string('cod_tipo', 2)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('inconsistencias');
	}

}
