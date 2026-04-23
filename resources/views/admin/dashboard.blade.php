@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
@php
    $totalCourses = \App\Models\Course::count();
    $totalLessons = \App\Models\Lesson::count();
@endphp

<div class="row reveal-up">
    <div class="col-12">
        <h2>Admin Dashboard</h2>
        <p class="text-muted">Manage your Learning Management System</p>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-3 mb-4">
        <div class="card stat-card interactive-card bg-primary text-white reveal-up">
            <div class="card-body">
                <p class="stat-meta">Student Activity</p>
                <h3 class="stat-value" data-countup="{{ $totalStudents }}">{{ $totalStudents }}</h3>
                <p class="mb-0">Track enrolled learners and keep the classroom roster up to date.</p>
                <a href="{{ route('admin.students') }}" class="btn btn-light btn-sm">Manage Students</a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card stat-card interactive-card bg-success text-white reveal-up">
            <div class="card-body">
                <p class="stat-meta">Course Library</p>
                <h3 class="stat-value" data-countup="{{ $totalCourses }}">{{ $totalCourses }}</h3>
                <p class="mb-0">Build and organize your learning paths from one place.</p>
                <a href="{{ route('admin.courses') }}" class="btn btn-light btn-sm">Manage Courses</a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card stat-card interactive-card bg-info text-white reveal-up">
            <div class="card-body">
                <p class="stat-meta">Lesson Content</p>
                <h3 class="stat-value" data-countup="{{ $totalLessons }}">{{ $totalLessons }}</h3>
                <p class="mb-0">Keep lessons moving with media, content, and quiz-ready materials.</p>
                <a href="{{ route('admin.lessons') }}" class="btn btn-light btn-sm">Manage Lessons</a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card stat-card interactive-card bg-warning text-white reveal-up">
            <div class="card-body">
                <p class="stat-meta">Assessment Pulse</p>
                <h3 class="stat-value" data-countup="{{ $totalQuizResults }}">{{ $totalQuizResults }}</h3>
                <p class="mb-0">Review attempts quickly and spot learning momentum across the platform.</p>
                <a href="{{ route('admin.quiz-results') }}" class="btn btn-light btn-sm">View Results</a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card section-card reveal-up">
            <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h5 class="mb-1">Quick Actions</h5>
                    <p class="text-muted mb-0">Open your most-used admin workflows without leaving the dashboard.</p>
                </div>
                <button class="btn btn-outline-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#quickActionsPanel" aria-expanded="true" aria-controls="quickActionsPanel">
                    Toggle Actions
                </button>
            </div>
            <div id="quickActionsPanel" class="collapse show">
                <div class="card-body d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.courses.create') }}" class="btn btn-primary action-chip">Create Course</a>
                    <a href="{{ route('admin.lessons.create') }}" class="btn btn-success action-chip">Create Lesson</a>
                    <a href="{{ route('admin.quizzes.create') }}" class="btn btn-info action-chip">Create Quiz</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
