<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVacunaHomologoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('vacuna_homologo', function(Blueprint $table)
		{
			$table->string('codigo_tipo_vacuna', 40)->primary('vacuna_homologo_pkey');
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
		Schema::drop('vacuna_homologo');
	}

}
