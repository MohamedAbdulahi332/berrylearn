@extends('layouts.app')
@section('content')
<h2>Edit Lesson</h2>
<div class="card mt-4">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.lessons.update', $lesson) }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label>Course</label>
                <select name="course_id" class="form-control" required>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ $lesson->course_id == $course->id ? 'selected' : '' }}>
                            {{ $course->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Title</label>
                <input type="text" name="title" class="form-control" value="{{ $lesson->title }}" required>
            </div>
            <div class="mb-3">
                <label>Content</label>
                <textarea name="content" class="form-control" rows="6">{{ $lesson->content }}</textarea>
            </div>
            <div class="mb-3">
                <label>Media File</label>
                @if($lesson->media_path)
                    <div class="alert alert-info">Current: {{ basename($lesson->media_path) }}</div>
                @endif
                <input type="file" name="media" class="form-control">
                <small class="text-muted">Upload new file to replace existing</small>
            </div>
            <button type="submit" class="btn btn-primary">Update Lesson</button>
            <a href="{{ route('admin.lessons') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
