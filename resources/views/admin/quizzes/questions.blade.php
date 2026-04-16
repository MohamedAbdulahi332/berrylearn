@extends('layouts.app')
@section('content')
<h2>Manage Questions for: {{ $quiz->title }}</h2>
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h5>Add New Question</h5></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.quizzes.questions.add', $quiz) }}">
                    @csrf
                    <div class="mb-3">
                        <label>Question</label>
                        <textarea name="question_text" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-2"><label>Option A</label><input type="text" name="option_a" class="form-control" required></div>
                    <div class="mb-2"><label>Option B</label><input type="text" name="option_b" class="form-control" required></div>
                    <div class="mb-2"><label>Option C</label><input type="text" name="option_c" class="form-control" required></div>
                    <div class="mb-2"><label>Option D</label><input type="text" name="option_d" class="form-control" required></div>
                    <div class="mb-3">
                        <label>Correct Answer</label>
                        <select name="correct_answer" class="form-control" required>
                            <option value="a">A</option><option value="b">B</option><option value="c">C</option><option value="d">D</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">Add Question</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h5>Existing Questions ({{ $quiz->questions->count() }})</h5></div>
            <div class="card-body" style="max-height: 600px; overflow-y: auto;">
                @forelse($quiz->questions as $index => $question)
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h6>Q{{ $index + 1 }}: {{ $question->question_text }}</h6>
                                <form method="POST" action="{{ route('admin.questions.delete', $question) }}" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</button>
                                </form>
                            </div>
                            <small>
                                <div>A: {{ $question->option_a }}</div>
                                <div>B: {{ $question->option_b }}</div>
                                <div>C: {{ $question->option_c }}</div>
                                <div>D: {{ $question->option_d }}</div>
                                <div class="text-success"><strong>Correct: {{ strtoupper($question->correct_answer) }}</strong></div>
                            </small>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">No questions yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
<a href="{{ route('admin.quizzes') }}" class="btn btn-secondary mt-3">Back to Quizzes</a>
@endsection
