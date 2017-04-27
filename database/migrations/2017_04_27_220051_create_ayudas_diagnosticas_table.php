<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAyudasDiagnosticasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ayudas_diagnosticas', function(Blueprint $table)
		{
			$table->bigInteger('id_seq', true);
			$table->bigInteger('id_registro');
			$table->string('ambito', 1)->nullable();
			$table->string('tipo_prueba', 2);
			$table->string('tipo_codificacion', 2);
			$table->string('cod_procedimiento', 6);
			$table->bigInteger('fecha_realizacion')->nullable();
			$table->bigInteger('fecha_entrega')->nullable();
			$table->string('resultado', 40)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ayudas_diagnosticas');
	}

}
