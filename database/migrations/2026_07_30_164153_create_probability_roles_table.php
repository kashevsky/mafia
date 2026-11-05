<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProbabilityRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('probability_roles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('probability_id')->nullable();
            $table->index('probability_id');
            $table->foreign('probability_id')->references('id')->on('probabilities')->onDelete('cascade');
            $table->unsignedBigInteger('role_id')->nullable();
            $table->index('role_id');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
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
        Schema::dropIfExists('probability_roles');
    }
}
