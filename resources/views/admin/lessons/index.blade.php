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
            <span class="badge-soft">With Video: {{ $lessons->filter(fn ($lesson) => $lesson->resolvedVideoPath())->count() }}</span>
            <span class="badge-soft">With PDF: {{ $lessons->filter(fn ($lesson) => $lesson->resolvedPdfPath())->count() }}</span>
        </div>
        <div class="search-wrap w-100" style="max-width: 320px;">
            <span class="search-label">Find</span>
            <input
                type="text"
                class="form-control soft-search"
                placeholder="Search lessons or courses"
                data-filter-input="#lesson-table-body"
                data-empty-target="#lesson-filter-empty"
            >
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
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

        {{-- Section: Empty search state. --}}
        <div id="lesson-filter-empty" class="filter-empty d-none mt-4 p-4 text-center text-muted">
            No lessons match your search.
        </div>
    </div>
</div>
@endsection
