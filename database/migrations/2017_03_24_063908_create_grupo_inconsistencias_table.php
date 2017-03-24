<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGrupoInconsistenciasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('grupo_inconsistencias', function(Blueprint $table)
		{
			$table->string('cod_grupo', 2)->primary('grupo_inconsistencias_pkey');
			$table->string('descripcion', 100)->nullable();
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
		Schema::drop('grupo_inconsistencias');
	}

}
