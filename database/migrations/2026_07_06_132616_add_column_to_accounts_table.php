<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounts', function (Blueprint $table) {

            $table->string('telegram_username')->nullable();
            $table->timestamp('telegram_registered_at')->nullable();
            $table->boolean('is_telegram_registered')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn('telegram_username');
            $table->dropColumn('telegram_registered_at');
            $table->dropColumn('is_telegram_registered');
        });
    }
}
