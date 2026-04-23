<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Section: Show the student homepage with the course browser only.
     */
    public function index()
    {
        // Section: Load the course browser with lesson counts.
        $courses = Course::with('lessons')->get();

        return view('student.home', compact('courses'));
    }

    /**
     * Section: Show a dedicated course page with its lessons and selected lesson detail.
     */
    public function showCourse(Request $request, Course $course)
    {
        // Section: Load the selected course and its lesson list.
        $course->load([
            'lessons' => fn ($query) => $query->orderBy('id'),
        ]);

        // Section: Resolve the selected lesson or fall back to the first lesson in the course.
        $selectedLesson = null;

        if ($request->filled('lesson_id')) {
            $selectedLesson = $course->lessons()
                ->with(['course', 'quizzes.questions'])
                ->find($request->lesson_id);
        }

        if (!$selectedLesson) {
            $selectedLesson = $course->lessons()
                ->with(['course', 'quizzes.questions'])
                ->orderBy('id')
                ->first();
        }

        return view('student.course', [
            'course' => $course,
            'selectedLesson' => $selectedLesson,
        ]);
    }
}
