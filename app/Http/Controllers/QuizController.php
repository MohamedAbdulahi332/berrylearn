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
        $answerReview = [];

        foreach ($questions as $question) {
            $userAnswer = $request->input('question_' . $question->id);
            $correctAnswer = $question->correct_answer;
            $isCorrect = $userAnswer === $correctAnswer;

            if ($isCorrect) {
                $score++;
            }

            $answerReview[] = [
                'question_text' => $question->question_text,
                'user_answer' => $userAnswer,
                'user_answer_text' => $userAnswer ? $question->{'option_' . $userAnswer} : null,
                'correct_answer' => $correctAnswer,
                'correct_answer_text' => $question->{'option_' . $correctAnswer},
                'is_correct' => $isCorrect,
            ];
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
            'answerReview' => $answerReview,
        ]);
    }
}
