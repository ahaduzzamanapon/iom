@extends('layouts.teacher')
@section('title','My Exams')
@section('content')
<div class="page-header">
  <div><div class="page-title">My Exams</div><div class="page-sub">আপনার batch-এর পরীক্ষা পরিচালনা করুন</div></div>
  <a href="{{ route('teacher.exams.create') }}" class="btn btn-primary">+ New Exam</a>
</div>
<div class="card">
  <x-data-table :headers="['#','Title','Batch','Subject','Type','Marks','Start','Status','Action']">
    @forelse($exams as $e)
    <tr>
      <td><input type="checkbox" name="ids[]" value="{{ $e->id }}"></td>
      <td>{{ $loop->iteration }}</td>
      <td style="font-weight:600">{{ $e->title }}</td>
      <td style="font-size:12px">{{ $e->batch->name ?? '—' }}<div style="color:#9ca3af">{{ $e->batch->course->name ?? '' }}</div></td>
      <td style="font-size:12px">{{ $e->subject->name ?? '—' }}</td>
      <td><span class="badge badge-blue">{{ strtoupper($e->type) }}</span></td>
      <td>{{ $e->total_marks }} <span style="color:#9ca3af;font-size:11px">(Pass: {{ $e->pass_marks }})</span></td>
      <td style="font-size:12px">{{ $e->start_at?->format('d M Y') ?? '—' }}</td>
      <td>
        <span class="badge {{ $e->status==='published'?'badge-green':($e->status==='approved'?'badge-blue':'badge-yellow') }}">
          {{ ucfirst($e->status) }}
        </span>
      </td>
      <td>
        <div style="display:flex;gap:6px">
          <a href="{{ route('teacher.exams.questions.assign',$e) }}" class="btn btn-sm btn-success">⚙ Manage</a>
          <form method="POST" action="{{ route('teacher.exams.destroy',$e) }}" onsubmit="return confirm('Delete?')" style="display:inline">
            @csrf @method('DELETE') <button class="btn btn-sm btn-danger">Delete</button>
          </form>
        </div>
      </td>
    </tr>
    @empty
    <tr><td colspan="9" style="text-align:center;padding:30px;color:#9ca3af">কোনো exam নেই।</td></tr>
    @endforelse
  </x-data-table>
  <div class="pagination">{{ $exams->links() }}</div>
</div>
@endsection
