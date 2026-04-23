<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    // Section: Mass-assignable lesson fields.
    protected $fillable = [
        'course_id',
        'title',
        'content',
        'media_path',
        'video_path',
        'pdf_path',
    ];

    /**
     * Section: Lesson to course relationship.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Section: Lesson to quiz relationship.
     */
    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    /**
     * Section: Resolve the current lesson video, including legacy uploads.
     */
    public function resolvedVideoPath(): ?string
    {
        if ($this->video_path) {
            return $this->video_path;
        }

        if ($this->media_path && strtolower(pathinfo($this->media_path, PATHINFO_EXTENSION)) === 'mp4') {
            return $this->media_path;
        }

        return null;
    }

    /**
     * Section: Resolve the current lesson PDF, including legacy uploads.
     */
    public function resolvedPdfPath(): ?string
    {
        if ($this->pdf_path) {
            return $this->pdf_path;
        }

        if ($this->media_path && strtolower(pathinfo($this->media_path, PATHINFO_EXTENSION)) === 'pdf') {
            return $this->media_path;
        }

        return null;
    }
}
