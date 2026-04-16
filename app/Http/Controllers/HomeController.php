<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show student homepage with courses and lessons
     */
    public function index(Request $request)
    {
        // Get all courses
        $courses = Course::with('lessons')->get();

        // Get selected course and lesson if provided
        $selectedCourse = null;
        $selectedLesson = null;

        if ($request->has('course_id')) {
            $selectedCourse = Course::with('lessons')->find($request->course_id);
        }

        if ($request->has('lesson_id')) {
            $selectedLesson = Lesson::with(['quizzes.questions'])->find($request->lesson_id);
        }

        return view('student.home', compact('courses', 'selectedCourse', 'selectedLesson'));
    }
}
