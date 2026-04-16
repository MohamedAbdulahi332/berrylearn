<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LessonController extends Controller
{
    /**
     * Show all lessons
     */
    public function index()
    {
        $lessons = Lesson::with('course')->latest()->get();

        return view('admin.lessons.index', compact('lessons'));
    }

    /**
     * Show create lesson form
     */
    public function create()
    {
        $courses = Course::all();

        return view('admin.lessons.create', compact('courses'));
    }

    /**
     * Store new lesson with media upload
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'media' => 'nullable|file|mimes:jpg,jpeg,png,pdf,mp4,doc,docx|max:10240', // 10MB max
        ]);

        $mediaPath = null;

        // Handle file upload
        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('upload'), $filename);
            $mediaPath = 'upload/' . $filename;
        }

        Lesson::create([
            'course_id' => $validated['course_id'],
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'media_path' => $mediaPath,
        ]);

        return redirect()->route('admin.lessons')->with('success', 'Lesson created successfully!');
    }

    /**
     * Show edit lesson form
     */
    public function edit(Lesson $lesson)
    {
        $courses = Course::all();

        return view('admin.lessons.edit', compact('lesson', 'courses'));
    }

    /**
     * Update lesson
     */
    public function update(Request $request, Lesson $lesson)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'media' => 'nullable|file|mimes:jpg,jpeg,png,pdf,mp4,doc,docx|max:10240',
        ]);

        $mediaPath = $lesson->media_path;

        // Handle new file upload
        if ($request->hasFile('media')) {
            // Delete old file if exists
            if ($lesson->media_path && file_exists(public_path($lesson->media_path))) {
                unlink(public_path($lesson->media_path));
            }

            $file = $request->file('media');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('upload'), $filename);
            $mediaPath = 'upload/' . $filename;
        }

        $lesson->update([
            'course_id' => $validated['course_id'],
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'media_path' => $mediaPath,
        ]);

        return redirect()->route('admin.lessons')->with('success', 'Lesson updated successfully!');
    }

    /**
     * Delete lesson
     */
    public function destroy(Lesson $lesson)
    {
        // Delete associated media file
        if ($lesson->media_path && file_exists(public_path($lesson->media_path))) {
            unlink(public_path($lesson->media_path));
        }

        $lesson->delete();

        return redirect()->route('admin.lessons')->with('success', 'Lesson deleted successfully!');
    }
}
