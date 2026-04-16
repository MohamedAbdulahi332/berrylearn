@extends('layouts.app')
@section('content')
<h2>Create New Lesson</h2>
<div class="card mt-4">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.lessons.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label>Course</label>
                <select name="course_id" class="form-control" required>
                    <option value="">Select Course</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Content</label>
                <textarea name="content" class="form-control" rows="6"></textarea>
            </div>
            <div class="mb-3">
                <label>Media File (optional)</label>
                <input type="file" name="media" class="form-control">
                <small class="text-muted">Allowed: JPG, PNG, PDF, MP4, DOC, DOCX (Max: 10MB)</small>
            </div>
            <button type="submit" class="btn btn-success">Create Lesson</button>
            <a href="{{ route('admin.lessons') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
