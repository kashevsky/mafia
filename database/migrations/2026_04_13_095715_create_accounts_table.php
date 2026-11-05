<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->integer('rating')->default(0);
            $table->integer('number')->nullable();
            $table->string('arr_id_vote')->nullable();
            $table->json('buff_role')->nullable();

            $table->string('character')->nullable();
            $table->integer('count')->default(0);
            $table->tinyInteger('active')->default(0);
            $table->unsignedBigInteger('game_id')->nullable();
            $table->unsignedBigInteger('table_id')->nullable();
            $table->unsignedBigInteger('is_vote')->nullable();
            $table->integer('position')->nullable();
            $table->string('role')->nullable();
            $table->tinyInteger('is_alive')->nullable();
            $table->integer('start_game')->nullable();
            $table->integer('target')->nullable();
            $table->string('nickname')->nullable();
            $table->string('password')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('admin_role')->nullable();
            $table->tinyInteger('is_admin')->nullable();
            $table->integer('balance')->default(0);
            $table->string('avatar')->nullable();
            $table->string('status')->nullable();
            $table->text('bio')->nullable();
            $table->string('birth')->nullable();
            $table->unsignedBigInteger('club_id')->nullable();
            $table->string('telegram_id')->nullable();
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
        Schema::dropIfExists('accounts');
    }
}
