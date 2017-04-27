<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAyudasDiagnosticasPruebasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ayudas_diagnosticas_pruebas', function(Blueprint $table)
		{
			$table->string('id_prueba', 2)->primary('ayudas_diagnosticas_pruebas_pkey');
			$table->string('descripcion', 100)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ayudas_diagnosticas_pruebas');
	}

}
