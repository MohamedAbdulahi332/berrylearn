<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'content',
        'media_path',
    ];

    /**
     * A lesson belongs to a course
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * A lesson has many quizzes
     */
    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }
}
