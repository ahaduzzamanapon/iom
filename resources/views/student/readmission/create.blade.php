@extends('layouts.student')

@section('title', 'Readmission আবেদন')

@section('content')
<div class="mb-4">
    <a href="{{ route('student.readmission.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> ফিরে যান
    </a>
    <h2 class="fw-bold d-inline ms-3">🔄 Readmission আবেদন করুন</h2>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('student.readmission.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Course *</label>
                <select name="course_id" class="form-select" required>
                    <option value="">— Course বেছে নিন —</option>
                    @foreach($courses as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">কারণ *</label>
                <textarea name="reason" class="form-control" rows="4" required
                          placeholder="Readmission-এর কারণ বিস্তারিত লিখুন..."></textarea>
                @error('reason') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-send me-1"></i> আবেদন জমা দিন
            </button>
        </form>
    </div>
</div>
@endsection
