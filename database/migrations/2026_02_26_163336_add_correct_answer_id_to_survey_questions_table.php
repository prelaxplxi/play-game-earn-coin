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
        Schema::table('survey_questions', function (Blueprint $table) {
            $table->unsignedBigInteger('correct_answer_id')->nullable()->after('question');
            $table->foreign('correct_answer_id')->references('id')->on('survey_question_options')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survey_questions', function (Blueprint $table) {
            $table->dropForeign(['correct_answer_id']);
            $table->dropColumn('correct_answer_id');
        });
    }
};
