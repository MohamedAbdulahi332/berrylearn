@extends('layouts.app')

@section('title', 'Quiz Result')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h4>Quiz Completed! 🎉</h4>
            </div>
            <div class="card-body text-center">
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

                <div class="mt-4">
                    <a href="{{ route('home') }}" class="btn btn-primary">Back to Home</a>
                    <a href="{{ route('profile') }}" class="btn btn-secondary">View All Results</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
