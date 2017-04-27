<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPesoTallaTensionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('peso_talla_tension', function(Blueprint $table)
		{
			$table->foreign('id_registro', 'peso_talla_tensionf1')->references('id_registro_seq')->on('registro')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('ambito', 'peso_talla_tensionf2')->references('cod_ambito')->on('ambito')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('peso_talla_tension', function(Blueprint $table)
		{
			$table->dropForeign('peso_talla_tensionf1');
			$table->dropForeign('peso_talla_tensionf2');
		});
	}

}
