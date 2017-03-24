<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTemaInformacionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tema_informacion', function(Blueprint $table)
		{
			$table->string('id_tema_informacion', 3)->primary('tema_informacion_pkey');
			$table->string('descripcion', 500)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tema_informacion');
	}

}
