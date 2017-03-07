<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserEntidadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_entidads', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('userid');
            $table->string('cod_entidad',12);
            
            $table->foreign('userid')
                  ->references('id')->on('users')
                  ->onUpdate('cascade')
                  ->onDelete('no action');

            $table->foreign('cod_entidad')
                  ->references('codigo_entidad')->on('entidades_sector_salud')
                  ->onUpdate('cascade')
                  ->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_entidads');
    }
}
