<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Eapb;

class Eapbs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('eapbs')){
            Schema::create('eapbs', function(Blueprint $table)
            {
                $table->bigInteger('id_entidad', true);
                $table->string('tipo_entidad', 3)->nullable();
                $table->string('tipo_identificacion', 3)->nullable();
                $table->string('num_identificacion', 12)->nullable();
                $table->string('cod_eapb', 12)->nullable();
                $table->string('nombre', 300)->nullable();
                $table->integer('cod_mpio')->nullable();
                $table->unique(['num_identificacion','cod_eapb'], 'eapbs_uniquekey');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {   
         Schema::drop('eapbs');
    }
}
