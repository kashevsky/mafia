<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('broadcasts', function (Blueprint $table) {
            $table->id();
            $table->text('message');
            $table->json('images')->nullable();
            $table->string('button_text')->nullable();
            $table->string('button_url')->nullable();
            $table->dateTime('scheduled_time')->nullable();
            $table->string('status')->default('pending'); // pending, sent, failed
            $table->integer('user_count')->default(0);
            $table->timestamps();
        });

        Schema::create('account_broadcast', function (Blueprint $table) {
            $table->foreignId('broadcast_id')->constrained()->cascadeOnDelete();
            $table->foreignId('account_id')->constrained()->cascadeOnDelete();
            $table->primary(['broadcast_id', 'account_id']);
            $table->timestamps();
        });

        Schema::create('broadcast_sent_log', function (Blueprint $table) {
            $table->foreignId('broadcast_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('sent_at')->useCurrent();
            $table->primary(['broadcast_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('broadcast_sent_log');
        Schema::dropIfExists('broadcast_user');
        Schema::dropIfExists('broadcasts');
    }
};
