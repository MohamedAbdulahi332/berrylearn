@extends('layouts.app')

@section('title', 'Upload Content - BerryLearn')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">Upload Learning Materials</h1>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Validation Errors -->
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">
            <!-- Form 1: Create New Course -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Create New Course</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('upload.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="action" value="create_course">
                            
                            <div class="mb-3">
                                <label for="course_title" class="form-label">Course Title</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="course_title" 
                                       name="course_title" 
                                       required>
                            </div>

                            <div class="mb-3">
                                <label for="course_description" class="form-label">Description</label>
                                <textarea class="form-control" 
                                          id="course_description" 
                                          name="course_description" 
                                          rows="4" 
                                          required></textarea>
                            </div>

                            <button type="submit" class="btn btn-success w-100">
                                Create Course
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Form 2: Add Lecture to Existing Course -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Add Lecture to Course</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('upload.store') }}" 
                              method="POST" 
                              enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="action" value="add_lecture">
                            
                            <div class="mb-3">
                                <label for="course_id" class="form-label">Select Course</label>
                                <select class="form-select" id="course_id" name="course_id" required>
                                    <option value="">-- Choose Course --</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}">{{ $course->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="lecture_title" class="form-label">Lecture Title</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="lecture_title" 
                                       name="lecture_title" 
                                       required>
                            </div>

                            <div class="mb-3">
                                <label for="lecture_content" class="form-label">Lecture Content</label>
                                <textarea class="form-control" 
                                          id="lecture_content" 
                                          name="lecture_content" 
                                          rows="3"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="video_url" class="form-label">Video URL (Optional)</label>
                                <input type="url" 
                                       class="form-control" 
                                       id="video_url" 
                                       name="video_url" 
                                       placeholder="https://youtube.com/...">
                            </div>

                            <div class="mb-3">
                                <label for="file" class="form-label">Upload File (Optional)</label>
                                <input type="file" 
                                       class="form-control" 
                                       id="file" 
                                       name="file" 
                                       accept=".pdf,.doc,.docx,.ppt,.pptx">
                                <small class="text-muted">Accepted: PDF, DOC, DOCX, PPT, PPTX (Max 10MB)</small>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                Add Lecture
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection