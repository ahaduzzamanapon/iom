@extends('layouts.student')

@section('title', 'Quiz')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold">🎯 Quiz</h2>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('info'))
    <div class="alert alert-info">{{ session('info') }}</div>
@endif

<!-- Join by Room Code -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h5 class="fw-semibold mb-3">🔑 Room Code দিয়ে Join করুন</h5>
        <form action="{{ route('student.quiz.join') }}" method="POST" class="d-flex gap-2">
            @csrf
            <input type="text" name="room_code" class="form-control" placeholder="Room Code (e.g. AB12CD34)" style="max-width:250px" required>
            <button type="submit" class="btn btn-primary">Join করুন</button>
        </form>
        @error('room_code') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>
</div>

<!-- Available Quizzes -->
<h5 class="fw-bold mb-3">📋 উপলব্ধ Quizzes</h5>

@if($quizzes->isEmpty())
    <div class="card shadow-sm">
        <div class="card-body text-center text-muted py-5">
            <i class="bi bi-clipboard-x fs-1 d-block mb-3"></i>
            এখন কোনো active quiz নেই।
        </div>
    </div>
@else
<div class="row g-3">
    @foreach($quizzes as $quiz)
    <div class="col-md-6 col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h6 class="fw-bold">{{ $quiz->title }}</h6>
                <p class="text-muted small">{{ $quiz->description }}</p>
                <div class="d-flex gap-2 flex-wrap mb-3">
                    <span class="badge bg-light text-dark border"><i class="bi bi-clock me-1"></i>{{ $quiz->duration_minutes }} min</span>
                    <span class="badge bg-light text-dark border"><i class="bi bi-question-circle me-1"></i>{{ $quiz->questions_count }} প্রশ্ন</span>
                    @if($quiz->batch)
                        <span class="badge bg-light text-dark border">{{ $quiz->batch->name }}</span>
                    @endif
                </div>
                @if(in_array($quiz->id, $attempts))
                    <a href="{{ route('student.quiz.result', $quiz) }}" class="btn btn-sm btn-outline-success w-100">
                        <i class="bi bi-check-circle me-1"></i> Result দেখুন
                    </a>
                @else
                    <a href="{{ route('student.quiz.take', $quiz) }}" class="btn btn-sm btn-primary w-100">
                        <i class="bi bi-play-fill me-1"></i> Quiz শুরু করুন
                    </a>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection
