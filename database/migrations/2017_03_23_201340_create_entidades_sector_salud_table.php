<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEntidadesSectorSaludTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('entidades_sector_salud', function(Blueprint $table)
		{
			$table->integer('id_entidad', true);
			$table->integer('cod_tipo_entidad')->nullable();
			$table->string('nombre_de_la_entidad', 320)->nullable();
			$table->integer('cod_mpio')->nullable();
			$table->bigInteger('num_identificacion')->nullable();
			$table->bigInteger('cod_habilitacion')->nullable();
			$table->unique(['num_identificacion','cod_habilitacion'], 'entidades_sector_salud_uniquekey');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('entidades_sector_salud');
	}

}
