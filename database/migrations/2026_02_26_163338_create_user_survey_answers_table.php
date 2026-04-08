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
        Schema::create('user_survey_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('response_id');
            $table->unsignedBigInteger('question_id');
            $table->unsignedBigInteger('option_id');
            $table->boolean('is_correct')->default(false);
            $table->timestamps();

            $table->foreign('response_id')->references('id')->on('user_survey_responses')->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('survey_questions')->onDelete('cascade');
            $table->foreign('option_id')->references('id')->on('survey_question_options')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_survey_answers');
    }
};
