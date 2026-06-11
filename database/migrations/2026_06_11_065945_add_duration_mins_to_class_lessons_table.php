<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('class_lessons', function (Blueprint $table) {
            $table->unsignedSmallInteger('duration_mins')->nullable()->after('type');
        });
    }

    public function down(): void
    {
        Schema::table('class_lessons', function (Blueprint $table) {
            $table->dropColumn('duration_mins');
        });
    }
};