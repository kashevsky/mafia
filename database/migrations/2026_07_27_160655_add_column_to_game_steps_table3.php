<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToGameStepsTable3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('game_steps', function (Blueprint $table) {
            $table->unsignedBigInteger('account_id')->nullable();
            $table->unsignedBigInteger('table_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('game_steps', function (Blueprint $table) {
            $table->dropColumn('account_id');
            $table->dropColumn('table_id');
        });
    }
}
