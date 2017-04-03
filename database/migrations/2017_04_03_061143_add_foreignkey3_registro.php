<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignkey3Registro extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('registro', function(Blueprint $table)
        {
            $table->foreign('id_eapb', 'registrosfk3')->references('id_entidad')->on('eapbs')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('registro', function(Blueprint $table)
        {
            $table->dropForeign('registrosfk3');
        });
    }
}
