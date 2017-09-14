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
            $table->string('module_name');
            $table->string('risk');
            $table->string('target');
            $table->string('parameter');
            $table->text('attack');
            $table->string('error');
            $table->string('wasc_id');
            $table->string('execution_time');
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
