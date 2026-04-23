@extends('layouts.app')

@section('title', 'Student Home')

@section('content')
@php
    $selectedLessonCount = $selectedCourse ? $selectedCourse->lessons->count() : 0;
@endphp

<div class="row reveal-up">
    <div class="col-12">
        <h2>Welcome, {{ auth()->user()->name }}!</h2>
        <p class="text-muted">Select a course to view lessons and take quizzes</p>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card section-card reveal-up">
            <div class="card-body">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                    <div>
                        <h5 class="mb-1">Browse Learning Content</h5>
                        <p class="text-muted mb-0">Filter through the available courses and lessons as you study.</p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge-soft">Courses: {{ $courses->count() }}</span>
                        @if($selectedCourse)
                            <span class="badge-soft">Lessons: {{ $selectedLessonCount }}</span>
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">Clear Selection</a>
                        @endif
                    </div>
                </div>

                <div class="search-wrap mb-4">
                    <span class="search-label">Find</span>
                    <input
                        type="text"
                        class="form-control soft-search"
                        placeholder="Search courses or lessons"
                        data-filter-input="#student-learning-browser"
                        data-empty-target="#student-filter-empty"
                    >
                </div>

                <div id="student-learning-browser">
                    <h5>Courses</h5>
                    <div class="horizontal-scroll">
                        @forelse($courses as $course)
                            <a
                                href="?course_id={{ $course->id }}"
                                class="btn {{ request('course_id') == $course->id ? 'btn-primary' : 'btn-outline-primary' }}"
                                data-filter-item
                                data-filter-text="{{ strtolower($course->title) }}"
                            >
                                {{ $course->title }}
                            </a>
                        @empty
                            <p class="text-muted">No courses available yet.</p>
                        @endforelse
                    </div>

                    @if($selectedCourse)
                    <div class="mt-4">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                            <h5 class="mb-0">Lessons in {{ $selectedCourse->title }}</h5>
                            <span class="text-muted small">{{ $selectedLessonCount }} available</span>
                        </div>
                        <div class="horizontal-scroll">
                            @forelse($selectedCourse->lessons as $lesson)
                                <a
                                    href="?course_id={{ $selectedCourse->id }}&lesson_id={{ $lesson->id }}"
                                    class="btn {{ request('lesson_id') == $lesson->id ? 'btn-success' : 'btn-outline-success' }}"
                                    data-filter-item
                                    data-filter-text="{{ strtolower($selectedCourse->title . ' ' . $lesson->title) }}"
                                >
                                    {{ $lesson->title }}
                                </a>
                            @empty
                                <p class="text-muted">No lessons available for this course.</p>
                            @endforelse
                        </div>
                    </div>
                    @endif
                </div>

                <div id="student-filter-empty" class="filter-empty d-none mt-4 p-4 text-center text-muted">
                    No courses or lessons match your search.
                </div>
            </div>
        </div>
    </div>
</div>

@if($selectedLesson)
<div class="row mt-4">
    <div class="col-12">
        <div class="card section-card reveal-up">
            <div class="card-header bg-success text-white">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h4 class="mb-0">{{ $selectedLesson->title }}</h4>
                    @if($selectedCourse)
                        <span class="badge bg-light text-success">{{ $selectedCourse->title }}</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                @if($selectedLesson->content)
                    <div class="mb-4">
                        <h5>Lesson Content</h5>
                        <p>{!! nl2br(e($selectedLesson->content)) !!}</p>
                    </div>
                @endif

                @if($selectedLesson->media_path)
                    <div class="mb-4">
                        <h5>Lesson Media</h5>
                        <div class="media-container">
                            @php
                                $extension = strtolower(pathinfo($selectedLesson->media_path, PATHINFO_EXTENSION));
                            @endphp
                            
                            @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                <img src="{{ asset($selectedLesson->media_path) }}" alt="Lesson media" class="img-fluid">
                            @elseif($extension === 'mp4')
                                <video controls preload="metadata">
                                    <source src="{{ asset($selectedLesson->media_path) }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            @elseif($extension === 'pdf')
                                <div class="p-4 rounded-4 bg-light border">
                                    <p class="mb-3">A PDF resource is attached to this lesson and can be opened in a new tab.</p>
                                    <a href="{{ asset($selectedLesson->media_path) }}" class="btn btn-outline-primary" target="_blank" rel="noopener">
                                        Open PDF
                                    </a>
                                </div>
                            @endif
                            
                            <div class="mt-3 d-flex flex-wrap gap-2">
                                <a href="{{ asset($selectedLesson->media_path) }}" 
                                   class="btn btn-primary" 
                                   download>
                                    Download Media
                                </a>
                                @if(in_array($extension, ['pdf', 'mp4']))
                                    <a href="{{ asset($selectedLesson->media_path) }}" class="btn btn-outline-secondary" target="_blank" rel="noopener">
                                        Open Media
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                @if($selectedLesson->quizzes->count() > 0)
                    @foreach($selectedLesson->quizzes as $quiz)
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                                <h5 class="mb-0">Quiz: {{ $quiz->title }}</h5>
                                <span class="badge-soft">Questions: {{ $quiz->questions->count() }}</span>
                            </div>
                            
                            @if($quiz->questions->count() > 0)
                                <form method="POST" action="{{ route('quiz.submit', $quiz) }}">
                                    @csrf
                                    
                                    @foreach($quiz->questions as $index => $question)
                                        <div class="card mb-3 interactive-card quiz-question-card">
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
                    <p class="text-muted mb-0">No quiz available for this lesson yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

@if(!$selectedCourse)
<div class="row mt-4">
    <div class="col-12">
        <div class="alert alert-info reveal-up">
            Select a course above to get started.
        </div>
    </div>
</div>
@endif
@endsection
