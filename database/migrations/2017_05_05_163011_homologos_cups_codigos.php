<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class HomologosCupsCodigos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('homologos_cups_codigos', function(Blueprint $table)
        {
            $table->bigInteger('id_seq', true);
            $table->string('cod_homologo', 16);
            $table->string('cod_cups', 16);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('homologos_cups_codigos');
    }
}
