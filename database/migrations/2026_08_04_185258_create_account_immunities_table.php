<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountImmunitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_immunities', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->string('dop')->nullable();
            $table->string('src')->nullable();
            $table->string('exeption')->nullable();
            $table->integer('count')->nullable();
            $table->integer('is_active')->nullable();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->index('account_id');
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->unsignedBigInteger('immunity_id')->nullable();
            $table->index('immunity_id');
            $table->foreign('immunity_id')->references('id')->on('immunities')->onDelete('cascade');
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
        Schema::dropIfExists('account_immunities');
    }
}
