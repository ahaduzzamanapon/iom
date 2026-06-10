@extends('layouts.admin')
@section('title', 'Student Report')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('admin.reports.index') }}" class="btn btn-sm btn-outline-secondary me-2"><i class="bi bi-arrow-left"></i></a>
        <h2 class="fw-bold d-inline">👨‍🎓 Student Report</h2>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.reports.students.export', 'pdf') }}" class="btn btn-outline-danger btn-sm"><i class="bi bi-file-pdf me-1"></i>PDF</a>
        <a href="{{ route('admin.reports.students.export', 'excel') }}" class="btn btn-outline-success btn-sm"><i class="bi bi-file-excel me-1"></i>Excel</a>
    </div>
</div>
<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr><th>Student ID</th><th>Name</th><th>Batch</th><th>Status</th><th>Enrolled</th></tr>
            </thead>
            <tbody>
                @forelse($students as $s)
                <tr>
                    <td><code>{{ $s->student_id }}</code></td>
                    <td>{{ $s->user->name }}</td>
                    <td>{{ $s->batches->first()?->batch->name ?? '—' }}</td>
                    <td><span class="badge bg-{{ $s->status === 'active' ? 'success' : 'secondary' }}">{{ $s->status }}</span></td>
                    <td>{{ $s->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-4">কোনো student নেই।</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $students->links() }}</div>
@endsection
