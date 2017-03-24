<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToRegistroTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('registro', function(Blueprint $table)
		{
			$table->foreign('id_archivo', 'registrosfk1')->references('id_archivo_seq')->on('archivo')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('id_user', 'registrosfk2')->references('id_user')->on('user_ips')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('registro', function(Blueprint $table)
		{
			$table->dropForeign('registrosfk1');
			$table->dropForeign('registrosfk2');
		});
	}

}
