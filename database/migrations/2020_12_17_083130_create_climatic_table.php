<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClimaticTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('climatic', function (Blueprint $table) {
            $table->string('state');
            $table->year('year');
            $table->string('month');
            $table->float('temperature');
            $table->float('rainAmount');
            $table->integer('rainDay');
            $table->float('humidity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('climatic');
    }
}
