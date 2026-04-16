@extends('layouts.app')
@section('title', 'Create Course')
@section('content')
<h2>Create New Course</h2>
<div class="card mt-4">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.courses.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4"></textarea>
            </div>
            <button type="submit" class="btn btn-success">Create Course</button>
            <a href="{{ route('admin.courses') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
