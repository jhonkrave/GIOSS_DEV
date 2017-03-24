<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProcedimientosQNqTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('procedimientos_q_nq', function(Blueprint $table)
		{
			$table->bigInteger('identificacion_consult_seq', true);
			$table->bigInteger('id_registro');
			$table->bigInteger('fecha_procedimiento');
			$table->smallInteger('tipo_codificacion')->nullable();
			$table->string('cod_procedimiento', 16)->nullable();
			$table->string('cod_diagnostico_principal', 8)->nullable();
			$table->string('cod_diagnostico_rel1', 8)->nullable();
			$table->string('cod_diagnostico_rel2', 8)->nullable();
			$table->char('ambito_procedimiento', 1)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('procedimientos_q_nq');
	}

}
