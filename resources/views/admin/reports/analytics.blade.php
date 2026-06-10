@extends('layouts.admin')
@section('title', 'Batch & Course Analytics')
@section('content')
<div class="mb-4">
    <a href="{{ route('admin.reports.index') }}" class="btn btn-sm btn-outline-secondary me-2"><i class="bi bi-arrow-left"></i></a>
    <h2 class="fw-bold d-inline">📊 Batch & Course Analytics</h2>
</div>

<!-- Course Summary -->
<h5 class="fw-bold mb-3">📚 Course Summary</h5>
<div class="row g-4 mb-5">
    @foreach($courses as $course)
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold">{{ $course->name }}</h6>
                <p class="text-muted small mb-2">{{ $course->type === 'short_term' ? 'Short Term' : 'Long Term' }}</p>
                <div class="d-flex gap-3">
                    <div class="text-center">
                        <h5 class="fw-bold text-primary mb-0">{{ $course->batches_count }}</h5>
                        <small class="text-muted">Batches</small>
                    </div>
                    <div class="text-center">
                        <h5 class="fw-bold text-success mb-0">{{ $course->subjects_count }}</h5>
                        <small class="text-muted">Subjects</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Batch Details -->
<h5 class="fw-bold mb-3">🗂️ Batch Details</h5>
<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr><th>Batch</th><th>Course</th><th>Students</th><th>Status</th><th>Start</th><th>End</th></tr>
            </thead>
            <tbody>
                @foreach($batches as $b)
                <tr>
                    <td><strong>{{ $b->name }}</strong></td>
                    <td>{{ $b->course->name }}</td>
                    <td><span class="badge bg-primary">{{ $b->student_count }}</span></td>
                    <td><span class="badge bg-{{ $b->status === 'active' ? 'success' : 'secondary' }}">{{ $b->status }}</span></td>
                    <td>{{ $b->start_date ? \Carbon\Carbon::parse($b->start_date)->format('d M Y') : '—' }}</td>
                    <td>{{ $b->end_date ? \Carbon\Carbon::parse($b->end_date)->format('d M Y') : '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
