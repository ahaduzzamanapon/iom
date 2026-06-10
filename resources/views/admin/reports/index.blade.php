@extends('layouts.admin')

@section('title', 'Reports & Analytics')

@section('content')
<h2 class="fw-bold mb-4">📊 Reports & Analytics</h2>

<!-- Quick Stats -->
<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="card shadow-sm border-0 bg-primary text-white">
            <div class="card-body">
                <h6 class="opacity-75">Total Students</h6>
                <h2 class="fw-bold mb-0">{{ $stats['total_students'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 bg-success text-white">
            <div class="card-body">
                <h6 class="opacity-75">Total Courses</h6>
                <h2 class="fw-bold mb-0">{{ $stats['total_courses'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 bg-info text-white">
            <div class="card-body">
                <h6 class="opacity-75">Total Batches</h6>
                <h2 class="fw-bold mb-0">{{ $stats['total_batches'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 bg-warning text-dark">
            <div class="card-body">
                <h6 class="opacity-75">Total Revenue</h6>
                <h2 class="fw-bold mb-0">৳ {{ number_format($stats['total_revenue']) }}</h2>
            </div>
        </div>
    </div>
</div>

<!-- Report Links -->
<div class="row g-4 mb-5">
    @php
    $reportLinks = [
        ['url' => route('admin.reports.students'), 'icon' => 'bi-people-fill', 'title' => 'Student Report', 'desc' => 'সব student-এর তালিকা ও export', 'color' => 'primary'],
        ['url' => route('admin.reports.attendance'), 'icon' => 'bi-calendar-check-fill', 'title' => 'Attendance Report', 'desc' => 'Batch-wise attendance বিশ্লেষণ', 'color' => 'success'],
        ['url' => route('admin.reports.examination'), 'icon' => 'bi-clipboard-data-fill', 'title' => 'Examination Report', 'desc' => 'Exam results ও grading', 'color' => 'warning'],
        ['url' => route('admin.reports.financial'), 'icon' => 'bi-cash-coin', 'title' => 'Financial Report', 'desc' => 'Payment history ও revenue', 'color' => 'danger'],
        ['url' => route('admin.reports.analytics'), 'icon' => 'bi-bar-chart-fill', 'title' => 'Batch Analytics', 'desc' => 'Course ও batch statistics', 'color' => 'info'],
    ];
    @endphp

    @foreach($reportLinks as $link)
    <div class="col-md-4">
        <a href="{{ $link['url'] }}" class="text-decoration-none">
            <div class="card shadow-sm h-100 border-start border-4 border-{{ $link['color'] }}">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="text-{{ $link['color'] }} fs-2"><i class="bi {{ $link['icon'] }}"></i></div>
                    <div>
                        <h6 class="fw-bold mb-1 text-dark">{{ $link['title'] }}</h6>
                        <p class="text-muted small mb-0">{{ $link['desc'] }}</p>
                    </div>
                </div>
            </div>
        </a>
    </div>
    @endforeach
</div>

<!-- Recent Payments -->
<div class="card shadow-sm">
    <div class="card-header fw-semibold">💳 সাম্প্রতিক Payments</div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead class="table-light">
                <tr><th>Student</th><th>Amount</th><th>Method</th><th>Status</th><th>Date</th></tr>
            </thead>
            <tbody>
                @foreach($recentPayments as $p)
                <tr>
                    <td>{{ $p->user->name ?? '—' }}</td>
                    <td>৳ {{ number_format($p->amount, 2) }}</td>
                    <td>{{ ucfirst($p->method) }}</td>
                    <td><span class="badge bg-{{ $p->status === 'paid' ? 'success' : 'secondary' }}">{{ $p->status }}</span></td>
                    <td>{{ $p->paid_at?->format('d M Y') ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
