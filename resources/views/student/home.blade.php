@extends('layouts.app')

@section('title', 'Student Home')

@section('content')
{{-- Section: Student page heading. --}}
<div class="row reveal-up">
    <div class="col-12">
        <h2>Welcome, {{ auth()->user()->name }}!</h2>
        <p class="text-muted">Choose a course to open its own lesson page and continue learning.</p>
    </div>
</div>

{{-- Section: Interactive course browser and lesson picker. --}}
<div class="row mt-4">
    <div class="col-12">
        <div class="card section-card reveal-up">
            <div class="card-body">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                    <div>
                        <h5 class="mb-1">Browse Learning Content</h5>
                        <p class="text-muted mb-0">Filter through the available courses, then open a dedicated lesson page for the one you want.</p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge-soft">Courses: {{ $courses->count() }}</span>
                    </div>
                </div>

                <div class="search-wrap mb-4">
                    <span class="search-label">Find</span>
                    <input
                        type="text"
                        class="form-control soft-search"
                        placeholder="Search courses"
                        data-filter-input="#student-learning-browser"
                        data-empty-target="#student-filter-empty"
                    >
                </div>

                <div id="student-learning-browser">
                    <h5>Courses</h5>
                    <div class="course-browser-grid">
                        @forelse($courses as $course)
                            <div
                                class="course-card"
                                data-filter-item
                                data-filter-text="{{ strtolower($course->title . ' ' . ($course->description ?? '')) }}"
                            >
                                <div class="course-card-body">
                                    <div>
                                        <h6 class="course-card-title">{{ $course->title }}</h6>
                                        <p class="course-card-meta">{{ $course->lessons->count() }} lesson(s) available</p>
                                    </div>
                                    <div class="course-card-actions">
                                        <a
                                            href="{{ route('student.courses.show', $course) }}"
                                            class="btn btn-sm btn-primary"
                                        >
                                            Open Course
                                        </a>
                                        <a
                                            href="{{ $course->youtubeSearchUrl() }}"
                                            class="btn btn-sm btn-outline-secondary"
                                            target="_blank"
                                            rel="noopener"
                                        >
                                            Search YouTube
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted">No courses available yet.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Section: Empty filter state. --}}
                <div id="student-filter-empty" class="filter-empty d-none mt-4 p-4 text-center text-muted">
                    No courses match your search.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
