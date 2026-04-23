@extends('layouts.app')
@section('content')
{{-- Section: Lesson management header. --}}
<div class="d-flex justify-content-between align-items-center mb-4 reveal-up">
    <div>
        <h2 class="mb-1">Manage Lessons</h2>
        <p class="text-muted mb-0">Quickly filter lesson content and check which lessons have videos or PDFs attached.</p>
    </div>
    <a href="{{ route('admin.lessons.create') }}" class="btn btn-success action-chip">Create Lesson</a>
</div>

{{-- Section: Lesson list panel. --}}
<div class="card section-card reveal-up">
    <div class="card-header d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
        <div class="d-flex flex-wrap gap-2">
            <span class="badge-soft">Lessons: {{ $lessons->count() }}</span>
            <span class="badge-soft">With Video: {{ $lessons->filter(function ($lesson) { return $lesson->resolvedVideoPath(); })->count() }}</span>
            <span class="badge-soft">With PDF: {{ $lessons->filter(function ($lesson) { return $lesson->resolvedPdfPath(); })->count() }}</span>
        </div>
        <div class="search-wrap w-100" style="max-width: 320px;">
            <span class="search-label">Find</span>
            <input
                type="text"
                class="form-control soft-search"
                placeholder="Search lessons or courses"
                data-filter-input="#lesson-list-panel"
                data-empty-target="#lesson-filter-empty"
            >
        </div>
    </div>
    <div class="card-body">
        <div id="lesson-list-panel">
        {{-- Section: Desktop lesson table. --}}
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr><th>ID</th><th>Title</th><th>Course</th><th>Video</th><th>PDF</th><th>Actions</th></tr>
                </thead>
                <tbody id="lesson-table-body">
                    @forelse($lessons as $lesson)
                        <tr data-filter-item data-filter-text="{{ strtolower($lesson->title . ' ' . $lesson->course->title . ' ' . $lesson->id) }}">
                            <td>{{ $lesson->id }}</td>
                            <td>{{ $lesson->title }}</td>
                            <td>{{ $lesson->course->title }}</td>
                            <td>
                                <span class="badge {{ $lesson->resolvedVideoPath() ? 'text-bg-success' : 'text-bg-secondary' }}">
                                    {{ $lesson->resolvedVideoPath() ? 'Uploaded' : 'None' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $lesson->resolvedPdfPath() ? 'text-bg-primary' : 'text-bg-secondary' }}">
                                    {{ $lesson->resolvedPdfPath() ? 'Uploaded' : 'None' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.lessons.edit', $lesson) }}" class="btn btn-sm btn-primary">Edit</a>
                                <form method="POST" action="{{ route('admin.lessons.delete', $lesson) }}" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this lesson?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted">No lessons found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Section: Mobile lesson cards. --}}
        <div class="d-grid gap-3 d-md-none">
            @forelse($lessons as $lesson)
                <div
                    class="card interactive-card"
                    data-filter-item
                    data-filter-text="{{ strtolower($lesson->title . ' ' . $lesson->course->title . ' ' . $lesson->id) }}"
                >
                    <div class="card-body d-flex flex-column gap-3">
                        <div>
                            <div class="d-flex justify-content-between align-items-start gap-3">
                                <div>
                                    <p class="text-muted small mb-1">Lesson #{{ $lesson->id }}</p>
                                    <h5 class="mb-1">{{ $lesson->title }}</h5>
                                    <p class="text-muted mb-0">{{ $lesson->course->title }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge {{ $lesson->resolvedVideoPath() ? 'text-bg-success' : 'text-bg-secondary' }}">
                                Video: {{ $lesson->resolvedVideoPath() ? 'Uploaded' : 'None' }}
                            </span>
                            <span class="badge {{ $lesson->resolvedPdfPath() ? 'text-bg-primary' : 'text-bg-secondary' }}">
                                PDF: {{ $lesson->resolvedPdfPath() ? 'Uploaded' : 'None' }}
                            </span>
                        </div>

                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('admin.lessons.edit', $lesson) }}" class="btn btn-sm btn-primary">Edit</a>
                            <form method="POST" action="{{ route('admin.lessons.delete', $lesson) }}" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this lesson?')">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-4">No lessons found.</div>
            @endforelse
        </div>
        </div>

        {{-- Section: Empty search state. --}}
        <div id="lesson-filter-empty" class="filter-empty d-none mt-4 p-4 text-center text-muted">
            No lessons match your search.
        </div>
    </div>
</div>
@endsection
