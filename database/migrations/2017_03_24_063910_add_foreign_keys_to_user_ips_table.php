<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToUserIpsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user_ips', function(Blueprint $table)
		{
			$table->foreign('tipo_identificacion', 'user_ips_fkey2')->references('id_tipo_ident')->on('tipo_identificacion_user')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('sexo', 'user_ips_fkey3')->references('id_genero')->on('generos_user')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('user_ips', function(Blueprint $table)
		{
			$table->dropForeign('user_ips_fkey2');
			$table->dropForeign('user_ips_fkey3');
		});
	}

}
