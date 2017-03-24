<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateResultadosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('resultados', function(Blueprint $table)
		{
			$table->bigInteger('ident_resultados_seq', true);
			$table->bigInteger('fecha_toma')->nullable();
			$table->bigInteger('fecha_resultado')->nullable();
			$table->string('cups_cod', 20)->nullable();
			$table->decimal('valor_resultado', 10, 0)->nullable();
			$table->bigInteger('referencia_usuario')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('resultados');
	}

}
