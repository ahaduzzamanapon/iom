@extends('layouts.student')
@section('title','Attendance')
@section('page-title','My Attendance')
@section('content')
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:20px">
  <div class="stat-card"><div class="stat-icon" style="background:#065f46"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div><div><div class="stat-val">{{ $percent }}%</div><div class="stat-lbl">Attendance %</div></div></div>
  <div class="stat-card"><div class="stat-icon" style="background:#0891b2"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></div><div><div class="stat-val">{{ $present }}</div><div class="stat-lbl">Present</div></div></div>
  <div class="stat-card"><div class="stat-icon" style="background:#dc2626"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></div><div><div class="stat-val">{{ $total - $present }}</div><div class="stat-lbl">Absent</div></div></div>
</div>
<div class="card">
  <table class="dt-table">
    <thead><tr><th>Class</th><th>Batch</th><th>Date</th><th>Status</th></tr></thead>
    <tbody>
      @forelse($records as $r)
      <tr>
        <td>{{ $r->classLesson->title ?? '—' }}</td>
        <td>{{ $r->batch->name ?? '—' }}</td>
        <td>{{ $r->date?->format('d M Y') }}</td>
        <td><span class="badge {{ $r->status==='present'?'badge-green':($r->status==='late'?'badge-yellow':'badge-red') }}">{{ ucfirst($r->status) }}</span></td>
      </tr>
      @empty
      <tr><td colspan="4" style="text-align:center;padding:30px;color:#9ca3af">কোনো attendance record নেই।</td></tr>
      @endforelse
    </tbody>
  </table>
  <div class="pagination">{{ $records->links() }}</div>
</div>
@endsection
