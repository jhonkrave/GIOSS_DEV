<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTipoIdentificacionUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tipo_identificacion_user', function(Blueprint $table)
		{
			$table->string('id_tipo_ident', 2)->primary('tipo_identificacion_user_pkey');
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
		Schema::drop('tipo_identificacion_user');
	}

}
