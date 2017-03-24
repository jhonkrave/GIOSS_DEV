<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateIngresosEgresosHospitalariosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ingresos_egresos_hospitalarios', function(Blueprint $table)
		{
			$table->bigInteger('identificacion_egreso_seq', true);
			$table->bigInteger('id_registro');
			$table->bigInteger('fecha_hora_ingreso');
			$table->bigInteger('fecha_hora_egreso')->nullable();
			$table->string('cod_diagnostico_ingreso', 8);
			$table->string('cod_diagnostico_egreso', 8)->nullable();
			$table->string('cod_diagnostico_egreso_rel1', 8)->nullable();
			$table->string('cod_diagnostico_egreso_rel2', 8)->nullable();
			$table->smallInteger('estado_salida')->nullable();
			$table->string('codigo_diagnostico_muerte', 8)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ingresos_egresos_hospitalarios');
	}

}
