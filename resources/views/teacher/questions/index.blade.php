@extends('layouts.teacher')
@section('title','My Questions')
@section('content')
<div class="page-header">
  <div><div class="page-title">My Questions</div><div class="page-sub">Question bank-এ জমা দেওয়া প্রশ্নসমূহ</div></div>
  <a href="{{ route('teacher.questions.create') }}" class="btn btn-primary">+ Add Question</a>
</div>
<div class="card">
  <x-data-table :headers="['#','Question','Exam','Subject','Type','Marks','Status','Action']">
    @forelse($questions as $q)
    <tr>
      <td><input type="checkbox" name="ids[]" value="{{ $q->id }}"></td>
      <td>{{ $loop->iteration }}</td>
      <td style="max-width:220px;font-size:13px">{{ Str::limit($q->question_text, 80) }}</td>
      <td style="font-size:12px">{{ $q->exam->title ?? '—' }}</td>
      <td style="font-size:12px">{{ $q->subject->name ?? '—' }}</td>
      <td><span class="badge badge-blue">{{ strtoupper($q->type ?? 'mcq') }}</span></td>
      <td>{{ $q->marks ?? 1 }}</td>
      <td><span class="badge {{ $q->status==='approved'?'badge-green':($q->status==='rejected'?'badge-red':'badge-yellow') }}">{{ ucfirst($q->status) }}</span></td>
      <td>
        <form method="POST" action="{{ route('teacher.questions.destroy',$q) }}" onsubmit="return confirm('Delete?')">
          @csrf @method('DELETE') <button class="btn btn-sm btn-danger">Delete</button>
        </form>
      </td>
    </tr>
    @empty
    <tr><td colspan="8" style="text-align:center;padding:30px;color:#9ca3af">কোনো question নেই।</td></tr>
    @endforelse
  </x-data-table>
  <div class="pagination">{{ $questions->links() }}</div>
</div>
@endsection
