<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\QuizResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Show admin dashboard
     */
    public function index()
    {
        $totalStudents = User::where('role', 'student')->count();
        $totalQuizResults = QuizResult::count();

        return view('admin.dashboard', compact('totalStudents', 'totalQuizResults'));
    }

    /**
     * Show all students
     */
    public function students()
    {
        $students = User::where('role', 'student')->orderByDesc('id')->get();

        return view('admin.students.index', compact('students'));
    }

    /**
     * Show edit student form
     */
    public function editStudent(User $student)
    {
        return view('admin.students.edit', compact('student'));
    }

    /**
     * Update student details
     */
    public function updateStudent(Request $request, User $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->id,
        ]);

        $student->update($validated);

        return redirect()->route('admin.students')->with('success', 'Student updated successfully!');
    }

    /**
     * Delete student
     */
    public function deleteStudent(User $student)
    {
        $student->delete();

        return redirect()->route('admin.students')->with('success', 'Student deleted successfully!');
    }

    /**
     * Reset student password
     */
    public function resetPassword(Request $request, User $student)
    {
        $validated = $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $student->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password reset successfully!');
    }

    /**
     * View all quiz results
     */
    public function quizResults(Request $request)
    {
        $query = QuizResult::with(['user', 'quiz.lesson.course']);

        // Filter by student if provided
        if ($request->has('student_id') && $request->student_id) {
            $query->where('user_id', $request->student_id);
        }

        // Filter by course if provided
        if ($request->has('course_id') && $request->course_id) {
            $query->whereHas('quiz.lesson', function($q) use ($request) {
                $q->where('course_id', $request->course_id);
            });
        }

        $results = $query->orderByDesc('id')->get();
        $students = User::where('role', 'student')->get();
        $courses = \App\Models\Course::all();

        return view('admin.quiz-results', compact('results', 'students', 'courses'));
    }
}
