<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Section: Add dedicated lesson material columns for videos and PDFs.
     */
    public function up(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->string('video_path')->nullable()->after('media_path');
            $table->string('pdf_path')->nullable()->after('video_path');
        });
    }

    /**
     * Section: Remove the dedicated lesson material columns.
     */
    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn(['video_path', 'pdf_path']);
        });
    }
};
