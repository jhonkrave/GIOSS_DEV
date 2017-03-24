<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToDIdentificacionUsrIpsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('d_identificacion_usr_ips', function(Blueprint $table)
		{
			$table->foreign('cod_prestador_servicios_salud', 'd_identificacion_usr_ips_fkey1')->references('id_entidad')->on('entidades_sector_salud')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('d_identificacion_usr_ips', function(Blueprint $table)
		{
			$table->dropForeign('d_identificacion_usr_ips_fkey1');
		});
	}

}
