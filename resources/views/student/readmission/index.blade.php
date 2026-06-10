@extends('layouts.student')

@section('title', 'Readmission')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">🔄 Readmission আবেদন</h2>
    <a href="{{ route('student.readmission.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> নতুন আবেদন
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if($readmissions->isEmpty())
    <div class="card shadow-sm">
        <div class="card-body text-center text-muted py-5">
            <i class="bi bi-file-earmark-x fs-1 d-block mb-3"></i>
            আপনার কোনো readmission আবেদন নেই।
        </div>
    </div>
@else
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Course</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Applied At</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($readmissions as $r)
                    <tr>
                        <td>{{ $r->course->name }}</td>
                        <td><small>{{ Str::limit($r->reason, 60) }}</small></td>
                        <td>
                            <span class="badge bg-{{ $r->status === 'approved' ? 'success' : ($r->status === 'rejected' ? 'danger' : 'warning') }}">
                                {{ ucfirst($r->status) }}
                            </span>
                        </td>
                        <td>{{ $r->created_at->format('d M Y') }}</td>
                        <td><small class="text-muted">{{ $r->remarks ?? '—' }}</small></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection
