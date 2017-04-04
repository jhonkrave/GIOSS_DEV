<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRegistroTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('registro', function(Blueprint $table)
		{
			$table->bigInteger('id_registro_seq', true);
			$table->integer('id_archivo');
			$table->integer('id_user');
			$table->integer('id_eapb');
			$table->unique(['id_archivo','id_user', 'id_eapb']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('registro');
	}

}
