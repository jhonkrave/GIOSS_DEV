<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToConsultaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('consulta', function(Blueprint $table)
		{
			$table->foreign('id_registro', 'consultafk1')->references('id_registro_seq')->on('registro')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('cod_consulta_esp', 'consultafk2')->references('cod_consulta_esp')->on('consulta_especializacion')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('cod_diagnostico_principal', 'consultafk3')->references('cod_diagnostico')->on('diagnostico_ciex')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('cod_diagnostico_rel1', 'consultafk4')->references('cod_diagnostico')->on('diagnostico_ciex')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('cod_diagnostico_rel2', 'consultafk5')->references('cod_diagnostico')->on('diagnostico_ciex')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('ambito_consulta', 'consultafk6')->references('cod_ambito')->on('ambito')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('finalidad_consulta', 'consultafk7')->references('cod_finalidad')->on('finalidad_consulta')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('tipo_diagnostico_principal', 'consultafk8')->references('cod_tipo')->on('tipo_diagnostico')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('consulta', function(Blueprint $table)
		{
			$table->dropForeign('consultafk1');
			$table->dropForeign('consultafk2');
			$table->dropForeign('consultafk3');
			$table->dropForeign('consultafk4');
			$table->dropForeign('consultafk5');
			$table->dropForeign('consultafk6');
			$table->dropForeign('consultafk7');
			$table->dropForeign('consultafk8');
		});
	}

}
