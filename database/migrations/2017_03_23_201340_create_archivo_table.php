<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateArchivoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('archivo', function(Blueprint $table)
		{
			$table->bigInteger('id_archivo_seq', true);
			$table->string('modulo_informacion', 3)->nullable();
			$table->integer('tipo_fuente')->nullable();
			$table->string('id_tema_informacion', 3)->nullable();
			$table->string('tipo_periodo', 10)->nullable();
			$table->date('fecha_ini_periodo')->nullable();
			$table->date('fecha_fin_periodo')->nullable();
			$table->bigInteger('id_entidad')->nullable();
			$table->bigInteger('numero_registros')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('archivo');
	}

}
