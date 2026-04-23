<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Section: Show the student homepage with course and lesson content.
     */
    public function index(Request $request)
    {
        // Section: Load the course browser with lesson counts.
        $courses = Course::with('lessons')->get();

        // Section: Resolve the currently selected course and lesson.
        $selectedCourse = null;
        $selectedLesson = null;

        if ($request->has('course_id')) {
            $selectedCourse = Course::with('lessons')->find($request->course_id);
        }

        if ($request->has('lesson_id')) {
            $selectedLesson = Lesson::with(['course', 'quizzes.questions'])->find($request->lesson_id);
        }

        return view('student.home', compact('courses', 'selectedCourse', 'selectedLesson'));
    }
}
