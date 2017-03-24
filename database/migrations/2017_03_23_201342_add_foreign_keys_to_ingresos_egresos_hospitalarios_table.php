<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToIngresosEgresosHospitalariosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('ingresos_egresos_hospitalarios', function(Blueprint $table)
		{
			$table->foreign('id_registro', 'consultafk1')->references('id_registro_seq')->on('registro')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('cod_diagnostico_ingreso', 'consultafk2')->references('cod_diagnostico')->on('diagnostico_ciex')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('cod_diagnostico_egreso', 'consultafk3')->references('cod_diagnostico')->on('diagnostico_ciex')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('cod_diagnostico_egreso_rel1', 'consultafk4')->references('cod_diagnostico')->on('diagnostico_ciex')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('cod_diagnostico_egreso_rel2', 'consultafk5')->references('cod_diagnostico')->on('diagnostico_ciex')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('"codigo_diagnóstico_muerte"', 'consultafk6')->references('cod_diagnostico')->on('diagnostico_ciex')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('ingresos_egresos_hospitalarios', function(Blueprint $table)
		{
			$table->dropForeign('consultafk1');
			$table->dropForeign('consultafk2');
			$table->dropForeign('consultafk3');
			$table->dropForeign('consultafk4');
			$table->dropForeign('consultafk5');
			$table->dropForeign('consultafk6');
		});
	}

}
