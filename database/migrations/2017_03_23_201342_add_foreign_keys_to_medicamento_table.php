<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToMedicamentoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('medicamento', function(Blueprint $table)
		{
			$table->foreign('id_registro', 'medicamentofk1')->references('id_registro_seq')->on('registro')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('ambito_suministro', 'medicamentofk2')->references('cod_ambito')->on('ambito')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('medicamento', function(Blueprint $table)
		{
			$table->dropForeign('medicamentofk1');
			$table->dropForeign('medicamentofk2');
		});
	}

}
