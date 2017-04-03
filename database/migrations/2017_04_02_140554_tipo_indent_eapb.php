<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TipoIndentEapb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_ident_eapb', function(Blueprint $table)
        {
            $table->string('id_tipo_ident', 3)->primary('tipo_identificacion_entidad_pkey');
            $table->string('descripcion', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tipo_ident_eapb');
    }
}
