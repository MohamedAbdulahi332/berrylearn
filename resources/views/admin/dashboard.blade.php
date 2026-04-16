@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <h2>Admin Dashboard</h2>
        <p class="text-muted">Manage your Learning Management System</p>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h3>{{ $totalStudents }}</h3>
                <p>Total Students</p>
                <a href="{{ route('admin.students') }}" class="btn btn-light btn-sm">Manage Students</a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h3>{{ \App\Models\Course::count() }}</h3>
                <p>Total Courses</p>
                <a href="{{ route('admin.courses') }}" class="btn btn-light btn-sm">Manage Courses</a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h3>{{ \App\Models\Lesson::count() }}</h3>
                <p>Total Lessons</p>
                <a href="{{ route('admin.lessons') }}" class="btn btn-light btn-sm">Manage Lessons</a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h3>{{ $totalQuizResults }}</h3>
                <p>Quiz Attempts</p>
                <a href="{{ route('admin.quiz-results') }}" class="btn btn-light btn-sm">View Results</a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>Quick Actions</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">Create Course</a>
                <a href="{{ route('admin.lessons.create') }}" class="btn btn-success">Create Lesson</a>
                <a href="{{ route('admin.quizzes.create') }}" class="btn btn-info">Create Quiz</a>
            </div>
        </div>
    </div>
</div>
@endsection
