<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToGameResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('game_results', function (Blueprint $table) {
            $table->unsignedBigInteger('table_id')->nullable();
            $table->integer('step')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('game_results', function (Blueprint $table) {
            $table->dropColumn('table_id');
            $table->dropColumn('step');
        });
    }
}
