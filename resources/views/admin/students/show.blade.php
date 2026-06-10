@extends('layouts.admin')
@section('title','Student Detail')
@section('page-title','Students')
@section('content')
<div class="page-header">
  <div>
    <div class="page-title">{{ $student->user->name }}</div>
    <div class="page-sub">Student ID: {{ $student->student_id }}</div>
  </div>
  <div style="display:flex;gap:10px">
    <a href="{{ route('admin.students.edit',$student) }}" class="btn btn-outline">Edit</a>
    <a href="{{ route('admin.students.index') }}" class="btn btn-outline">← Back</a>
  </div>
</div>

@if(session('success'))
  <div class="alert alert-success" style="margin-bottom:16px;padding:12px 16px;background:#d1fae5;border:1px solid #6ee7b7;border-radius:8px;color:#065f46">
    {{ session('success') }}
  </div>
@endif

<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px">
  {{-- Profile --}}
  <div class="card">
    <div class="card-body">
      <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px">
        @if($student->photo)
          <img src="{{ asset('storage/'.$student->photo) }}" style="width:80px;height:80px;object-fit:cover;border-radius:50%;border:3px solid var(--border)">
        @else
          <div style="width:80px;height:80px;border-radius:50%;background:var(--primary);display:flex;align-items:center;justify-content:center;font-size:28px;color:white;font-weight:700">
            {{ strtoupper(substr($student->user->name,0,1)) }}
          </div>
        @endif
        <div>
          <div style="font-size:18px;font-weight:700">{{ $student->user->name }}</div>
          <div style="color:#9ca3af;font-size:13px">{{ $student->user->email }}</div>
          <span class="badge {{ $student->status==='active'?'badge-green':'badge-gray' }}" style="margin-top:4px">{{ ucfirst($student->status) }}</span>
        </div>
      </div>

      <table style="width:100%;border-collapse:collapse;font-size:14px">
        <tr><td style="padding:8px 0;color:#6b7280;width:160px">Student ID</td><td style="padding:8px 0;font-weight:700;font-family:monospace">{{ $student->student_id }}</td></tr>
        <tr><td style="padding:8px 0;color:#6b7280">Phone</td><td style="padding:8px 0">{{ $student->phone ?? '—' }}</td></tr>
        <tr><td style="padding:8px 0;color:#6b7280">Date of Birth</td><td style="padding:8px 0">{{ $student->date_of_birth ?? '—' }}</td></tr>
        <tr><td style="padding:8px 0;color:#6b7280">Gender</td><td style="padding:8px 0">{{ ucfirst($student->gender ?? '—') }}</td></tr>
        <tr><td style="padding:8px 0;color:#6b7280">Guardian</td><td style="padding:8px 0">{{ $student->guardian_name ?? '—' }} {{ $student->guardian_phone ? '('.$student->guardian_phone.')' : '' }}</td></tr>
        <tr><td style="padding:8px 0;color:#6b7280">Address</td><td style="padding:8px 0">{{ $student->address ?? '—' }}</td></tr>
        <tr><td style="padding:8px 0;color:#6b7280">Joined</td><td style="padding:8px 0">{{ $student->created_at->format('d M Y') }}</td></tr>
      </table>
    </div>
  </div>

  {{-- Batches & Promote --}}
  <div>
    <div class="card" style="margin-bottom:16px">
      <div class="card-body">
        <div style="font-size:14px;font-weight:700;margin-bottom:12px">Enrolled Batches</div>
        @forelse($student->batches as $sb)
          <div style="padding:8px 0;border-bottom:1px solid var(--border);font-size:13px">
            <div style="font-weight:600">{{ $sb->batch->name ?? '—' }}</div>
            <div style="color:#9ca3af">{{ $sb->batch->course->name ?? '' }}</div>
            <span class="badge {{ $sb->status==='active'?'badge-green':'badge-gray' }}" style="margin-top:4px">{{ ucfirst($sb->status) }}</span>
          </div>
        @empty
          <div style="color:#9ca3af;font-size:13px">কোনো batch নেই।</div>
        @endforelse
      </div>
    </div>

    {{-- Semester Promotion Form --}}
    @php
      $courseIds = $student->batches->pluck('batch.course_id')->filter()->unique()->toArray();
      $semesters = \App\Models\Semester::whereIn('course_id', $courseIds)->get();
      $targetBatches = \App\Models\Batch::whereIn('course_id', $courseIds)->where('status', 'active')->get();
    @endphp
    @if(count($courseIds) > 0)
    <div class="card" style="margin-bottom:16px">
      <div class="card-body">
        <div style="font-size:14px;font-weight:700;margin-bottom:12px">Semester Promotion</div>
        <form method="POST" action="{{ route('admin.students.promote', $student->user_id) }}">
          @csrf
          <div class="form-group">
            <label class="form-label">From Semester</label>
            <select name="from_semester_id" class="form-control" required>
              <option value="">-- Select --</option>
              @foreach($semesters as $s)
                <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->course->name ?? '' }})</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">To Semester</label>
            <select name="to_semester_id" class="form-control" required>
              <option value="">-- Select --</option>
              @foreach($semesters as $s)
                <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->course->name ?? '' }})</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Target Batch</label>
            <select name="batch_id" class="form-control" required>
              <option value="">-- Select Target Batch --</option>
              @foreach($targetBatches as $tb)
                <option value="{{ $tb->id }}">{{ $tb->name }} (Semester: {{ $tb->semester->name ?? 'None' }})</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Promotion Status</label>
            <select name="status" class="form-control" required>
              <option value="promoted">Promoted</option>
              <option value="held_back">Held Back</option>
              <option value="re_exam">Re-Exam</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Remarks</label>
            <textarea name="remarks" class="form-control" rows="2" placeholder="Promotion remarks..."></textarea>
          </div>
          <button type="submit" class="btn btn-success" style="width:100%;justify-content:center">
            ✓ Apply Progression
          </button>
        </form>
      </div>
    </div>
    @endif

    {{-- Certificate Issue --}}
    <div class="card">
      <div class="card-body">
        <div style="font-size:14px;font-weight:700;margin-bottom:12px">Issue Certificate</div>
        <form method="POST" action="{{ route('admin.certificates.generate', $student->user_id) }}">
          @csrf
          <div class="form-group">
            <label class="form-label">Select Course</label>
            <select name="course_id" class="form-control" required>
              <option value="">-- Course --</option>
              @foreach($student->batches as $sb)
                @if($sb->batch?->course)
                  <option value="{{ $sb->batch->course->id }}">{{ $sb->batch->course->name }}</option>
                @endif
              @endforeach
            </select>
          </div>
          <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
            ↓ Generate & Download
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
