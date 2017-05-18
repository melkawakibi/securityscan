<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScanDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scan_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('scan_id')->unsigned();
            $table->foreign('scan_id')->references('id')->on('scans');
            $table->integer('f_scan_key')->unsigned();
            $table->foreign('f_scan_key')->references('scan_key')->on('scans');
            $table->string('type');
            $table->string('message');
            $table->string('sql_inj');
            $table->string('thread');
            $table->integer('thread_level');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scan_details');
    }
}
