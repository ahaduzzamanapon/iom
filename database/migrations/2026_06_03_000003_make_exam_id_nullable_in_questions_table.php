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
        Schema::table('questions', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['exam_id']);
            
            // Make column nullable
            $table->unsignedBigInteger('exam_id')->nullable()->change();
            
            // Re-add foreign key with cascade on delete
            $table->foreign('exam_id')->references('id')->on('exams')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropForeign(['exam_id']);
            $table->unsignedBigInteger('exam_id')->nullable(false)->change();
            $table->foreign('exam_id')->references('id')->on('exams')->cascadeOnDelete();
        });
    }
};
