<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToInconsistenciasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('inconsistencias', function(Blueprint $table)
		{
			$table->foreign('cod_grupo', 'inconsistenciasfk1')->references('cod_grupo')->on('grupo_inconsistencias')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('cod_tipo', 'inconsistenciasfk2')->references('cod_tipo')->on('tipo_inconsistencias')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('inconsistencias', function(Blueprint $table)
		{
			$table->dropForeign('inconsistenciasfk1');
			$table->dropForeign('inconsistenciasfk2');
		});
	}

}
