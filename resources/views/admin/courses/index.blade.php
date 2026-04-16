@extends('layouts.app')
@section('title', 'Manage Courses')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manage Courses</h2>
    <a href="{{ route('admin.courses.create') }}" class="btn btn-success">Create Course</a>
</div>
<div class="card">
    <div class="card-body">
        <div class="mb-3" style="max-width: 420px;">
            <input type="text" id="adminCourseSearchInput" class="form-control" placeholder="Search courses by title...">
        </div>
        <table class="table">
            <thead>
                <tr><th>ID</th><th>Title</th><th>Lessons</th><th>Created</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($courses as $course)
                    <tr data-course-row>
                        <td>{{ $course->id }}</td>
                        <td>{{ $course->title }}</td>
                        <td>{{ $course->lessons->count() }}</td>
                        <td>{{ $course->created_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-sm btn-primary">Edit</a>
                            <form method="POST" action="{{ route('admin.courses.delete', $course) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">No courses found.</td></tr>
                @endforelse
                @if($courses->count() > 0)
                    <tr id="adminCourseSearchEmptyRow" class="d-none">
                        <td colspan="5" class="text-center text-muted">No matching courses found.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('adminCourseSearchInput');
        const rows = Array.from(document.querySelectorAll('[data-course-row]'));
        const emptyRow = document.getElementById('adminCourseSearchEmptyRow');

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
