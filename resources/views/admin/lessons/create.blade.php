@extends('layouts.app')
@section('content')
{{-- Section: Lesson creation header. --}}
<h2>Create New Lesson</h2>
<div class="card mt-4">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.lessons.store') }}" enctype="multipart/form-data">
            @csrf
            {{-- Section: Course selection. --}}
            <div class="mb-3">
                <label>Course</label>
                <select name="course_id" class="form-control" required>
                    <option value="">Select Course</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->title }}</option>
                    @endforeach
                </select>
            </div>
            {{-- Section: Lesson title. --}}
            <div class="mb-3">
                <label>Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            {{-- Section: Lesson content. --}}
            <div class="mb-3">
                <label>Content</label>
                <textarea name="content" class="form-control" rows="6"></textarea>
            </div>
            {{-- Section: Lesson video upload. --}}
            <div class="mb-3">
                <label>Lesson Video (optional)</label>
                <input type="file" name="video" class="form-control" accept="video/mp4">
                <small class="text-muted">Upload an MP4 learning video for students to watch inside the lesson. Max: 50MB.</small>
            </div>
            {{-- Section: Lesson PDF upload. --}}
            <div class="mb-3">
                <label>Lesson PDF (optional)</label>
                <input type="file" name="pdf" class="form-control" accept="application/pdf">
                <small class="text-muted">Upload a PDF resource for students to open or download. Max: 20MB.</small>
            </div>
            {{-- Section: Form actions. --}}
            <button type="submit" class="btn btn-success">Create Lesson</button>
            <a href="{{ route('admin.lessons') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
