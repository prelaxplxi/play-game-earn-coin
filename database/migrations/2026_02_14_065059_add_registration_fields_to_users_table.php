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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_no')->nullable()->after('email');
            $table->string('photo_url')->nullable()->after('phone_no');
            $table->string('gmail_account_id')->nullable()->unique()->after('photo_url');
            $table->string('password')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone_no', 'photo_url', 'gmail_account_id']);
            $table->string('password')->nullable(false)->change();
        });
    }
};
