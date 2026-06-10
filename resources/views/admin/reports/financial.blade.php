@extends('layouts.admin')
@section('title', 'Financial Report')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('admin.reports.index') }}" class="btn btn-sm btn-outline-secondary me-2"><i class="bi bi-arrow-left"></i></a>
        <h2 class="fw-bold d-inline">💰 Financial Report</h2>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold">From</label>
                <input type="date" name="from" class="form-control" value="{{ request('from') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">To</label>
                <input type="date" name="to" class="form-control" value="{{ request('to') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="">সব</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2 align-items-end">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('admin.reports.financial.export', 'pdf') . '?' . http_build_query(request()->all()) }}"
                   class="btn btn-outline-danger"><i class="bi bi-file-pdf"></i></a>
                <a href="{{ route('admin.reports.financial.export', 'excel') . '?' . http_build_query(request()->all()) }}"
                   class="btn btn-outline-success"><i class="bi bi-file-excel"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="alert alert-info">
    <strong>মোট Revenue:</strong> ৳ {{ number_format($totalRevenue, 2) }}
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr><th>Transaction ID</th><th>Student</th><th>Amount</th><th>Method</th><th>Status</th><th>Paid At</th></tr>
            </thead>
            <tbody>
                @forelse($payments as $p)
                <tr>
                    <td><code>{{ $p->transaction_id }}</code></td>
                    <td>{{ $p->user->name ?? '—' }}</td>
                    <td>৳ {{ number_format($p->amount, 2) }}</td>
                    <td>{{ ucfirst($p->method) }}</td>
                    <td><span class="badge bg-{{ $p->status === 'paid' ? 'success' : 'warning' }}">{{ $p->status }}</span></td>
                    <td>{{ $p->paid_at?->format('d M Y') ?? '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">কোনো payment নেই।</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $payments->links() }}</div>
@endsection
