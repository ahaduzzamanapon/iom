@extends('layouts.teacher')
@section('title','Dashboard')
@section('page-title','Teacher Dashboard')
@section('content')
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon" style="background:#1e3a5f">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
    </div>
    <div><div class="stat-val">{{ $myBatches->count() }}</div><div class="stat-lbl">My Batches</div></div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background:#0891b2">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.882v6.236a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
    </div>
    <div><div class="stat-val">{{ $myClasses->count() }}</div><div class="stat-lbl">Upcoming Classes</div></div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background:#7c3aed">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
    </div>
    <div><div class="stat-val">{{ $myExams->count() }}</div><div class="stat-lbl">Active Exams</div></div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
  <div class="card">
    <div class="card-header"><span class="card-title">📹 Upcoming Live Classes</span></div>
    <div class="card-body" style="padding:0">
      <table class="dt-table"><tbody>
        @forelse($myClasses as $c)
        <tr>
          <td><div style="font-weight:600">{{ $c->title }}</div><div style="font-size:11px;color:#718096">{{ $c->scheduled_at?->format('d M, h:i A') }}</div></td>
          <td><span class="badge badge-blue">{{ strtoupper($c->type) }}</span></td>
        </tr>
        @empty
        <tr><td colspan="2" style="text-align:center;padding:20px;color:#9ca3af">কোনো upcoming class নেই।</td></tr>
        @endforelse
      </tbody></table>
    </div>
  </div>
  <div class="card">
    <div class="card-header"><span class="card-title">📚 My Batches</span></div>
    <div class="card-body" style="padding:0">
      <table class="dt-table"><tbody>
        @forelse($myBatches as $b)
        <tr>
          <td><div style="font-weight:600">{{ $b->name }}</div><div style="font-size:11px;color:#718096">{{ $b->course->name ?? '' }}</div></td>
          <td style="text-align:right">
            <a href="{{ route('teacher.attendance.index', $b->id) }}" class="btn btn-sm btn-primary" style="font-size:11px;padding:4px 8px">📊 Attendance</a>
          </td>
        </tr>
        @empty
        <tr><td colspan="2" style="text-align:center;padding:20px;color:#9ca3af">কোনো batch assign হয়নি।</td></tr>
        @endforelse
      </tbody></table>
    </div>
  </div>
</div>
@endsection
