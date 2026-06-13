@extends('layouts.admin')
@section('title','Routine')
@section('page-title','Class Routine')
@section('content')
<div class="page-header">
  <div><div class="page-title">Class Routine</div><div class="page-sub">ক্লাসের সময়সূচি পরিচালনা করুন</div></div>
  <a href="{{ route('admin.routines.create') }}" class="btn btn-primary">+ Add Routine</a>
</div>

@forelse($batches as $batch)
  @if(isset($routines[$batch->id]) && $routines[$batch->id]->count())
  <div class="card" style="margin-bottom:20px">
    <div class="card-body" style="padding:12px 20px;background:var(--sidebar-bg);border-radius:12px 12px 0 0">
      <div style="font-weight:700;font-size:15px;color:var(--text-primary)">
        {{ $batch->name }} <span style="font-size:12px;color:#9ca3af;font-weight:400">({{ $batch->course->name ?? '' }})</span>
      </div>
    </div>
    <div style="overflow-x:auto">
      <table style="width:100%;border-collapse:collapse;font-size:13px">
        <thead>
          <tr style="border-bottom:1px solid var(--border)">
            <th style="padding:10px 16px;text-align:left;color:#6b7280;font-weight:600">Day</th>
            <th style="padding:10px 16px;text-align:left;color:#6b7280;font-weight:600">Time</th>
            <th style="padding:10px 16px;text-align:left;color:#6b7280;font-weight:600">Subject</th>
            <th style="padding:10px 16px;text-align:left;color:#6b7280;font-weight:600">Teacher</th>
            <th style="padding:10px 16px;text-align:left;color:#6b7280;font-weight:600">Room</th>
            <th style="padding:10px 16px;text-align:left;color:#6b7280;font-weight:600">Type</th>
            <th style="padding:10px 16px;text-align:left;color:#6b7280;font-weight:600">Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($routines[$batch->id] as $r)
          <tr style="border-bottom:1px solid var(--border)">
            <td style="padding:10px 16px;font-weight:600;text-transform:capitalize">{{ $r->day }}</td>
            <td style="padding:10px 16px">
              {{ \Carbon\Carbon::parse($r->start_time)->format('h:i A') }} – {{ \Carbon\Carbon::parse($r->end_time)->format('h:i A') }}
            </td>
            <td style="padding:10px 16px">{{ $r->subject->name ?? '—' }}</td>
            <td style="padding:10px 16px">{{ $r->teacher->name ?? '—' }}</td>
            <td style="padding:10px 16px">{{ $r->room ?: '—' }}</td>
            <td style="padding:10px 16px">
              <span class="badge {{ $r->type==='class'?'badge-green':'badge-blue' }}">{{ ucfirst($r->type) }}</span>
            </td>
            <td style="padding:10px 16px">
              <form method="POST" action="{{ route('admin.routines.destroy',$r) }}" onsubmit="return confirm('Delete?')">
                @csrf @method('DELETE') <button class="btn btn-sm btn-danger">Delete</button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
  @endif
@empty
  <div class="card">
    <div style="text-align:center;padding:50px;color:#9ca3af">কোনো active batch নেই। প্রথমে batch তৈরি করুন।</div>
  </div>
@endforelse

@if($batches->count() > 0 && $routines->isEmpty())
<div class="card">
  <div style="text-align:center;padding:50px;color:#9ca3af">কোনো routine তৈরি হয়নি। উপরে "Add Routine" বাটনে ক্লিক করুন।</div>
</div>
@endif
@endsection
