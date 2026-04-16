@extends('layouts.app')

@section('title', 'Quiz Result')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h4>Quiz Completed! 🎉</h4>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <h2>{{ $quiz->title }}</h2>
                
                    <div class="my-4">
                        <h1 class="display-1">{{ $percentage }}%</h1>
                        <p class="lead">You scored {{ $score }} out of {{ $total }}</p>
                    </div>

                    @if($percentage >= 80)
                        <div class="alert alert-success">
                            <strong>Excellent work!</strong> You've mastered this material.
                        </div>
                    @elseif($percentage >= 60)
                        <div class="alert alert-info">
                            <strong>Good job!</strong> You're getting there.
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <strong>Keep studying!</strong> Review the lesson and try again.
                        </div>
                    @endif
                </div>

                <hr class="my-4">

                <div>
                    <h4 class="mb-3">Answer Review</h4>

                    @foreach($answerReview as $index => $answer)
                        <div class="card mb-3 border-{{ $answer['is_correct'] ? 'success' : 'danger' }}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start gap-3">
                                    <div>
                                        <h6 class="mb-2">Question {{ $index + 1 }}</h6>
                                        <p class="mb-3">{{ $answer['question_text'] }}</p>
                                    </div>
                                    <span class="badge bg-{{ $answer['is_correct'] ? 'success' : 'danger' }}">
                                        {{ $answer['is_correct'] ? 'Correct' : 'Incorrect' }}
                                    </span>
                                </div>

                                <p class="mb-2">
                                    <strong>Your answer:</strong>
                                    @if($answer['user_answer'])
                                        {{ strtoupper($answer['user_answer']) }}. {{ $answer['user_answer_text'] }}
                                    @else
                                        <span class="text-muted">No answer selected</span>
                                    @endif
                                </p>

                                <p class="mb-0">
                                    <strong>Correct answer:</strong>
                                    {{ strtoupper($answer['correct_answer']) }}. {{ $answer['correct_answer_text'] }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 text-center">
                    <a href="{{ route('home') }}" class="btn btn-primary">Back to Home</a>
                    <a href="{{ route('profile') }}" class="btn btn-secondary">View All Results</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
