@extends('layouts.admin')
@section('title','Attendance')
@section('page-title','Attendance Report')
@section('content')
<div class="page-header">
  <div><div class="page-title">Attendance</div></div>
  <a href="{{ route('admin.attendance.export','pdf') }}" class="btn-export btn-pdf">📄 Export PDF</a>
</div>
<div class="card" style="margin-bottom:16px">
  <div class="card-body">
    <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end">
      <div class="form-group" style="margin:0;min-width:180px">
        <label class="form-label">Batch</label>
        <select name="batch_id" class="form-control">
          <option value="">All Batches</option>
          @foreach($batches as $b)
            <option value="{{ $b->id }}" {{ request('batch_id')==$b->id?'selected':'' }}>{{ $b->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group" style="margin:0">
        <label class="form-label">Date</label>
        <input type="date" name="date" class="form-control" value="{{ request('date') }}">
      </div>
      <button class="btn btn-primary">Filter</button>
      <a href="{{ route('admin.attendance.index') }}" class="btn btn-outline">Reset</a>
    </form>
  </div>
</div>
<div class="card">
  <x-data-table :headers="['#','Student','Batch','Class','Date','Status']">
    @forelse($records as $r)
    <tr>
      <td><input type="checkbox" name="ids[]" value="{{ $r->id }}"></td>
      <td>{{ $r->student->name ?? '—' }}</td>
      <td>{{ $r->batch->name ?? '—' }}</td>
      <td style="font-size:12px">{{ $r->classLesson->title ?? '—' }}</td>
      <td style="font-size:12px">{{ $r->date?->format('d M Y') }}</td>
      <td>
        <span class="badge {{ $r->status==='present'?'badge-green':($r->status==='late'?'badge-yellow':'badge-red') }}">
          {{ ucfirst($r->status) }}
        </span>
      </td>
    </tr>
    @empty
    <tr><td colspan="6" style="text-align:center;padding:30px;color:#9ca3af">কোনো attendance record নেই।</td></tr>
    @endforelse
  </x-data-table>
  <div class="pagination">{{ $records->links() }}</div>
</div>
@endsection
