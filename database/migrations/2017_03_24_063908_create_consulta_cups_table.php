<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateConsultaCupsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('consulta_cups', function(Blueprint $table)
		{
			$table->string('cod_consulta', 16)->primary('consulta_cups_pkey');
			$table->string('descripcion', 200)->nullable();
			$table->string('cod_sis_cups', 4)->nullable();
			$table->string('descrip_sis_cups', 200)->nullable();
			$table->string('cod_grup_cups', 2)->nullable();
			$table->string('desc_grup_cups', 200)->nullable();
			$table->string('ambito_cups', 4)->nullable();
			$table->string('sexo_cups', 4)->nullable();
			$table->string('nivel_atencion', 4)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('consulta_cups');
	}

}
