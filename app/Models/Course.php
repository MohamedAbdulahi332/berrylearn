<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    // Section: Mass-assignable course fields.
    protected $fillable = [
        'title',
        'description',
    ];

    /**
     * Section: Course to lesson relationship.
     */
    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    /**
     * Section: Quick link to YouTube search results for this course.
     */
    public function youtubeSearchUrl(): string
    {
        return 'https://www.youtube.com/results?search_query=' . urlencode($this->title . ' tutorial');
    }
}
