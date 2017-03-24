<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToProcedimientosQNqTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('procedimientos_q_nq', function(Blueprint $table)
		{
			$table->foreign('id_registro', 'procedimientos_q_nqfk1')->references('id_registro_seq')->on('registro')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('cod_diagnostico_principal', 'procedimientos_q_nqfk2')->references('cod_diagnostico')->on('diagnostico_ciex')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('cod_diagnostico_rel1', 'procedimientos_q_nqfk3')->references('cod_diagnostico')->on('diagnostico_ciex')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('cod_diagnostico_rel2', 'procedimientos_q_nqfk4')->references('cod_diagnostico')->on('diagnostico_ciex')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('ambito_procedimiento', 'procedimientos_q_nqfk5')->references('cod_ambito')->on('ambito')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('procedimientos_q_nq', function(Blueprint $table)
		{
			$table->dropForeign('procedimientos_q_nqfk1');
			$table->dropForeign('procedimientos_q_nqfk2');
			$table->dropForeign('procedimientos_q_nqfk3');
			$table->dropForeign('procedimientos_q_nqfk4');
			$table->dropForeign('procedimientos_q_nqfk5');
		});
	}

}
