<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('class_lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained()->cascadeOnDelete();
            $table->foreignId('batch_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('title_bn')->nullable();
            $table->enum('type', ['video', 'live', 'pdf', 'note'])->default('video');
            $table->string('youtube_url')->nullable();
            $table->string('file_path')->nullable();
            $table->string('meet_link')->nullable();   // Google Meet
            $table->string('zoom_link')->nullable();   // Zoom
            $table->dateTime('scheduled_at')->nullable();
            $table->unsignedSmallInteger('order')->default(1);
            $table->boolean('is_published')->default(false);
            $table->dateTime('publish_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_lessons');
    }
};
