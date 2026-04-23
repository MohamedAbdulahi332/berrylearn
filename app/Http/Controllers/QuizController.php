<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    /**
     * Submit quiz answers and calculate score
     */
    public function submit(Request $request, Quiz $quiz)
    {
        // Get all questions for this quiz
        $questions = $quiz->questions;

        // Calculate score
        $score = 0;
        $total = $questions->count();

        foreach ($questions as $question) {
            $userAnswer = $request->input('question_' . $question->id);

            if ($userAnswer === $question->correct_answer) {
                $score++;
            }
        }

        // Store result in database
        $quizResult = QuizResult::create([
            'user_id' => Auth::id(),
            'quiz_id' => $quiz->id,
            'score' => $score,
            'total' => $total,
        ]);

        // Return result view with score
        return view('student.quiz-result', [
            'quiz' => $quiz,
            'score' => $score,
            'total' => $total,
            'percentage' => $quizResult->percentage,
        ]);
    }
}
