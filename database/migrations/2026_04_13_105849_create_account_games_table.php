<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_games', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->index('account_id');
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->unsignedBigInteger('game_id')->nullable();
            $table->index('game_id');
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
            $table->string('price')->nullable();
            $table->tinyInteger('is_paid')->nullable();
            $table->string('role')->nullable();
            $table->string('character')->nullable();
            $table->string('die_in')->nullable(); // day-2 (день / ночь - какая по счету)
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
        Schema::dropIfExists('account_games');
    }
}
