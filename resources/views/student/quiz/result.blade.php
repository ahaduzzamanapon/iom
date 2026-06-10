@extends('layouts.student')

@section('title', 'Quiz Result')

@section('content')
<div class="text-center mb-5">
    <h2 class="fw-bold">🎯 Quiz Result</h2>
    <h4 class="text-muted">{{ $quiz->title }}</h4>
</div>

@php
    $pct = $attempt->total > 0 ? round(($attempt->score / $attempt->total) * 100) : 0;
    $color = $pct >= 70 ? 'success' : ($pct >= 40 ? 'warning' : 'danger');
@endphp

<div class="card shadow-sm mb-4">
    <div class="card-body text-center py-5">
        <div class="display-1 fw-bold text-{{ $color }} mb-3">{{ $pct }}%</div>
        <h5>আপনি পেয়েছেন: <strong>{{ $attempt->score }} / {{ $attempt->total }}</strong></h5>
        <p class="text-muted">Submit করা হয়েছে: {{ $attempt->submitted_at?->format('d M Y H:i') }}</p>

        <div class="progress mt-3" style="height:20px; max-width:400px; margin:0 auto">
            <div class="progress-bar bg-{{ $color }} fs-6" style="width:{{ $pct }}%">{{ $pct }}%</div>
        </div>
    </div>
</div>

<!-- Answer Review -->
<h5 class="fw-bold mb-3">📋 Answer Review</h5>
@foreach($attempt->answers as $ans)
<div class="card mb-2 border-{{ $ans->is_correct ? 'success' : 'danger' }}">
    <div class="card-body py-2">
        <p class="mb-1 fw-semibold">{{ $ans->question->question }}</p>
        <div class="small">
            <span class="me-3">
                আপনার উত্তর:
                <strong class="text-{{ $ans->is_correct ? 'success' : 'danger' }}">
                    {{ $ans->selected_option ? strtoupper($ans->selected_option) : 'উত্তর দেননি' }}
                </strong>
                @if(!$ans->is_correct)
                    | সঠিক উত্তর:
                    <strong class="text-success">{{ strtoupper($ans->question->correct_option) }}</strong>
                @endif
            </span>
            @if($ans->is_correct)
                <span class="badge bg-success">✓ সঠিক</span>
            @else
                <span class="badge bg-danger">✗ ভুল</span>
            @endif
        </div>
    </div>
</div>
@endforeach

<div class="mt-4 text-center">
    <a href="{{ route('student.quiz.index') }}" class="btn btn-primary">
        <i class="bi bi-arrow-left me-1"></i> Quiz তালিকায় ফিরুন
    </a>
</div>
@endsection
