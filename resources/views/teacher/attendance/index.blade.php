@extends('layouts.teacher')
@section('title','Attendance')
@section('content')
<div class="page-header">
  <div class="page-title">Mark Attendance</div>
</div>

@if(session('success'))
  <div class="alert alert-success" style="margin-bottom:16px;padding:12px 16px;background:#d1fae5;border:1px solid #6ee7b7;border-radius:8px;color:#065f46">
    {{ session('success') }}
  </div>
@endif

<form method="POST" action="{{ route('teacher.attendance.store') }}">
  @csrf
  <input type="hidden" name="batch_id" value="{{ $batch }}">

  <div class="card" style="margin-bottom:16px">
    <div class="card-body" style="display:flex;gap:16px;flex-wrap:wrap;align-items:flex-end">
      <div class="form-group" style="margin:0;min-width:220px">
        <label class="form-label">Class / Lesson <span style="color:red">*</span></label>
        <select name="class_lesson_id" class="form-control" required>
          <option value="">-- Select Class --</option>
          @foreach($lessons as $l)
            <option value="{{ $l->id }}">{{ $l->title }} ({{ $l->module->subject->name ?? '' }})</option>
          @endforeach
        </select>
      </div>
      <div class="form-group" style="margin:0">
        <label class="form-label">Date <span style="color:red">*</span></label>
        <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
      </div>
    </div>
  </div>

  <div class="card">
    <div style="overflow-x:auto">
      <table style="width:100%;border-collapse:collapse;font-size:13px">
        <thead>
          <tr style="border-bottom:1px solid var(--border)">
            <th style="padding:10px 16px;text-align:left;color:#6b7280">#</th>
            <th style="padding:10px 16px;text-align:left;color:#6b7280">Student</th>
            <th style="padding:10px 16px;text-align:left;color:#6b7280">Student ID</th>
            <th style="padding:10px 16px;text-align:center;color:#6b7280">Present</th>
            <th style="padding:10px 16px;text-align:center;color:#6b7280">Absent</th>
            <th style="padding:10px 16px;text-align:center;color:#6b7280">Late</th>
          </tr>
        </thead>
        <tbody>
          @forelse($students as $i => $sb)
          <tr style="border-bottom:1px solid var(--border)">
            <td style="padding:10px 16px">{{ $i+1 }}</td>
            <td style="padding:10px 16px;font-weight:600">{{ $sb->user->name ?? '—' }}</td>
            <td style="padding:10px 16px;font-family:monospace;font-size:12px">{{ $sb->user->studentProfile->student_id ?? '—' }}</td>
            <td style="padding:10px 16px;text-align:center">
              <input type="radio" name="attendance[{{ $sb->user_id }}]" value="present" checked>
            </td>
            <td style="padding:10px 16px;text-align:center">
              <input type="radio" name="attendance[{{ $sb->user_id }}]" value="absent">
            </td>
            <td style="padding:10px 16px;text-align:center">
              <input type="radio" name="attendance[{{ $sb->user_id }}]" value="late">
            </td>
          </tr>
          @empty
          <tr><td colspan="6" style="text-align:center;padding:30px;color:#9ca3af">এই batch-এ কোনো active student নেই।</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($students->count())
    <div style="padding:16px;border-top:1px solid var(--border)">
      <button type="submit" class="btn btn-primary">Save Attendance</button>
    </div>
    @endif
  </div>
</form>
@endsection
