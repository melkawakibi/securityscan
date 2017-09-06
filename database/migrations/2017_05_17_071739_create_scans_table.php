<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('website_id')->unsigned();
            $table->foreign('website_id')->references('id')->on('websites');
            $table->timestamp('time_end')->nullable();
            $table->string('time_taken')->nullable();
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
        Schema::dropIfExists('scans');
    }
}
