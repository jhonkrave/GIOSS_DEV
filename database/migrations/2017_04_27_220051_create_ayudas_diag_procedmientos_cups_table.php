<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAyudasDiagProcedmientosCupsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ayudas_diag_procedmientos_cups', function(Blueprint $table)
		{
			$table->string('cod_procedimiento', 2)->primary('ayudas_diag_procedmientos_cups_pkey');
			$table->string('descripcion', 100)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ayudas_diag_procedmientos_cups');
	}

}
