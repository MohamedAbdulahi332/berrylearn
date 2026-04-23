@extends('layouts.app')
@section('title', 'Manage Courses')
@section('content')
@php($totalLessons = $courses->sum(fn ($course) => $course->lessons->count()))

<div class="d-flex justify-content-between align-items-center mb-4 reveal-up">
    <div>
        <h2 class="mb-1">Manage Courses</h2>
        <p class="text-muted mb-0">Filter, review, and update the learning catalog.</p>
    </div>
    <a href="{{ route('admin.courses.create') }}" class="btn btn-success action-chip">Create Course</a>
</div>
<div class="card section-card reveal-up">
    <div class="card-header d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
        <div class="d-flex flex-wrap gap-2">
            <span class="badge-soft">Courses: {{ $courses->count() }}</span>
            <span class="badge-soft">Lessons: {{ $totalLessons }}</span>
        </div>
        <div class="search-wrap w-100" style="max-width: 320px;">
            <span class="search-label">Find</span>
            <input
                type="text"
                class="form-control soft-search"
                placeholder="Search courses"
                data-filter-input="#course-table-body"
                data-empty-target="#course-filter-empty"
            >
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr><th>ID</th><th>Title</th><th>Lessons</th><th>Created</th><th>Actions</th></tr>
                </thead>
                <tbody id="course-table-body">
                    @forelse($courses as $course)
                        <tr data-filter-item data-filter-text="{{ strtolower($course->title . ' ' . $course->id) }}">
                            <td>{{ $course->id }}</td>
                            <td>{{ $course->title }}</td>
                            <td><span class="badge text-bg-primary">{{ $course->lessons->count() }}</span></td>
                            <td>{{ $course->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-sm btn-primary">Edit</a>
                                <form method="POST" action="{{ route('admin.courses.delete', $course) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this course?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted">No courses found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div id="course-filter-empty" class="filter-empty d-none mt-4 p-4 text-center text-muted">
            No courses match your search.
        </div>
    </div>
</div>
@endsection
