@extends('layouts.app')
@section('content')
<h2>Quiz Results</h2>
<div class="card mt-4">
    <div class="card-body">
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <label>Filter by Student</label>
                <select name="student_id" class="form-control">
                    <option value="">All Students</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                            {{ $student->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label>Filter by Course</label>
                <select name="course_id" class="form-control">
                    <option value="">All Courses</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                            {{ $course->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-primary d-block">Filter</button>
            </div>
        </form>
        <table class="table">
            <tr><th>Student</th><th>Quiz</th><th>Course</th><th>Score</th><th>Percentage</th><th>Date</th></tr>
            @forelse($results as $result)
                <tr>
                    <td>{{ $result->user->name }}</td>
                    <td>{{ $result->quiz->title }}</td>
                    <td>{{ optional(optional(optional($result->quiz)->lesson)->course)->title ?? 'Missing course' }}</td>
                    <td>{{ $result->score }}/{{ $result->total }}</td>
                    <td><span class="badge bg-{{ $result->percentage >= 80 ? 'success' : ($result->percentage >= 60 ? 'info' : 'warning') }}">{{ $result->percentage }}%</span></td>
                    <td>{{ optional($result->created_at)->format('M d, Y H:i') ?? 'Not available' }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted">No results found.</td></tr>
            @endforelse
        </table>
    </div>
</div>
@endsection
