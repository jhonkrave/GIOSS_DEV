<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToArchivoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('archivo', function(Blueprint $table)
		{
			$table->foreign('id_tema_informacion', 'archivofk1')->references('id_tema_informacion')->on('tema_informacion')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('id_entidad', 'archivofk2')->references('id_entidad')->on('entidades_sector_salud')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('archivo', function(Blueprint $table)
		{
			$table->dropForeign('archivofk1');
			$table->dropForeign('archivofk2');
		});
	}

}
