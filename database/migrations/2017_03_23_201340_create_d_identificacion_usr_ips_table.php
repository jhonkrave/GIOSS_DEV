<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDIdentificacionUsrIpsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('d_identificacion_usr_ips', function(Blueprint $table)
		{
			$table->bigInteger('identificacion_seq', true);
			$table->dateTime('fecha_ingreso')->nullable()->default('now()');
			$table->string('tipo_identificacion', 2)->nullable();
			$table->string('numero_identenficacion', 20)->nullable();
			$table->date('fecha_nacimiento')->nullable();
			$table->string('sexo', 1)->nullable();
			$table->string('primer_nombre', 50)->nullable();
			$table->string('segundo_nombre', 50)->nullable();
			$table->string('primer_apellido', 50)->nullable();
			$table->string('segundo_apellido', 50)->nullable();
			$table->string('regimen', 50)->nullable();
			$table->bigInteger('cod_prestador_servicios_salud')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('d_identificacion_usr_ips');
	}

}
