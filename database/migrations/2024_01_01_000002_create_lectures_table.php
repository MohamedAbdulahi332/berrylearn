<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the 'lectures' table to store lecture materials.
     */
    public function up(): void
    {
        Schema::create('lectures', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('course_id') // Foreign key to courses table
                  ->constrained() // Creates foreign key constraint
                  ->onDelete('cascade'); // Delete lectures when course is deleted
            $table->string('title'); // Lecture title
            $table->text('content')->nullable(); // Lecture text content
            $table->string('video_url')->nullable(); // Optional video URL
            $table->string('file_path')->nullable(); // Optional file path
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lectures');
    }
};