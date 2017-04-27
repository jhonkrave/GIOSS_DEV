<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToAyudasDiagnosticasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('ayudas_diagnosticas', function(Blueprint $table)
		{
			$table->foreign('id_registro', 'ayudas_diagnosticasf1')->references('id_registro_seq')->on('registro')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('ambito', 'ayudas_diagnosticasf2')->references('cod_ambito')->on('ambito')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('tipo_prueba', 'ayudas_diagnosticasf3')->references('id_prueba')->on('ayudas_diagnosticas_pruebas')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('ayudas_diagnosticas', function(Blueprint $table)
		{
			$table->dropForeign('ayudas_diagnosticasf1');
			$table->dropForeign('ayudas_diagnosticasf2');
			$table->dropForeign('ayudas_diagnosticasf3');
		});
	}

}
