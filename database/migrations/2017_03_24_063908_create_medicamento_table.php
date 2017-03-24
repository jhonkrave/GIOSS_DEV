<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMedicamentoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('medicamento', function(Blueprint $table)
		{
			$table->bigInteger('ident_medicamento_seq', true);
			$table->bigInteger('id_registro');
			$table->bigInteger('fecha_entrega');
			$table->smallInteger('tipo_codificacion');
			$table->string('codigo_medicamento', 40)->nullable();
			$table->integer('catidad')->nullable();
			$table->string('ambito_suministro', 8)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('medicamento');
	}

}
