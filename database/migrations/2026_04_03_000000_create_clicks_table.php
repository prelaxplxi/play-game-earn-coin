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
        Schema::create('clicks', function (Blueprint $table) {
            $table->id();
            $table->string('click_id', 64)->unique();
            $table->string('campaign_id', 64)->nullable();
            $table->string('source', 64)->nullable();
            $table->string('device_id', 128)->nullable();
            $table->string('app_user_id', 128)->nullable();
            $table->dateTime('created_at')->useCurrent();

            $table->index('device_id', 'idx_device_id');
            $table->index('app_user_id', 'idx_app_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clicks');
    }
};
