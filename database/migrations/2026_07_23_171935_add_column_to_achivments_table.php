<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToAchivmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('achivments', function (Blueprint $table) {
            $table->string('step')->nullable();
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
        Schema::table('achivments', function (Blueprint $table) {
            $table->dropColumn('step');
            $table->dropColumn('table_id');
        });
    }
}
