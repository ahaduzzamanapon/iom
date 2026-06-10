<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('teacher_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('teacher_id')->unique(); // IOM-T-001
            $table->string('phone')->nullable();
            $table->string('qualification')->nullable();
            $table->string('specialization')->nullable();
            $table->string('photo')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        Schema::create('routines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->enum('day', ['saturday','sunday','monday','tuesday','wednesday','thursday','friday']);
            $table->time('start_time');
            $table->time('end_time');
            $table->string('room')->nullable();
            $table->enum('type', ['class', 'exam'])->default('class');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('routines');
        Schema::dropIfExists('teacher_profiles');
    }
};
