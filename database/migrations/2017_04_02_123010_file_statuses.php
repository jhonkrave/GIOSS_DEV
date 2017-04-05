<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FileStatuses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('file_statuses', function(Blueprint $table)
        {
            $table->bigInteger('file_statuses_id', true);
            $table->bigInteger('consecutive');
            $table->bigInteger('archivoid');
            $table->string('current_status', 20)->nullable();
            $table->integer('porcent')->nullable()->default(0);
            $table->integer('total_registers')->nullable()->default(0);
            $table->integer('current_line')->nullable()->default(0);
            $table->string('final_status', 20)->nullable();
            $table->string('zipath', 300)->nullable();
            $table->unique(['file_statuses_id','consecutive','archivoid']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('file_statuses');
    }
}
