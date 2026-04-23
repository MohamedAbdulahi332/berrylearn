<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Show all courses
     */
    public function index()
    {
        $courses = Course::with('lessons')->orderByDesc('id')->get();

        return view('admin.courses.index', compact('courses'));
    }

    /**
     * Show create course form
     */
    public function create()
    {
        return view('admin.courses.create');
    }

    /**
     * Store new course
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Course::create($validated);

        return redirect()->route('admin.courses')->with('success', 'Course created successfully!');
    }

    /**
     * Show edit course form
     */
    public function edit(Course $course)
    {
        return view('admin.courses.edit', compact('course'));
    }

    /**
     * Update course
     */
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $course->update($validated);

        return redirect()->route('admin.courses')->with('success', 'Course updated successfully!');
    }

    /**
     * Delete course
     */
    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('admin.courses')->with('success', 'Course deleted successfully!');
    }
}
