<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quiz_id',
        'score',
        'total',
    ];

    /**
     * A quiz result belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A quiz result belongs to a quiz
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Get percentage score
     */
    public function getPercentageAttribute()
    {
        return $this->total > 0 ? round(($this->score / $this->total) * 100, 2) : 0;
    }
}
