<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysEapb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('eapbs', function(Blueprint $table)
        {
            $table->foreign('tipo_identificacion', 'eapbs_fkey1')->references('id_tipo_ident')->on('tipo_ident_eapb')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('tipo_entidad', 'eapbs_fkey2')->references('id_tipo_ent')->on('tipo_eapb')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('eapbs', function(Blueprint $table)
        {
            $table->dropForeign('eapbs_fkey1');
            $table->dropForeign('eapbs_fkey2');
            
        });
    }
}
