<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToEntidadesSectorSaludTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('entidades_sector_salud', function(Blueprint $table)
		{
			$table->foreign('cod_tipo_entidad', 'entidades_sector_salud_fkey1')->references('codigo_tipo_entidad')->on('tipo_entidad')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('cod_mpio', 'entidades_sector_salud_fkey2')->references('cod_divipola')->on('municipios')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('entidades_sector_salud', function(Blueprint $table)
		{
			$table->dropForeign('entidades_sector_salud_fkey1');
			$table->dropForeign('entidades_sector_salud_fkey2');
		});
	}

}
