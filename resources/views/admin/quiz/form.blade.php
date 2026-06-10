@extends('layouts.admin')

@section('title', isset($quiz) ? 'Quiz Edit' : 'নতুন Quiz Room')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.quiz.index') }}" class="btn btn-sm btn-outline-secondary me-2">
        <i class="bi bi-arrow-left"></i> ফিরে যান
    </a>
    <h2 class="fw-bold d-inline">{{ isset($quiz) ? 'Quiz Edit' : 'নতুন Quiz Room' }}</h2>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ isset($quiz) ? route('admin.quiz.update', $quiz) : route('admin.quiz.store') }}" method="POST">
            @csrf
            @if(isset($quiz)) @method('PUT') @endif

            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Quiz Title *</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $quiz->title ?? '') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Duration (minutes) *</label>
                    <input type="number" name="duration_minutes" class="form-control" min="1"
                           value="{{ old('duration_minutes', $quiz->duration_minutes ?? 30) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Batch (optional)</label>
                    <select name="batch_id" class="form-select">
                        <option value="">— সকল Batch —</option>
                        @foreach($batches as $batch)
                        <option value="{{ $batch->id }}" {{ old('batch_id', $quiz->batch_id ?? '') == $batch->id ? 'selected' : '' }}>
                            {{ $batch->name }} ({{ $batch->course->name }})
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Start At</label>
                    <input type="datetime-local" name="starts_at" class="form-control"
                           value="{{ old('starts_at', isset($quiz->starts_at) ? $quiz->starts_at->format('Y-m-d\TH:i') : '') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">End At</label>
                    <input type="datetime-local" name="ends_at" class="form-control"
                           value="{{ old('ends_at', isset($quiz->ends_at) ? $quiz->ends_at->format('Y-m-d\TH:i') : '') }}">
                </div>
                @if(isset($quiz))
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        @foreach(['draft','active','closed'] as $s)
                        <option value="{{ $s }}" {{ $quiz->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="col-12">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $quiz->description ?? '') }}</textarea>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save me-1"></i> {{ isset($quiz) ? 'Update' : 'তৈরি করুন' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
