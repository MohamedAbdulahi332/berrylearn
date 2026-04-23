<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    /**
     * Section: Show all lessons in the admin area.
     */
    public function index()
    {
        $lessons = Lesson::with('course')->latest()->get();

        return view('admin.lessons.index', compact('lessons'));
    }

    /**
     * Section: Show the lesson creation form.
     */
    public function create()
    {
        $courses = Course::all();

        return view('admin.lessons.create', compact('courses'));
    }

    /**
     * Section: Store a new lesson with optional video and PDF uploads.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'video' => 'nullable|file|mimes:mp4|max:51200',
            'pdf' => 'nullable|file|mimes:pdf|max:20480',
        ]);

        // Section: Persist the lesson record with learning materials.
        Lesson::create([
            'course_id' => $validated['course_id'],
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'media_path' => null,
            'video_path' => $request->hasFile('video') ? $this->storeLearningFile($request->file('video'), 'video') : null,
            'pdf_path' => $request->hasFile('pdf') ? $this->storeLearningFile($request->file('pdf'), 'pdf') : null,
        ]);

        return redirect()->route('admin.lessons')->with('success', 'Lesson created successfully!');
    }

    /**
     * Section: Show the lesson editing form.
     */
    public function edit(Lesson $lesson)
    {
        $courses = Course::all();

        return view('admin.lessons.edit', compact('lesson', 'courses'));
    }

    /**
     * Section: Update the lesson details and replace uploaded materials if needed.
     */
    public function update(Request $request, Lesson $lesson)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'video' => 'nullable|file|mimes:mp4|max:51200',
            'pdf' => 'nullable|file|mimes:pdf|max:20480',
        ]);

        // Section: Replace the current lesson video only when a new one is uploaded.
        $videoPath = $lesson->video_path;
        if ($request->hasFile('video')) {
            $this->deleteLearningFile($lesson->video_path);
            $videoPath = $this->storeLearningFile($request->file('video'), 'video');
        }

        // Section: Replace the current lesson PDF only when a new one is uploaded.
        $pdfPath = $lesson->pdf_path;
        if ($request->hasFile('pdf')) {
            $this->deleteLearningFile($lesson->pdf_path);
            $pdfPath = $this->storeLearningFile($request->file('pdf'), 'pdf');
        }

        // Section: Save the updated lesson record.
        $lesson->update([
            'course_id' => $validated['course_id'],
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'video_path' => $videoPath,
            'pdf_path' => $pdfPath,
        ]);

        return redirect()->route('admin.lessons')->with('success', 'Lesson updated successfully!');
    }

    /**
     * Section: Delete a lesson and remove its uploaded learning materials.
     */
    public function destroy(Lesson $lesson)
    {
        $this->deleteLearningFile($lesson->video_path);
        $this->deleteLearningFile($lesson->pdf_path);
        $this->deleteLearningFile($lesson->media_path);

        $lesson->delete();

        return redirect()->route('admin.lessons')->with('success', 'Lesson deleted successfully!');
    }

    /**
     * Section: Store a lesson learning file inside the public upload directory.
     */
    private function storeLearningFile($file, string $prefix): string
    {
        $uploadDirectory = public_path('upload');

        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0755, true);
        }

        $filename = time() . '_' . $prefix . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
        $file->move($uploadDirectory, $filename);

        return 'upload/' . $filename;
    }

    /**
     * Section: Remove a stored lesson learning file when it exists.
     */
    private function deleteLearningFile(?string $path): void
    {
        if ($path && file_exists(public_path($path))) {
            unlink(public_path($path));
        }
    }
}
