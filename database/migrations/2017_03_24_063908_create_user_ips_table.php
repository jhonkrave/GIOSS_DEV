<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserIpsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_ips', function(Blueprint $table)
		{
			$table->bigInteger('id_user', true);
			$table->dateTime('fecha_ingreso')->nullable()->default('now()');
			$table->string('tipo_identificacion', 2)->nullable();
			$table->string('num_identificacion', 20);
			$table->date('fecha_nacimiento')->nullable();
			$table->string('sexo', 2)->nullable();
			$table->string('primer_nombre', 50)->nullable();
			$table->string('segundo_nombre', 50)->nullable();
			$table->string('primer_apellido', 50)->nullable();
			$table->string('segundo_apellido', 50)->nullable();
			$table->string('num_historia_clinica', 12)->nullable();
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_ips');
	}

}
