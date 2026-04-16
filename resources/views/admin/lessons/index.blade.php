@extends('layouts.app')
@section('title', 'Manage Lessons')
@section('content')
<div class="d-flex justify-content-between mb-4">
    <h2>Manage Lessons</h2>
    <a href="{{ route('admin.lessons.create') }}" class="btn btn-success">Create Lesson</a>
</div>
<div class="mb-3" style="max-width: 420px;">
    <input type="text" id="adminLessonSearchInput" class="form-control" placeholder="Search lessons by title or course...">
</div>
<table class="table">
    <tr><th>ID</th><th>Title</th><th>Course</th><th>Media</th><th>Actions</th></tr>
    @forelse($lessons as $lesson)
        <tr data-lesson-row>
            <td>{{ $lesson->id }}</td>
            <td>{{ $lesson->title }}</td>
            <td>{{ $lesson->course->title }}</td>
            <td>{{ $lesson->media_path ? '✓' : '-' }}</td>
            <td>
                <a href="{{ route('admin.lessons.edit', $lesson) }}" class="btn btn-sm btn-primary">Edit</a>
                <form method="POST" action="{{ route('admin.lessons.delete', $lesson) }}" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</button>
                </form>
            </td>
        </tr>
    @empty
        <tr><td colspan="5" class="text-center text-muted">No lessons found.</td></tr>
    @endforelse
    @if($lessons->count() > 0)
        <tr id="adminLessonSearchEmptyRow" class="d-none">
            <td colspan="5" class="text-center text-muted">No matching lessons found.</td>
        </tr>
    @endif
</table>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('adminLessonSearchInput');
        const rows = Array.from(document.querySelectorAll('[data-lesson-row]'));
        const emptyRow = document.getElementById('adminLessonSearchEmptyRow');

        if (!input || !rows.length) {
            return;
        }

        input.addEventListener('input', function () {
            const query = input.value.trim().toLowerCase();
            let visibleCount = 0;

            rows.forEach(function (row) {
                const matches = row.textContent.toLowerCase().includes(query);
                row.style.display = matches ? '' : 'none';

                if (matches) {
                    visibleCount++;
                }
            });

            if (emptyRow) {
                emptyRow.classList.toggle('d-none', visibleCount !== 0);
            }
        });
    });
</script>
@endsection
