@extends('layouts.app')

@section('title', 'All Courses - BerryLearn')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">Available Courses</h1>
        
        @if($courses->count() > 0)
            <div class="row">
                @foreach($courses as $course)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">{{ $course->title }}</h5>
                                <p class="card-text">{{ Str::limit($course->description, 100) }}</p>
                                <a href="{{ route('lectures.show', $course->id) }}" 
                                   class="btn btn-primary">
                                    View Lectures
                                </a>
                            </div>
                            <div class="card-footer text-muted">
                                {{ $course->lectures->count() }} Lecture(s)
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-info">
                No courses available yet. <a href="{{ route('upload.create') }}">Create one!</a>
            </div>
        @endif
    </div>
</div>
@endsection