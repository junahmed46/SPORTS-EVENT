<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAtheleteProgress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('athlete_progress', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('SEA_id');
            $table->enum('finish_type', ['corridor', 'line']);
            $table->decimal('clock_time',14,4);
            $table->integer('step_history');
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
        Schema::dropIfExists('athlete_progress');
    }
}
