<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToResultadosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('resultados', function(Blueprint $table)
		{
			$table->foreign('referencia_usuario', 'entidades_sector_salud_fkey1')->references('id_user')->on('user_ips')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('resultados', function(Blueprint $table)
		{
			$table->dropForeign('entidades_sector_salud_fkey1');
		});
	}

}
