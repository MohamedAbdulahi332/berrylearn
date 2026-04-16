<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Http\Request;

class AdminQuizController extends Controller
{
    /**
     * Show all quizzes
     */
    public function index()
    {
        $quizzes = Quiz::with('lesson.course')->latest()->get();

        return view('admin.quizzes.index', compact('quizzes'));
    }

    /**
     * Show create quiz form
     */
    public function create()
    {
        $lessons = Lesson::with('course')->get();

        return view('admin.quizzes.create', compact('lessons'));
    }

    /**
     * Store new quiz
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'title' => 'required|string|max:255',
        ]);

        $quiz = Quiz::create($validated);

        return redirect()->route('admin.quizzes.questions', $quiz)->with('success', 'Quiz created! Now add questions.');
    }

    /**
     * Show quiz questions management
     */
    public function questions(Quiz $quiz)
    {
        $quiz->load('questions');

        return view('admin.quizzes.questions', compact('quiz'));
    }

    /**
     * Add question to quiz
     */
    public function addQuestion(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'question_text' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'correct_answer' => 'required|in:a,b,c,d',
        ]);

        $quiz->questions()->create($validated);

        return back()->with('success', 'Question added successfully!');
    }

    /**
     * Delete question
     */
    public function deleteQuestion(Question $question)
    {
        $quizId = $question->quiz_id;
        $question->delete();

        return back()->with('success', 'Question deleted successfully!');
    }

    /**
     * Edit quiz
     */
    public function edit(Quiz $quiz)
    {
        $lessons = Lesson::with('course')->get();

        return view('admin.quizzes.edit', compact('quiz', 'lessons'));
    }

    /**
     * Update quiz
     */
    public function update(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'title' => 'required|string|max:255',
        ]);

        $quiz->update($validated);

        return redirect()->route('admin.quizzes')->with('success', 'Quiz updated successfully!');
    }

    /**
     * Delete quiz
     */
    public function destroy(Quiz $quiz)
    {
        $quiz->delete();

        return redirect()->route('admin.quizzes')->with('success', 'Quiz deleted successfully!');
    }
}
