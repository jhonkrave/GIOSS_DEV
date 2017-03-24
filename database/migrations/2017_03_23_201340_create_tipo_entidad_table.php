<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTipoEntidadTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tipo_entidad', function(Blueprint $table)
		{
			$table->integer('codigo_tipo_entidad')->primary('tipo_entidad_pkey');
			$table->string('descripcion', 320);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tipo_entidad');
	}

}
