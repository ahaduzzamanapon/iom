@extends('layouts.admin')
@section('title', 'Attendance Report')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('admin.reports.index') }}" class="btn btn-sm btn-outline-secondary me-2"><i class="bi bi-arrow-left"></i></a>
        <h2 class="fw-bold d-inline">📅 Attendance Report</h2>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Batch</label>
                <select name="batch_id" class="form-select">
                    <option value="">সব Batch</option>
                    @foreach($batches as $b)
                        <option value="{{ $b->id }}" {{ request('batch_id') == $b->id ? 'selected' : '' }}>
                            {{ $b->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">From</label>
                <input type="date" name="from" class="form-control" value="{{ request('from') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">To</label>
                <input type="date" name="to" class="form-control" value="{{ request('to') }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill">Filter</button>
                <a href="{{ route('admin.reports.attendance.export', 'pdf') . '?' . http_build_query(request()->all()) }}"
                   class="btn btn-outline-danger"><i class="bi bi-file-pdf"></i></a>
                <a href="{{ route('admin.reports.attendance.export', 'excel') . '?' . http_build_query(request()->all()) }}"
                   class="btn btn-outline-success"><i class="bi bi-file-excel"></i></a>
            </div>
        </form>
    </div>
</div>

@if($attendance->isNotEmpty())
<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr><th>Date</th><th>Student</th><th>Batch</th><th>Class</th><th>Status</th></tr>
            </thead>
            <tbody>
                @foreach($attendance as $a)
                <tr>
                    <td>{{ $a->date }}</td>
                    <td>{{ $a->user->name ?? '—' }}</td>
                    <td>{{ $a->batch->name ?? '—' }}</td>
                    <td>{{ $a->classLesson->title ?? '—' }}</td>
                    <td>
                        <span class="badge bg-{{ $a->status === 'present' ? 'success' : ($a->status === 'late' ? 'warning' : 'danger') }}">
                            {{ ucfirst($a->status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $attendance->links() }}</div>
@else
<div class="card shadow-sm">
    <div class="card-body text-center text-muted py-5">Batch এবং তারিখ filter করুন attendance দেখতে।</div>
</div>
@endif
@endsection
