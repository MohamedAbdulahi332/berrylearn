@extends('layouts.app')
@section('content')
<h2>Create New Quiz</h2>
<div class="card mt-4">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.quizzes.store') }}">
            @csrf
            <div class="mb-3">
                <label>Lesson</label>
                <select name="lesson_id" class="form-control" required>
                    <option value="">Select Lesson</option>
                    @foreach($lessons as $lesson)
                        <option value="{{ $lesson->id }}">{{ $lesson->course->title }} - {{ $lesson->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Quiz Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Create Quiz & Add Questions</button>
            <a href="{{ route('admin.quizzes') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
