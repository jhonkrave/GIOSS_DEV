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
			$table->bigInteger('id_entidad', true);
			$table->string('tipo_entidad', 3)->nullable();
			$table->string('tipo_identificacion', 3)->nullable();
			$table->string('num_identificacion', 12)->nullable();
			$table->string('cod_habilitacion', 12)->nullable();
			$table->string('nombre', 300)->nullable();
			$table->integer('cod_mpio')->nullable();
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
