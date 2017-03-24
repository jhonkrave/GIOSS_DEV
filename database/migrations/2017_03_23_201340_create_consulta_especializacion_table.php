<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateConsultaEspecializacionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('consulta_especializacion', function(Blueprint $table)
		{
			$table->smallInteger('cod_consulta_esp')->primary('consulta_especializacion_pkey');
			$table->string('descrip_consulta_esp', 100)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('consulta_especializacion');
	}

}
