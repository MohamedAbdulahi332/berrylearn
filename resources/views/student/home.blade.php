@extends('layouts.app')

@section('title', 'Student Home')

@section('content')
<div class="row">
    <div class="col-12">
        <h2>Welcome, {{ auth()->user()->name }}!</h2>
        <p class="text-muted">Select a course to view lessons and take quizzes</p>
    </div>
</div>

<!-- Horizontally Scrollable Course List -->
<div class="row mt-4">
    <div class="col-12">
        <h5>Courses</h5>
        <div class="horizontal-scroll">
            @forelse($courses as $course)
                <a href="?course_id={{ $course->id }}" 
                   class="btn {{ request('course_id') == $course->id ? 'btn-primary' : 'btn-outline-primary' }}">
                    {{ $course->title }}
                </a>
            @empty
                <p class="text-muted">No courses available yet.</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Horizontally Scrollable Lesson List (shown when course is selected) -->
@if($selectedCourse)
<div class="row mt-4">
    <div class="col-12">
        <h5>Lessons in {{ $selectedCourse->title }}</h5>
        <div class="horizontal-scroll">
            @forelse($selectedCourse->lessons as $lesson)
                <a href="?course_id={{ $selectedCourse->id }}&lesson_id={{ $lesson->id }}" 
                   class="btn {{ request('lesson_id') == $lesson->id ? 'btn-success' : 'btn-outline-success' }}">
                    {{ $lesson->title }}
                </a>
            @empty
                <p class="text-muted">No lessons available for this course.</p>
            @endforelse
        </div>
    </div>
</div>
@endif

<!-- Lesson Content (shown when lesson is selected) -->
@if($selectedLesson)
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h4>{{ $selectedLesson->title }}</h4>
            </div>
            <div class="card-body">
                <!-- Lesson Content -->
                @if($selectedLesson->content)
                    <div class="mb-4">
                        <h5>Lesson Content</h5>
                        <p>{!! nl2br(e($selectedLesson->content)) !!}</p>
                    </div>
                @endif

                <!-- Downloadable Media -->
                @if($selectedLesson->media_path)
                    <div class="mb-4">
                        <h5>Downloadable Media</h5>
                        <div class="media-container">
                            @php
                                $extension = pathinfo($selectedLesson->media_path, PATHINFO_EXTENSION);
                            @endphp
                            
                            @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                <img src="{{ asset($selectedLesson->media_path) }}" alt="Lesson media" class="img-fluid">
                            @endif
                            
                            <div class="mt-2">
                                <a href="{{ asset($selectedLesson->media_path) }}" 
                                   class="btn btn-primary" 
                                   download>
                                    📥 Download Media
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Quiz Section -->
                @if($selectedLesson->quizzes->count() > 0)
                    @foreach($selectedLesson->quizzes as $quiz)
                        <div class="mb-4">
                            <h5>📝 Quiz: {{ $quiz->title }}</h5>
                            
                            @if($quiz->questions->count() > 0)
                                <form method="POST" action="{{ route('quiz.submit', $quiz) }}">
                                    @csrf
                                    
                                    @foreach($quiz->questions as $index => $question)
                                        <div class="card mb-3">
                                            <div class="card-body">
                                                <h6>Question {{ $index + 1 }}</h6>
                                                <p>{{ $question->question_text }}</p>
                                                
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" 
                                                           name="question_{{ $question->id }}" 
                                                           value="a" 
                                                           id="q{{ $question->id }}_a" required>
                                                    <label class="form-check-label" for="q{{ $question->id }}_a">
                                                        A. {{ $question->option_a }}
                                                    </label>
                                                </div>
                                                
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" 
                                                           name="question_{{ $question->id }}" 
                                                           value="b" 
                                                           id="q{{ $question->id }}_b">
                                                    <label class="form-check-label" for="q{{ $question->id }}_b">
                                                        B. {{ $question->option_b }}
                                                    </label>
                                                </div>
                                                
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" 
                                                           name="question_{{ $question->id }}" 
                                                           value="c" 
                                                           id="q{{ $question->id }}_c">
                                                    <label class="form-check-label" for="q{{ $question->id }}_c">
                                                        C. {{ $question->option_c }}
                                                    </label>
                                                </div>
                                                
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" 
                                                           name="question_{{ $question->id }}" 
                                                           value="d" 
                                                           id="q{{ $question->id }}_d">
                                                    <label class="form-check-label" for="q{{ $question->id }}_d">
                                                        D. {{ $question->option_d }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    
                                    <button type="submit" class="btn btn-success">Submit Quiz</button>
                                </form>
                            @else
                                <p class="text-muted">No questions available for this quiz yet.</p>
                            @endif
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">No quiz available for this lesson yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

@if(!$selectedCourse)
<div class="row mt-4">
    <div class="col-12">
        <div class="alert alert-info">
            👆 Select a course above to get started!
        </div>
    </div>
</div>
@endif
@endsection
