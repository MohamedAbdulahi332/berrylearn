<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'title',
    ];

    /**
     * A quiz belongs to a lesson
     */
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * A quiz has many questions
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    /**
     * A quiz has many results
     */
    public function results()
    {
        return $this->hasMany(QuizResult::class);
    }
}
