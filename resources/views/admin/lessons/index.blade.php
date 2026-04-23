@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between mb-4">
    <h2>Manage Lessons</h2>
    <a href="{{ route('admin.lessons.create') }}" class="btn btn-success">Create Lesson</a>
</div>
<table class="table">
    <tr><th>ID</th><th>Title</th><th>Course</th><th>Media</th><th>Actions</th></tr>
    @forelse($lessons as $lesson)
        <tr>
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
</table>
@endsection
