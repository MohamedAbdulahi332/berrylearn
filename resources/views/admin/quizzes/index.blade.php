@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between mb-4">
    <h2>Manage Quizzes</h2>
    <a href="{{ route('admin.quizzes.create') }}" class="btn btn-success">Create Quiz</a>
</div>
<table class="table">
    <tr><th>ID</th><th>Title</th><th>Lesson</th><th>Course</th><th>Questions</th><th>Actions</th></tr>
    @forelse($quizzes as $quiz)
        <tr>
            <td>{{ $quiz->id }}</td>
            <td>{{ $quiz->title }}</td>
            <td>{{ optional($quiz->lesson)->title ?? 'Missing lesson' }}</td>
            <td>{{ optional(optional($quiz->lesson)->course)->title ?? 'Missing course' }}</td>
            <td>{{ $quiz->questions->count() }}</td>
            <td>
                <a href="{{ route('admin.quizzes.questions', $quiz) }}" class="btn btn-sm btn-info">Questions</a>
                <a href="{{ route('admin.quizzes.edit', $quiz) }}" class="btn btn-sm btn-primary">Edit</a>
                <form method="POST" action="{{ route('admin.quizzes.delete', $quiz) }}" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</button>
                </form>
            </td>
        </tr>
    @empty
        <tr><td colspan="6" class="text-center text-muted">No quizzes found.</td></tr>
    @endforelse
</table>
@endsection
