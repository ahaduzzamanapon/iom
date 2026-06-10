@extends('layouts.admin')
@section('title','Exams')
@section('page-title','Exam Management')
@section('content')
<div class="page-header">
  <div><div class="page-title">Exams</div><div class="page-sub">পরীক্ষা পরিচালনা করুন</div></div>
  <a href="{{ route('admin.exams.create') }}" class="btn btn-primary">+ New Exam</a>
</div>
<div class="card" style="margin-bottom:16px">
  <div class="card-body">
    <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end">
      <div class="form-group" style="margin:0;min-width:140px">
        <label class="form-label">Type</label>
        <select name="type" class="form-control">
          <option value="">All</option>
          <option value="mcq" {{ request('type')==='mcq'?'selected':'' }}>MCQ</option>
          <option value="written" {{ request('type')==='written'?'selected':'' }}>Written</option>
          <option value="re_exam" {{ request('type')==='re_exam'?'selected':'' }}>Re-Exam</option>
        </select>
      </div>
      <div class="form-group" style="margin:0;min-width:140px">
        <label class="form-label">Status</label>
        <select name="status" class="form-control">
          <option value="">All</option>
          <option value="draft" {{ request('status')==='draft'?'selected':'' }}>Draft</option>
          <option value="approved" {{ request('status')==='approved'?'selected':'' }}>Approved</option>
          <option value="published" {{ request('status')==='published'?'selected':'' }}>Published</option>
          <option value="completed" {{ request('status')==='completed'?'selected':'' }}>Completed</option>
        </select>
      </div>
      <button class="btn btn-primary">Filter</button>
      <a href="{{ route('admin.exams.index') }}" class="btn btn-outline">Reset</a>
    </form>
  </div>
</div>
<div class="card">
  <x-data-table :headers="['#','Title','Batch','Subject','Type','Marks','Date','Status','Actions']">
    @forelse($exams as $e)
    <tr>
      <td><input type="checkbox" name="ids[]" value="{{ $e->id }}"></td>
      <td style="font-weight:600">{{ $e->title }}</td>
      <td>{{ $e->batch->name ?? '—' }}<div style="font-size:11px;color:#9ca3af">{{ $e->batch->course->name ?? '' }}</div></td>
      <td>{{ $e->subject->name ?? '—' }}</td>
      <td><span class="badge badge-blue">{{ strtoupper($e->type) }}</span></td>
      <td>{{ $e->total_marks }} <span style="color:#9ca3af;font-size:11px">(Pass: {{ $e->pass_marks }})</span></td>
      <td style="font-size:12px">{{ $e->start_at?->format('d M Y') ?? '—' }}</td>
      <td><span class="badge {{ $e->status==='published'?'badge-green':($e->status==='completed'?'badge-gray':'badge-yellow') }}">{{ ucfirst($e->status) }}</span></td>
      <td style="white-space:nowrap">
        <a href="{{ route('admin.exams.show',$e) }}" class="btn btn-sm btn-outline">View</a>
        <a href="{{ route('admin.exams.edit',$e) }}" class="btn btn-sm btn-outline">Edit</a>
        <form method="POST" action="{{ route('admin.exams.destroy',$e) }}" style="display:inline" onsubmit="return confirm('Delete?')">
          @csrf @method('DELETE') <button class="btn btn-sm btn-danger">Del</button>
        </form>
      </td>
    </tr>
    @empty
    <tr><td colspan="9" style="text-align:center;padding:30px;color:#9ca3af">কোনো exam নেই।</td></tr>
    @endforelse
  </x-data-table>
  <div class="pagination">{{ $exams->links() }}</div>
</div>
@endsection
