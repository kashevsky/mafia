<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempComformistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_comformists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('from_user_id')->nullable();
            $table->index('from_user_id');
            $table->foreign('from_user_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->unsignedBigInteger('to_user_id')->nullable();
            $table->index('to_user_id');
            $table->foreign('to_user_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->string('time_of_day')->nullable();
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
        Schema::dropIfExists('temp_comformists');
    }
}
