@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Edit Profile</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5>Change Password</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-warning">Change Password</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Quiz History</h5>
            </div>
            <div class="card-body">
                @forelse($quizResults as $result)
                    <div class="card mb-3">
                        <div class="card-body">
                            <h6>{{ $result->quiz->title }}</h6>
                            <p class="mb-1 text-muted small">
                                {{ $result->quiz->lesson->course->title }} - {{ $result->quiz->lesson->title }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-{{ $result->percentage >= 80 ? 'success' : ($result->percentage >= 60 ? 'info' : 'warning') }}">
                                    Score: {{ $result->score }}/{{ $result->total }} ({{ $result->percentage }}%)
                                </span>
                                <small class="text-muted">{{ $result->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">You haven't taken any quizzes yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
