<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('earning_coin_histories', function (Blueprint $table) {
            $table->string('type')->default('game')->after('earning_coins');
            $table->text('description')->nullable()->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('earning_coin_histories', function (Blueprint $table) {
            $table->dropColumn(['type', 'description']);
        });
    }
};
