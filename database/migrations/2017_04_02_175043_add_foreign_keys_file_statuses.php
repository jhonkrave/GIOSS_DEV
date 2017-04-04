<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysFileStatuses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('file_statuses', function(Blueprint $table)
        {
            $table->foreign('archivoid', 'file_statuses_fkey1')->references('id_archivo_seq')->on('archivo')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('file_statuses', function(Blueprint $table)
        {
            $table->dropForeign('file_statuses_fkey1');
        });
    }
}
