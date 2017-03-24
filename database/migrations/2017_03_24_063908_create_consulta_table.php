<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateConsultaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('consulta', function(Blueprint $table)
		{
			$table->bigInteger('identificacion_consult_seq', true);
			$table->bigInteger('id_registro');
			$table->bigInteger('fecha_consulta');
			$table->char('ambito_consulta', 1)->nullable();
			$table->smallInteger('tipo_codificacion');
			$table->string('cod_consulta', 16);
			$table->smallInteger('cod_consulta_esp')->nullable();
			$table->string('cod_diagnostico_principal', 8)->nullable();
			$table->string('cod_diagnostico_rel1', 8)->nullable();
			$table->string('cod_diagnostico_rel2', 8)->nullable();
			$table->smallInteger('tipo_diagnostico_principal')->nullable();
			$table->smallInteger('finalidad_consulta')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('consulta');
	}

}
