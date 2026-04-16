@extends('layouts.app')
@section('title', 'Edit Course')
@section('content')
<h2>Edit Course</h2>
<div class="card mt-4">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.courses.update', $course) }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" value="{{ $course->title }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4">{{ $course->description }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update Course</button>
            <a href="{{ route('admin.courses') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
