@extends('layouts.app')

@section('title', $course->title)

@section('content')
{{-- Section: Selected course metrics and lesson resources. --}}
@php
    $selectedVideoPath = $selectedLesson ? $selectedLesson->resolvedVideoPath() : null;
    $selectedPdfPath = $selectedLesson ? $selectedLesson->resolvedPdfPath() : null;
@endphp

{{-- Section: Course page heading and navigation actions. --}}
<div class="row reveal-up">
    <div class="col-12">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
            <div>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm mb-3">Back to Courses</a>
                <h2 class="mb-1">{{ $course->title }}</h2>
                <p class="text-muted mb-0">
                    {{ $course->description ?: 'Open any lesson below to study the course materials and complete the quiz.' }}
                </p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <span class="badge-soft">Lessons: {{ $course->lessons->count() }}</span>
                @if($selectedLesson)
                    <span class="badge-soft">Current Lesson: {{ $selectedLesson->title }}</span>
                @endif
                <a
                    href="{{ $course->youtubeSearchUrl() }}"
                    class="btn btn-outline-secondary btn-sm"
                    target="_blank"
                    rel="noopener"
                >
                    Search YouTube
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Section: Course lesson selector. --}}
<div class="row mt-4">
    <div class="col-12">
        <div class="card section-card reveal-up">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <div>
                        <h5 class="mb-1">Course Lessons</h5>
                        <p class="text-muted mb-0">Select a lesson to open its materials and quiz on this page.</p>
                    </div>
                    <a href="{{ route('home') }}" class="btn btn-sm btn-outline-primary">Open Another Course</a>
                </div>

                <div class="horizontal-scroll lesson-chip-grid">
                    @forelse($course->lessons as $lesson)
                        <a
                            href="{{ route('student.courses.show', ['course' => $course, 'lesson_id' => $lesson->id]) }}"
                            class="btn lesson-chip {{ optional($selectedLesson)->id === $lesson->id ? 'btn-success' : 'btn-outline-success' }}"
                        >
                            {{ $lesson->title }}
                        </a>
                    @empty
                        <p class="text-muted mb-0">No lessons are available for this course yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Section: Lesson learning materials and quiz content. --}}
@if($selectedLesson)
<div class="row mt-4">
    <div class="col-12">
        <div class="card section-card reveal-up">
            <div class="card-header bg-success text-white">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h4 class="mb-0">{{ $selectedLesson->title }}</h4>
                    <span class="badge bg-light text-success">{{ $course->title }}</span>
                </div>
            </div>
            <div class="card-body">
                @if($selectedLesson->content)
                    <div class="mb-4">
                        <h5>Lesson Content</h5>
                        <p>{!! nl2br(e($selectedLesson->content)) !!}</p>
                    </div>
                @endif

                @if($selectedVideoPath || $selectedPdfPath || $selectedLesson->media_path)
                    <div class="mb-4">
                        <h5>Lesson Learning Materials</h5>
                        <div class="media-container">
                            @php
                                $extension = $selectedLesson->media_path
                                    ? strtolower(pathinfo($selectedLesson->media_path, PATHINFO_EXTENSION))
                                    : '';
                            @endphp

                            {{-- Section: Legacy image support. --}}
                            @if($selectedLesson->media_path && in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                <img src="{{ asset($selectedLesson->media_path) }}" alt="Lesson media" class="img-fluid">
                            @endif

                            {{-- Section: Embedded lesson video. --}}
                            @if($selectedVideoPath)
                                <video controls preload="metadata">
                                    <source src="{{ asset($selectedVideoPath) }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            @endif

                            {{-- Section: Downloadable lesson PDF. --}}
                            @if($selectedPdfPath)
                                <div class="resource-card p-4 mt-3">
                                    <p class="mb-3">A PDF resource is attached to this lesson and can be opened or downloaded.</p>
                                    <a href="{{ asset($selectedPdfPath) }}" class="btn btn-outline-primary" target="_blank" rel="noopener">
                                        Open PDF
                                    </a>
                                </div>
                            @endif

                            {{-- Section: Material action buttons. --}}
                            <div class="mt-3 d-flex flex-wrap gap-2">
                                @if($selectedVideoPath)
                                    <a href="{{ asset($selectedVideoPath) }}" class="btn btn-primary" download>
                                        Download Video
                                    </a>
                                @endif
                                @if($selectedPdfPath)
                                    <a href="{{ asset($selectedPdfPath) }}" class="btn btn-outline-primary" download>
                                        Download PDF
                                    </a>
                                @endif
                                @if($selectedLesson->media_path && !$selectedVideoPath && !$selectedPdfPath)
                                    <a href="{{ asset($selectedLesson->media_path) }}" class="btn btn-primary" download>
                                        Download Media
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Section: Lesson quizzes. --}}
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
                                <p class="text-muted mb-0">No questions are available for this quiz yet.</p>
                            @endif
                        </div>
                    @endforeach
                @else
                    <p class="text-muted mb-0">No quiz is available for this lesson yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@else
<div class="row mt-4">
    <div class="col-12">
        <div class="alert alert-info reveal-up">
            This course does not have any lessons available yet.
        </div>
    </div>
</div>
@endif
@endsection
