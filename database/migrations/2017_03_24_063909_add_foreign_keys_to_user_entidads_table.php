<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToUserEntidadsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user_entidads', function(Blueprint $table)
		{
			$table->foreign('id_user','user_entidads_userid_foreign')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('id_entidad','user_entidads_id_entidad_foreign')->references('id_entidad')->on('entidades_sector_salud')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('user_entidads', function(Blueprint $table)
		{
			$table->dropForeign('user_entidads_userid_foreign');
			$table->dropForeign('user_entidads_id_entidad_foreign');
		});
	}

}
