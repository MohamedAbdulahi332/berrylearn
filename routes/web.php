<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\AdminQuizController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root to login
Route::get('/', function () {
    return redirect('/login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Student Routes (Protected by auth + role:student middleware)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:student'])->group(function () {
    // Section: Student homepage.
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Section: Student course detail page.
    Route::get('/courses/{course}', [HomeController::class, 'showCourse'])->name('student.courses.show');

    // Section: Student profile.
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Section: Student quiz submission.
    Route::post('/quiz/{quiz}/submit', [QuizController::class, 'submit'])->name('quiz.submit');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Protected by auth + role:admin middleware)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    // Admin Dashboard
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');

    // Student Management
    Route::get('/students', [AdminController::class, 'students'])->name('admin.students');
    Route::get('/students/{student}/edit', [AdminController::class, 'editStudent'])->name('admin.students.edit');
    Route::post('/students/{student}', [AdminController::class, 'updateStudent'])->name('admin.students.update');
    Route::delete('/students/{student}', [AdminController::class, 'deleteStudent'])->name('admin.students.delete');
    Route::post('/students/{student}/reset-password', [AdminController::class, 'resetPassword'])->name('admin.students.reset');

    // Course Management
    Route::get('/courses', [CourseController::class, 'index'])->name('admin.courses');
    Route::get('/courses/create', [CourseController::class, 'create'])->name('admin.courses.create');
    Route::post('/courses', [CourseController::class, 'store'])->name('admin.courses.store');
    Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('admin.courses.edit');
    Route::post('/courses/{course}', [CourseController::class, 'update'])->name('admin.courses.update');
    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('admin.courses.delete');

    // Lesson Management
    Route::get('/lessons', [LessonController::class, 'index'])->name('admin.lessons');
    Route::get('/lessons/create', [LessonController::class, 'create'])->name('admin.lessons.create');
    Route::post('/lessons', [LessonController::class, 'store'])->name('admin.lessons.store');
    Route::get('/lessons/{lesson}/edit', [LessonController::class, 'edit'])->name('admin.lessons.edit');
    Route::post('/lessons/{lesson}', [LessonController::class, 'update'])->name('admin.lessons.update');
    Route::delete('/lessons/{lesson}', [LessonController::class, 'destroy'])->name('admin.lessons.delete');

    // Quiz Management
    Route::get('/quizzes', [AdminQuizController::class, 'index'])->name('admin.quizzes');
    Route::get('/quizzes/create', [AdminQuizController::class, 'create'])->name('admin.quizzes.create');
    Route::post('/quizzes', [AdminQuizController::class, 'store'])->name('admin.quizzes.store');
    Route::get('/quizzes/{quiz}/edit', [AdminQuizController::class, 'edit'])->name('admin.quizzes.edit');
    Route::post('/quizzes/{quiz}', [AdminQuizController::class, 'update'])->name('admin.quizzes.update');
    Route::delete('/quizzes/{quiz}', [AdminQuizController::class, 'destroy'])->name('admin.quizzes.delete');

    // Quiz Questions Management
    Route::get('/quizzes/{quiz}/questions', [AdminQuizController::class, 'questions'])->name('admin.quizzes.questions');
    Route::post('/quizzes/{quiz}/questions', [AdminQuizController::class, 'addQuestion'])->name('admin.quizzes.questions.add');
    Route::delete('/questions/{question}', [AdminQuizController::class, 'deleteQuestion'])->name('admin.questions.delete');

    // Quiz Results
    Route::get('/quiz-results', [AdminController::class, 'quizResults'])->name('admin.quiz-results');
});
