<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('avatar')->nullable();
            $table->string('status')->nullable();
            $table->string('date')->nullable();
            $table->string('time')->nullable();
            $table->string('free_space')->nullable();
            $table->string('total_space')->nullable();
            $table->string('occupied_space')->nullable();
            $table->string('address')->nullable();
            $table->unsignedBigInteger('game_id')->nullable();
            $table->index('game_id');
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
            $table->string('price')->nullable();
            $table->string('finished_at')->nullable();
            $table->tinyInteger('is_reported')->nullable();
            $table->longText('game_object')->nullable();
            $table->integer('seats')->nullable();
            $table->integer('free_seats')->nullable();
            $table->integer('sold_seats')->nullable();
            $table->integer('total_seats')->nullable();
            $table->tinyInteger('is_running')->default(0);
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
        Schema::dropIfExists('tables');
    }
}
