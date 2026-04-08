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
            $table->tinyInteger('is_active')->default(1)->after('balance')->comment('0 for deactive, 1 for active');
        });

        Schema::table('games', function (Blueprint $table) {
            $table->tinyInteger('is_active')->default(1)->after('coins')->comment('0 for deactive, 1 for active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });

        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
