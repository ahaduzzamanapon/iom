@extends('layouts.admin')
@section('title','Attendance Report')
@section('page-title','Attendance Report')
@section('content')
<div class="page-header">
  <div><div class="page-title">Attendance Summary</div><div class="page-sub">ব্যাচ অনুযায়ী উপস্থিতির পরিসংখ্যান</div></div>
  <div style="display:flex;gap:10px">
    <a href="{{ route('admin.attendance.export','pdf') }}" class="btn btn-outline">↓ PDF</a>
    <a href="{{ route('admin.attendance.index') }}" class="btn btn-outline">← Back</a>
  </div>
</div>

@foreach($batches as $batch)
@php
  $total    = \App\Models\Attendance::where('batch_id', $batch->id)->count();
  $present  = \App\Models\Attendance::where('batch_id', $batch->id)->where('status','present')->count();
  $absent   = \App\Models\Attendance::where('batch_id', $batch->id)->where('status','absent')->count();
  $late     = \App\Models\Attendance::where('batch_id', $batch->id)->where('status','late')->count();
  $pct      = $total > 0 ? round(($present / $total) * 100) : 0;
@endphp
<div class="card" style="margin-bottom:16px">
  <div class="card-body">
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px">
      <div>
        <div style="font-size:16px;font-weight:700">{{ $batch->name }}</div>
        <div style="font-size:12px;color:#9ca3af">{{ $batch->course->name ?? '' }}</div>
      </div>
      <div style="display:flex;gap:20px;text-align:center">
        <div><div style="font-size:22px;font-weight:700;color:#10b981">{{ $present }}</div><div style="font-size:11px;color:#6b7280">Present</div></div>
        <div><div style="font-size:22px;font-weight:700;color:#ef4444">{{ $absent }}</div><div style="font-size:11px;color:#6b7280">Absent</div></div>
        <div><div style="font-size:22px;font-weight:700;color:#f59e0b">{{ $late }}</div><div style="font-size:11px;color:#6b7280">Late</div></div>
        <div><div style="font-size:22px;font-weight:700;color:var(--primary)">{{ $pct }}%</div><div style="font-size:11px;color:#6b7280">Rate</div></div>
      </div>
    </div>
    <div style="background:#e5e7eb;border-radius:99px;height:8px;margin-top:16px">
      <div style="background:var(--primary);border-radius:99px;height:8px;width:{{ $pct }}%"></div>
    </div>
  </div>
</div>
@endforeach
@endsection
