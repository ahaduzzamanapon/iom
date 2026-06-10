@extends('layouts.student')
@section('title','Dashboard')
@section('page-title','আমার Dashboard')

@section('content')

{{-- Stats --}}
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon" style="background:#065f46">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
    </div>
    <div>
      <div class="stat-val">{{ $batches->count() }}</div>
      <div class="stat-lbl">Enrolled Batches</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background:#0891b2">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div>
      <div class="stat-val">{{ $attendancePercent }}%</div>
      <div class="stat-lbl">Attendance</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background:{{ $duePayments > 0 ? '#dc2626' : '#16a34a' }}">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
    </div>
    <div>
      <div class="stat-val">{{ $duePayments }}</div>
      <div class="stat-lbl">Due Payments</div>
    </div>
  </div>
  @if($latestResult)
  <div class="stat-card">
    <div class="stat-icon" style="background:#7c3aed">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
    </div>
    <div>
      <div class="stat-val">{{ $latestResult->cgpa ?? '—' }}</div>
      <div class="stat-lbl">Latest CGPA</div>
    </div>
  </div>
  @endif
</div>

{{-- Attendance Progress --}}
<div class="card">
  <div class="card-header"><span class="card-title">📊 Attendance Overview</span></div>
  <div class="card-body">
    <div style="display:flex;align-items:center;gap:12px">
      <div class="progress-bar" style="flex:1">
        <div class="progress-fill" style="width:{{ $attendancePercent }}%"></div>
      </div>
      <span style="font-weight:700;color:{{ $attendancePercent >= $minAttendance ? '#16a34a' : '#dc2626' }}">{{ $attendancePercent }}%</span>
    </div>
    @if($attendancePercent < $minAttendance)
      <p style="color:#dc2626;font-size:12px;margin-top:8px">⚠️ Minimum {{ $minAttendance }}% attendance প্রয়োজন। আপনার attendance কম।</p>
    @endif
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">

{{-- Upcoming Live Classes --}}
<div class="card">
  <div class="card-header">
    <span class="card-title">📹 Upcoming Live Classes</span>
    <a href="{{ route('student.learning.index') }}" class="btn btn-sm btn-outline">All Classes</a>
  </div>
  <div class="card-body" style="padding:0">
    <table class="dt-table">
      <tbody>
        @forelse($upcomingClasses as $c)
        <tr>
          <td>
            <div style="font-weight:600;font-size:13px">{{ $c->title }}</div>
            <div style="font-size:11px;color:#718096">{{ $c->scheduled_at?->format('d M, h:i A') }}</div>
          </td>
          <td style="white-space:nowrap">
            @if($c->meet_link)
              <a href="{{ $c->meet_link }}" target="_blank" class="btn btn-sm btn-primary">Google Meet</a>
            @elseif($c->zoom_link)
              <a href="{{ $c->zoom_link }}" target="_blank" class="btn btn-sm" style="background:#2D8CFF;color:#fff">Zoom</a>
            @endif
          </td>
        </tr>
        @empty
        <tr><td colspan="2" style="text-align:center;padding:20px;color:#9ca3af">কোনো upcoming class নেই।</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- Notices --}}
<div class="card">
  <div class="card-header">
    <span class="card-title">🔔 Recent Notices</span>
    <a href="{{ route('student.notices.index') }}" class="btn btn-sm btn-outline">All</a>
  </div>
  <div class="card-body" style="padding:0">
    <table class="dt-table">
      <tbody>
        @forelse($notices as $n)
        <tr>
          <td>
            <div style="font-weight:600;font-size:13px">{{ $n->title }}</div>
            <div style="font-size:11px;color:#718096">{{ $n->published_at?->format('d M Y') }}</div>
          </td>
          <td><span class="badge badge-blue">{{ ucfirst($n->scope) }}</span></td>
        </tr>
        @empty
        <tr><td colspan="2" style="text-align:center;padding:20px;color:#9ca3af">কোনো notice নেই।</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

</div>
@endsection
