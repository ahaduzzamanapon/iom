@extends('layouts.admin')
@section('title','Dashboard')
@section('page-title','Dashboard')

@section('content')

{{-- Stats --}}
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon" style="background:#1a5276">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
    </div>
    <div>
      <div class="stat-val">{{ $stats['students'] }}</div>
      <div class="stat-lbl">Active Students</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background:#16a34a">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
    </div>
    <div>
      <div class="stat-val">{{ $stats['teachers'] }}</div>
      <div class="stat-lbl">Active Teachers</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background:#7c3aed">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
    </div>
    <div>
      <div class="stat-val">{{ $stats['batches'] }}</div>
      <div class="stat-lbl">Active Batches</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background:#ea580c">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
    </div>
    <div>
      <div class="stat-val">{{ $stats['pending_admissions'] }}</div>
      <div class="stat-lbl">Pending Admissions</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background:#0891b2">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div>
      <div class="stat-val">৳{{ number_format($stats['total_revenue']) }}</div>
      <div class="stat-lbl">Total Revenue</div>
    </div>
  </div>
</div>

{{-- Recent Admissions + Payments --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-top:4px">

  {{-- Recent Admissions --}}
  <div class="card">
    <div class="card-header">
      <span class="card-title">Recent Admissions</span>
      <a href="{{ route('admin.admissions.index') }}" class="btn btn-sm btn-outline">View All</a>
    </div>
    <div style="overflow:hidden">
      <table class="dt-table">
        <thead><tr><th>#</th><th>Name</th><th>Course</th><th>Status</th></tr></thead>
        <tbody>
          @forelse($recent_admissions as $a)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $a->applicant_name }}</td>
            <td>{{ $a->course->name ?? '—' }}</td>
            <td>
              <span class="badge {{ $a->status === 'approved' ? 'badge-green' : ($a->status === 'rejected' ? 'badge-red' : 'badge-yellow') }}">
                {{ ucfirst($a->status) }}
              </span>
            </td>
          </tr>
          @empty
          <tr><td colspan="4" style="text-align:center;color:#9ca3af;padding:20px">কোনো admission নেই</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- Recent Payments --}}
  <div class="card">
    <div class="card-header">
      <span class="card-title">Recent Payments</span>
      <a href="{{ route('admin.payments.index') }}" class="btn btn-sm btn-outline">View All</a>
    </div>
    <div style="overflow:hidden">
      <table class="dt-table">
        <thead><tr><th>#</th><th>Student</th><th>Amount</th><th>Status</th></tr></thead>
        <tbody>
          @forelse($recent_payments as $p)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $p->student->name ?? '—' }}</td>
            <td>৳{{ number_format($p->amount) }}</td>
            <td>
              <span class="badge {{ $p->status === 'paid' ? 'badge-green' : ($p->status === 'failed' ? 'badge-red' : 'badge-yellow') }}">
                {{ ucfirst($p->status) }}
              </span>
            </td>
          </tr>
          @empty
          <tr><td colspan="4" style="text-align:center;color:#9ca3af;padding:20px">কোনো payment নেই</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

</div>

@endsection
