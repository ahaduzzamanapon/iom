@extends('layouts.admin')
@section('title', 'Examination Report')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('admin.reports.index') }}" class="btn btn-sm btn-outline-secondary me-2"><i class="bi bi-arrow-left"></i></a>
        <h2 class="fw-bold d-inline">📋 Examination Report</h2>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Exam বেছে নিন</label>
                <select name="exam_id" class="form-select">
                    <option value="">— Exam বেছে নিন —</option>
                    @foreach($exams as $exam)
                        <option value="{{ $exam->id }}" {{ request('exam_id') == $exam->id ? 'selected' : '' }}>
                            {{ $exam->title }} ({{ $exam->batch->name ?? '—' }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 d-flex gap-2 align-items-end">
                <button type="submit" class="btn btn-primary">Show Results</button>
                @if(request('exam_id'))
                <a href="{{ route('admin.reports.examination.export', 'pdf') . '?exam_id=' . request('exam_id') }}"
                   class="btn btn-outline-danger"><i class="bi bi-file-pdf"></i> PDF</a>
                <a href="{{ route('admin.reports.examination.export', 'excel') . '?exam_id=' . request('exam_id') }}"
                   class="btn btn-outline-success"><i class="bi bi-file-excel"></i> Excel</a>
                @endif
            </div>
        </form>
    </div>
</div>

@if($results->isNotEmpty())
<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr><th>Rank</th><th>Student</th><th>Obtained</th><th>Total</th><th>%</th><th>Grade</th><th>GPA</th><th>Status</th></tr>
            </thead>
            <tbody>
                @foreach($results as $i => $r)
                @php $pct = $r->exam->total_marks > 0 ? round(($r->obtained_marks / $r->exam->total_marks) * 100, 1) : 0; @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $r->user->name ?? '—' }}</td>
                    <td>{{ $r->obtained_marks }}</td>
                    <td>{{ $r->exam->total_marks }}</td>
                    <td>{{ $pct }}%</td>
                    <td><span class="badge bg-{{ $pct >= 50 ? 'success' : 'danger' }}">{{ $r->grade ?? '—' }}</span></td>
                    <td>{{ $r->gpa ?? '—' }}</td>
                    <td><span class="badge bg-{{ $r->status === 'passed' ? 'success' : 'danger' }}">{{ $r->status ?? '—' }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $results->links() }}</div>
@elseif(request('exam_id'))
<div class="card shadow-sm">
    <div class="card-body text-center text-muted py-5">এই exam-এর কোনো result নেই।</div>
</div>
@else
<div class="card shadow-sm">
    <div class="card-body text-center text-muted py-5">উপরে একটি exam বেছে নিন।</div>
</div>
@endif
@endsection
