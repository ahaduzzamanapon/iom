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
        Schema::table('class_lessons', function (Blueprint $table) {
            $table->string('meeting_provider')->nullable()->after('zoom_link'); // 'zoom' | 'google_meet' | 'manual'
            $table->string('zoom_meeting_id')->nullable()->after('meeting_provider');
            $table->text('zoom_start_url')->nullable()->after('zoom_meeting_id'); // teacher-only host link
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_lessons', function (Blueprint $table) {
            $table->dropColumn(['meeting_provider', 'zoom_meeting_id', 'zoom_start_url']);
        });
    }
};
