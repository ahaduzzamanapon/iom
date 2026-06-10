@extends('layouts.admin')
@section('title','Audit Trail')
@section('page-title','Audit Trail')
@section('content')
<div class="page-header">
  <div>
    <div class="page-title">🔍 Audit Trail</div>
    <div class="page-sub">সব activity log — কে, কখন, কী করেছে</div>
  </div>
</div>

{{-- Filters --}}
<div class="card" style="margin-bottom:16px">
  <div class="card-body">
    <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end">
      <div class="form-group" style="margin:0;min-width:160px">
        <label class="form-label">Log Module</label>
        <select name="log_name" class="form-control">
          <option value="">সব Module</option>
          @foreach($logNames as $ln)
            <option value="{{ $ln }}" {{ request('log_name')==$ln?'selected':'' }}>{{ ucfirst($ln) }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group" style="margin:0;min-width:160px">
        <label class="form-label">Date</label>
        <input type="date" name="date" class="form-control" value="{{ request('date') }}">
      </div>
      <button class="btn btn-primary">Filter</button>
      <a href="{{ route('admin.audit.index') }}" class="btn btn-outline">Reset</a>
    </form>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <span class="card-title">Activity Logs ({{ $logs->total() }})</span>
  </div>
  <div class="dt-wrapper">
    <div class="dt-table-wrap">
      <table class="dt-table">
        <thead>
          <tr>
            <th>#</th>
            <th>User</th>
            <th>Action</th>
            <th>Module</th>
            <th>Subject</th>
            <th>Description</th>
            <th>Time</th>
          </tr>
        </thead>
        <tbody>
          @forelse($logs as $log)
          <tr>
            <td>{{ $log->id }}</td>
            <td>
              @if($log->causer)
                <strong>{{ $log->causer->name }}</strong>
                <div style="font-size:11px;color:#718096">{{ $log->causer->email }}</div>
              @else
                <span style="color:#aaa">System</span>
              @endif
            </td>
            <td>
              @php
                $actionColors = ['created'=>'badge-green','updated'=>'badge-blue','deleted'=>'badge-red'];
                $color = $actionColors[$log->event] ?? 'badge-gray';
              @endphp
              <span class="badge {{ $color }}">{{ ucfirst($log->event ?? 'log') }}</span>
            </td>
            <td><span class="badge badge-gray">{{ $log->log_name }}</span></td>
            <td style="font-size:11px">
              {{ $log->subject_type ? class_basename($log->subject_type) : '—' }}
              @if($log->subject_id) <span style="color:#718096">#{{ $log->subject_id }}</span> @endif
            </td>
            <td style="font-size:12px;max-width:300px">{{ Str::limit($log->description, 80) }}</td>
            <td style="white-space:nowrap;font-size:12px;color:#718096">
              {{ $log->created_at->format('d M Y') }}<br>
              {{ $log->created_at->format('h:i A') }}
            </td>
          </tr>
          @empty
          <tr><td colspan="7" style="text-align:center;padding:30px;color:#9ca3af">কোনো log নেই।</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
  <div class="pagination">{{ $logs->links() }}</div>
</div>
@endsection
