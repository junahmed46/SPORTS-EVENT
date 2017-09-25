<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSportEventDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sport_event_athletes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('SE_id');
            $table->integer('A_id');
            $table->string('code_identifier',64);
            $table->integer('start_number');
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
        Schema::dropIfExists('sport_event_detail');
    }
}
