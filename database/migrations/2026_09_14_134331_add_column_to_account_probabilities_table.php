<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToAccountProbabilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_probabilities', function (Blueprint $table) {
            $table->unsignedBigInteger('game_id')->nullable();
            $table->unsignedBigInteger('table_id')->nullable();
            $table->tinyInteger('is_activated')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_probabilities', function (Blueprint $table) {
            //
        });
    }
}
