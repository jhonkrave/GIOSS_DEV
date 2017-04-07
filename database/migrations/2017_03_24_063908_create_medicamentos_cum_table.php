<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMedicamentosCumTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('medicamentos_cum', function(Blueprint $table)
		{
			$table->string('codigo_medicamento', 40)->primary('medicamentos_cum_pkey');
			$table->string('registro', 200)->nullable();
			$table->string('producto', 200)->nullable();
			$table->string('desrip_comercial_cum', 320)->nullable();
			$table->string('descrip_atc', 320)->nullable();
			$table->string('principio_activo', 320)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('medicamentos_cum');
	}

}
