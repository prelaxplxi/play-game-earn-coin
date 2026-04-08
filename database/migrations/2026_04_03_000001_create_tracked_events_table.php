<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tracked_events', function (Blueprint $table) {
            $table->id();
            $table->string('click_id', 64);
            $table->unsignedBigInteger('click_db_id')->nullable();
            $table->string('event_name', 64);
            $table->dateTime('event_time');
            $table->string('device_id', 128)->nullable();
            $table->string('app_user_id', 128)->nullable();
            $table->string('transaction_id', 128)->nullable();
            $table->decimal('revenue', 12, 2)->nullable();
            $table->string('currency', 8)->nullable();
            $table->json('meta_json')->nullable();
            $table->json('raw_payload');
            $table->dateTime('created_at')->useCurrent();

            $table->index('click_id', 'idx_click_id');
            $table->index('event_name', 'idx_event_name');
            $table->index('event_time', 'idx_event_time');

            $table->foreign('click_db_id', 'fk_tracked_events_click')
                ->references('id')
                ->on('clicks')
                ->onDelete('set null');
        });

        // add unique index with prefix length to avoid utf8mb4 maximum index key length issues
        DB::statement('ALTER TABLE tracked_events ADD UNIQUE KEY uniq_event_dedupe (click_id(32), event_name(32), transaction_id(64))');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracked_events');
    }
};