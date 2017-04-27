<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePesoTallaTensionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('peso_talla_tension', function(Blueprint $table)
		{
			$table->bigInteger('id_seq', true);
			$table->bigInteger('id_registro');
			$table->string('ambito', 1)->nullable();
			$table->bigInteger('fecha_med_peso');
			$table->float('valor_peso', 10, 0);
			$table->bigInteger('fecha_med_talla')->nullable();
			$table->integer('valor_talla');
			$table->bigInteger('fecha_med_tension')->nullable();
			$table->integer('valor_tension_sistolica');
			$table->integer('valor_tension_diastolica');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('peso_talla_tension');
	}

}
