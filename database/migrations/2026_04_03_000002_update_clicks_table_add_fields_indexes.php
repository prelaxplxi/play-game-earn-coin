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
        Schema::table('clicks', function (Blueprint $table) {
            if (!Schema::hasColumn('clicks', 'sub_source')) {
                $table->string('sub_source', 128)->nullable()->after('source');
            }
            if (!Schema::hasColumn('clicks', 'ip_address')) {
                $table->string('ip_address', 45)->nullable()->after('app_user_id');
            }
            if (!Schema::hasColumn('clicks', 'user_agent')) {
                $table->string('user_agent', 500)->nullable()->after('ip_address');
            }
            if (!Schema::hasColumn('clicks', 'landing_url')) {
                $table->string('landing_url', 1000)->nullable()->after('user_agent');
            }
            if (!Schema::hasColumn('clicks', 'referrer')) {
                $table->string('referrer', 1000)->nullable()->after('landing_url');
            }
            if (!Schema::hasColumn('clicks', 'meta_json')) {
                $table->json('meta_json')->nullable()->after('referrer');
            }

            if (!Schema::hasColumn('clicks', 'campaign_id') || !Schema::hasColumn('clicks', 'source') || !Schema::hasColumn('clicks', 'created_at')) {
                if (!Schema::hasTable('clicks')) {
                    return;
                }

                // add indexes only if they do not exist
                $indexNames = collect(DB::select("SHOW INDEX FROM clicks"))->pluck('Key_name')->toArray();

                if (!in_array('idx_campaign_id', $indexNames)) {
                    $table->index('campaign_id', 'idx_campaign_id');
                }
                if (!in_array('idx_source', $indexNames)) {
                    $table->index('source', 'idx_source');
                }
                if (!in_array('idx_created_at', $indexNames)) {
                    $table->index('created_at', 'idx_created_at');
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clicks', function (Blueprint $table) {
            $table->dropIndex('idx_campaign_id');
            $table->dropIndex('idx_source');
            $table->dropIndex('idx_created_at');

            $table->dropColumn(['sub_source', 'ip_address', 'user_agent', 'landing_url', 'referrer', 'meta_json']);
        });
    }
};