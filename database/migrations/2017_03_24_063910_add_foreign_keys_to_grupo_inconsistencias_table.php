<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToGrupoInconsistenciasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('grupo_inconsistencias', function(Blueprint $table)
		{
			$table->foreign('cod_tipo', 'mgrupo_inconsistenciasfk1')->references('cod_tipo')->on('tipo_inconsistencias')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('grupo_inconsistencias', function(Blueprint $table)
		{
			$table->dropForeign('mgrupo_inconsistenciasfk1');
		});
	}

}
