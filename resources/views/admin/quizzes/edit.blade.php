@extends('layouts.app')
@section('content')
<h2>Edit Quiz</h2>
<div class="card mt-4">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.quizzes.update', $quiz) }}">
            @csrf
            <div class="mb-3">
                <label>Lesson</label>
                <select name="lesson_id" class="form-control" required>
                    @foreach($lessons as $lesson)
                        <option value="{{ $lesson->id }}" {{ $quiz->lesson_id == $lesson->id ? 'selected' : '' }}>
                            {{ $lesson->course->title }} - {{ $lesson->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Quiz Title</label>
                <input type="text" name="title" class="form-control" value="{{ $quiz->title }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Quiz</button>
            <a href="{{ route('admin.quizzes') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
