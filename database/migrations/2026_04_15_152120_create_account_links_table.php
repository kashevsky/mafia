<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_links', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->tinyInteger('is_used')->nullable();
            $table->unsignedBigInteger('club_id')->nullable();
            $table->index('club_id');
            $table->unsignedBigInteger('account_id')->nullable();
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
        Schema::dropIfExists('account_links');
    }
}
