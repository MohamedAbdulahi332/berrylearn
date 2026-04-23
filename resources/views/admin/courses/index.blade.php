@extends('layouts.app')
@section('title', 'Manage Courses')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manage Courses</h2>
    <a href="{{ route('admin.courses.create') }}" class="btn btn-success">Create Course</a>
</div>
<div class="card">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr><th>ID</th><th>Title</th><th>Lessons</th><th>Created</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($courses as $course)
                    <tr>
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
            </tbody>
        </table>
    </div>
</div>
@endsection
