<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDiagnosticoCiexTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('diagnostico_ciex', function(Blueprint $table)
		{
			$table->string('cod_diagnostico', 8)->primary('diagnostico_ciex_pkey');
			$table->string('descripcion', 300)->nullable();
			$table->string('cod_grupo_dx', 10)->nullable();
			$table->string('cod_capitulo_dx', 10)->nullable();
			$table->string('cod_sub_grupo_dx', 10)->nullable();
			$table->string('cod_sexo', 10)->nullable();
			$table->string('edad_min', 10)->nullable();
			$table->string('edad_max', 10)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('diagnostico_ciex');
	}

}
