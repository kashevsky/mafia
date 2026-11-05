<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGameStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_steps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('game_id')->nullable();
            $table->index('game_id');
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
            $table->unsignedBigInteger('from_user_id')->nullable();
            $table->index('from_user_id');
            $table->foreign('from_user_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->unsignedBigInteger('to_user_id')->nullable();
            $table->index('to_user_id');
            $table->foreign('to_user_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->string('action')->nullable();
            $table->string('status')->nullable();
            $table->string('take_rating_where_true')->nullable();
            $table->string('take_rating_where_false')->nullable();
            $table->string('doctor_healed_self_from_shoot')->nullable();
            $table->string('doctor_healed_civillian_from_shoot_mafia')->nullable();
            $table->string('doctor_healed_civillian_from_shoot')->nullable();
            $table->string('doctor_healed_civillian_from_no_shoot')->nullable();
            $table->string('role')->nullable();
            $table->integer('step')->nullable();
            $table->tinyInteger('is_don')->default(0);
            $table->tinyInteger('is_binary')->default(1);
            $table->tinyInteger('is_confirmed')->default(0);
            $table->text('text')->nullable();
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
        Schema::dropIfExists('game_steps');
    }
}
