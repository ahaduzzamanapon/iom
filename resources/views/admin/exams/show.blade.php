@extends('layouts.admin')
@section('title','Exam Detail')
@section('page-title','Exam Detail')
@section('content')
<div class="page-header">
  <div><div class="page-title">{{ $exam->title }}</div><div class="page-sub">{{ $exam->batch->name ?? '' }} | {{ $exam->subject->name ?? '' }}</div></div>
  <div style="display:flex;gap:8px">
    <a href="{{ route('admin.exams.edit',$exam) }}" class="btn btn-outline">Edit</a>
    <a href="{{ route('admin.exams.questions.assign',$exam) }}" class="btn btn-success">⚙ Manage Questions</a>
    <a href="{{ route('admin.questions.index') }}?exam_id={{ $exam->id }}" class="btn btn-primary">View Questions</a>
  </div>
</div>
<div style="display:grid;grid-template-columns:1fr 2fr;gap:20px">
  <div class="card"><div class="card-body">
    <table style="width:100%;font-size:13px">
      <tr><td style="color:#718096;padding:6px 0">Type</td><td><span class="badge badge-blue">{{ strtoupper($exam->type) }}</span></td></tr>
      <tr><td style="color:#718096;padding:6px 0">Batch</td><td>{{ $exam->batch->name ?? '—' }}</td></tr>
      <tr><td style="color:#718096;padding:6px 0">Semester</td><td>{{ $exam->semester->name ?? '—' }}</td></tr>
      <tr><td style="color:#718096;padding:6px 0">Total Marks</td><td><strong>{{ $exam->total_marks }}</strong></td></tr>
      <tr><td style="color:#718096;padding:6px 0">Pass Marks</td><td>{{ $exam->pass_marks }}</td></tr>
      <tr><td style="color:#718096;padding:6px 0">Duration</td><td>{{ $exam->duration_minutes }} min</td></tr>
      <tr><td style="color:#718096;padding:6px 0">Status</td><td><span class="badge badge-{{ $exam->status==='published'?'green':'yellow' }}">{{ ucfirst($exam->status) }}</span></td></tr>
      <tr><td style="color:#718096;padding:6px 0">Questions</td><td>{{ $exam->questions->count() }}</td></tr>
      <tr><td style="color:#718096;padding:6px 0">Attempts</td><td>{{ $exam->attempts->count() }}</td></tr>
    </table>
  </div></div>
  <div class="card">
    <div class="card-header"><span class="card-title">Student Results</span></div>
    <div style="overflow:hidden">
      <table class="dt-table">
        <thead><tr><th>Student</th><th>Marks</th><th>%</th><th>Grade</th><th>Status</th></tr></thead>
        <tbody>
          @forelse($exam->attempts as $a)
          <tr>
            <td>{{ $a->student->name ?? '—' }}</td>
            <td>{{ $a->obtained_marks ?? '—' }} / {{ $exam->total_marks }}</td>
            <td>{{ $a->percentage ?? '—' }}%</td>
            <td><strong>{{ $a->grade ?? '—' }}</strong></td>
            <td><span class="badge {{ $a->status==='evaluated'?'badge-green':'badge-yellow' }}">{{ ucfirst($a->status) }}</span></td>
          </tr>
          @empty
          <tr><td colspan="5" style="text-align:center;padding:20px;color:#9ca3af">কোনো attempt নেই।</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
