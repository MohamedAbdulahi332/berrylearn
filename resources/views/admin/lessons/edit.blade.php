@extends('layouts.app')
@section('content')
{{-- Section: Lesson editing header. --}}
<h2>Edit Lesson</h2>
<div class="card mt-4">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.lessons.update', $lesson) }}" enctype="multipart/form-data">
            @csrf
            {{-- Section: Course selection. --}}
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
            {{-- Section: Lesson title. --}}
            <div class="mb-3">
                <label>Title</label>
                <input type="text" name="title" class="form-control" value="{{ $lesson->title }}" required>
            </div>
            {{-- Section: Lesson content. --}}
            <div class="mb-3">
                <label>Content</label>
                <textarea name="content" class="form-control" rows="6">{{ $lesson->content }}</textarea>
            </div>
            {{-- Section: Current and replacement lesson video. --}}
            <div class="mb-3">
                <label>Lesson Video</label>
                @if($lesson->resolvedVideoPath())
                    <div class="alert alert-info d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                        <span>Current video: {{ basename($lesson->resolvedVideoPath()) }}</span>
                        <a href="{{ asset($lesson->resolvedVideoPath()) }}" class="btn btn-sm btn-outline-primary" target="_blank" rel="noopener">Open Video</a>
                    </div>
                @endif
                <input type="file" name="video" class="form-control" accept="video/mp4">
                <small class="text-muted">Upload a new MP4 file to replace the current lesson video.</small>
            </div>
            {{-- Section: Current and replacement lesson PDF. --}}
            <div class="mb-3">
                <label>Lesson PDF</label>
                @if($lesson->resolvedPdfPath())
                    <div class="alert alert-info d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                        <span>Current PDF: {{ basename($lesson->resolvedPdfPath()) }}</span>
                        <a href="{{ asset($lesson->resolvedPdfPath()) }}" class="btn btn-sm btn-outline-primary" target="_blank" rel="noopener">Open PDF</a>
                    </div>
                @endif
                <input type="file" name="pdf" class="form-control" accept="application/pdf">
                <small class="text-muted">Upload a new PDF file to replace the current lesson handout.</small>
            </div>
            {{-- Section: Form actions. --}}
            <button type="submit" class="btn btn-primary">Update Lesson</button>
            <a href="{{ route('admin.lessons') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
