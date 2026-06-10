<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // surveys table
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamp('closes_at')->nullable();
            $table->timestamps();
        });

        // survey_questions table
        Schema::create('survey_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_id')->constrained()->cascadeOnDelete();
            $table->string('question_key'); // unique within the survey (e.g. q1, q_major, q_occupation)
            $table->string('label');
            $table->enum('type', ['text', 'textarea', 'select', 'radio', 'checkbox', 'date', 'number']);
            $table->json('options')->nullable(); // JSON containing choice values & branch targets
            $table->string('default_next_question_key')->nullable(); // where to go next by default (or if non-choice type)
            $table->boolean('is_start')->default(false); // entry point question
            $table->boolean('required')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // survey_responses table
        Schema::create('survey_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->json('answers'); // JSON structure: {"question_key": "user_answer"}
            $table->string('respondent_name')->nullable();
            $table->string('respondent_email')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survey_responses');
        Schema::dropIfExists('survey_questions');
        Schema::dropIfExists('surveys');
    }
};
