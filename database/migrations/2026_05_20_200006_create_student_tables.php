<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('student_id')->unique(); // auto-generated e.g. IOM-2025-0001
            $table->string('phone')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('address')->nullable();
            $table->string('photo')->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('guardian_phone')->nullable();
            $table->string('guardian_relation')->nullable();
            $table->enum('status', ['active', 'inactive', 'transferred', 'suspended'])->default('active');
            $table->timestamps();
        });

        Schema::create('student_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('batch_id')->constrained()->cascadeOnDelete();
            $table->date('enrolled_at');
            $table->enum('status', ['active', 'completed', 'transferred'])->default('active');
            $table->timestamps();
        });

        Schema::create('student_semester_promotions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('from_semester_id')->constrained('semesters')->cascadeOnDelete();
            $table->foreignId('to_semester_id')->constrained('semesters')->cascadeOnDelete();
            $table->foreignId('promoted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['promoted', 'held_back', 're_exam'])->default('promoted');
            $table->text('remarks')->nullable();
            $table->timestamp('promoted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_semester_promotions');
        Schema::dropIfExists('student_batches');
        Schema::dropIfExists('student_profiles');
    }
};
