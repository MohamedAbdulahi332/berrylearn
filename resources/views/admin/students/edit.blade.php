@extends('layouts.app')

@section('title', 'Edit Student')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Edit Student Details</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.students.update', $student) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $student->name }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $student->email }}" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Student</button>
                    <a href="{{ route('admin.students') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5>Reset Password</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.students.reset', $student) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-warning">Reset Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
