<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Quiz Rooms ────────────────────────────────────────────────
        Schema::create('quiz_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('batch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('room_code', 20)->unique();
            $table->enum('status', ['draft', 'active', 'closed'])->default('draft');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->integer('duration_minutes')->default(30);
            $table->timestamps();
        });

        // ── Quiz Questions ────────────────────────────────────────────
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_room_id')->constrained()->cascadeOnDelete();
            $table->text('question');
            $table->string('option_a');
            $table->string('option_b');
            $table->string('option_c')->nullable();
            $table->string('option_d')->nullable();
            $table->enum('correct_option', ['a', 'b', 'c', 'd']);
            $table->integer('marks')->default(1);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // ── Quiz Attempts ─────────────────────────────────────────────
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_room_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('score')->default(0);
            $table->integer('total')->default(0);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->unique(['quiz_room_id', 'user_id']);
        });

        // ── Quiz Attempt Answers ──────────────────────────────────────
        Schema::create('quiz_attempt_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_attempt_id')->constrained()->cascadeOnDelete();
            $table->foreignId('quiz_question_id')->constrained()->cascadeOnDelete();
            $table->enum('selected_option', ['a', 'b', 'c', 'd'])->nullable();
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
        });

        // ── Custom Forms ──────────────────────────────────────────────
        Schema::create('custom_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamp('closes_at')->nullable();
            $table->timestamps();
        });

        // ── Form Fields ───────────────────────────────────────────────
        Schema::create('form_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('custom_form_id')->constrained()->cascadeOnDelete();
            $table->string('label');
            $table->enum('type', ['text', 'textarea', 'select', 'radio', 'checkbox', 'file', 'date', 'number']);
            $table->json('options')->nullable();  // for select/radio/checkbox
            $table->boolean('required')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // ── Form Responses ────────────────────────────────────────────
        Schema::create('form_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('custom_form_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->json('answers');  // {field_id: value}
            $table->string('respondent_name')->nullable();
            $table->string('respondent_email')->nullable();
            $table->timestamps();
        });

        // ── Readmission Applications ──────────────────────────────────
        Schema::create('readmissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('batch_id')->nullable()->constrained()->nullOnDelete(); // target batch
            $table->text('reason');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        // ── Course Transfers ──────────────────────────────────────────
        if (!Schema::hasTable('batch_transfers')) {
            Schema::create('batch_transfers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('from_batch_id')->constrained('batches')->cascadeOnDelete();
                $table->foreignId('to_batch_id')->constrained('batches')->cascadeOnDelete();
                $table->text('reason')->nullable();
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('transferred_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('batch_transfers');
        Schema::dropIfExists('readmissions');
        Schema::dropIfExists('form_responses');
        Schema::dropIfExists('form_fields');
        Schema::dropIfExists('custom_forms');
        Schema::dropIfExists('quiz_attempt_answers');
        Schema::dropIfExists('quiz_attempts');
        Schema::dropIfExists('quiz_questions');
        Schema::dropIfExists('quiz_rooms');
    }
};
