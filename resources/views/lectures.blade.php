@extends('layouts.app')

@section('title', $course->title . ' - BerryLearn')

@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- Course Header -->
        <div class="card mb-4 bg-light">
            <div class="card-body">
                <h1>{{ $course->title }}</h1>
                <p class="lead">{{ $course->description }}</p>
                <a href="{{ route('courses.index') }}" class="btn btn-secondary">
                    &larr; Back to Courses
                </a>
            </div>
        </div>

        <!-- Lectures List -->
        <h2 class="mb-3">Lectures</h2>
        
        @if($lectures->count() > 0)
            @foreach($lectures as $index => $lecture)
                <div class="card mb-3">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">{{ $index + 1 }}. {{ $lecture->title }}</h5>
                    </div>
                    <div class="card-body">
                        <!-- Text Content -->
                        @if($lecture->content)
                            <div class="mb-3">
                                <h6>Content:</h6>
                                <p>{{ $lecture->content }}</p>
                            </div>
                        @endif

                        <!-- Video Link -->
                        @if($lecture->video_url)
                            <div class="mb-3">
                                <h6>Video:</h6>
                                <a href="{{ $lecture->video_url }}" 
                                   target="_blank" 
                                   class="btn btn-success btn-sm">
                                    📹 Watch Video
                                </a>
                            </div>
                        @endif

                        <!-- Downloadable File -->
                        @if($lecture->file_path)
                            <div class="mb-3">
                                <h6>Download Material:</h6>
                                <a href="{{ asset('storage/' . $lecture->file_path) }}" 
                                   download 
                                   class="btn btn-info btn-sm">
                                    📄 Download File
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <div class="alert alert-warning">
                No lectures available for this course yet.
            </div>
        @endif
    </div>
</div>
@endsection