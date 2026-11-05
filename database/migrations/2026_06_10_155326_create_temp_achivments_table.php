<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempAchivmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_achivments', function (Blueprint $table) {
            $table->id();
            $table->string('achivment')->nullable();
            $table->unsignedBigInteger('from_user_id')->nullable();
            $table->index('from_user_id');
            $table->foreign('from_user_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->unsignedBigInteger('to_user_id')->nullable();
            $table->index('to_user_id');
            $table->foreign('to_user_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->string('time_of_day')->nullable();
            $table->integer('count_nights')->nullable();
            $table->unsignedBigInteger('game_id')->nullable();
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
        Schema::dropIfExists('temp_achivments');
    }
}
