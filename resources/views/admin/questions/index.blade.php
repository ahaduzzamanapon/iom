@extends('layouts.admin')
@section('title','Questions')
@section('page-title','Question Bank')
@section('content')
<div class="page-header">
  <div><div class="page-title">Question Bank</div><div class="page-sub">শিক্ষকদের জমা দেওয়া প্রশ্ন অনুমোদন করুন</div></div>
</div>

{{-- Filter --}}
<div class="card" style="margin-bottom:16px">
  <div class="card-body">
    <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end">
      <div class="form-group" style="margin:0;min-width:140px">
        <label class="form-label">Status</label>
        <select name="status" class="form-control">
          <option value="">All</option>
          <option value="pending" {{ request('status')==='pending'?'selected':'' }}>Pending</option>
          <option value="approved" {{ request('status')==='approved'?'selected':'' }}>Approved</option>
          <option value="rejected" {{ request('status')==='rejected'?'selected':'' }}>Rejected</option>
        </select>
      </div>
      <button class="btn btn-primary">Filter</button>
      <a href="{{ route('admin.questions.index') }}" class="btn btn-outline">Reset</a>
    </form>
  </div>
</div>

<div class="card">
  <x-data-table :headers="['#','Question','Exam','Subject','Type','Marks','By','Status','Actions']">
    @forelse($questions as $q)
    <tr>
      <td><input type="checkbox" name="ids[]" value="{{ $q->id }}"></td>
      <td>{{ $loop->iteration }}</td>
      <td style="max-width:200px;font-size:13px">{{ Str::limit($q->question_text, 70) }}</td>
      <td style="font-size:12px">{{ $q->exam->title ?? '—' }}</td>
      <td style="font-size:12px">{{ $q->subject->name ?? '—' }}</td>
      <td><span class="badge badge-blue">{{ strtoupper($q->type ?? 'mcq') }}</span></td>
      <td>{{ $q->marks ?? 1 }}</td>
      <td style="font-size:12px">{{ $q->creator->name ?? '—' }}</td>
      <td>
        <span class="badge {{ $q->status==='approved'?'badge-green':($q->status==='rejected'?'badge-red':'badge-yellow') }}">
          {{ ucfirst($q->status) }}
        </span>
      </td>
      <td style="white-space:nowrap">
        @if($q->status !== 'approved')
          <form method="POST" action="{{ route('admin.questions.approve',$q) }}" style="display:inline">
            @csrf <button class="btn btn-sm btn-primary">✓ Approve</button>
          </form>
        @endif
        <form method="POST" action="{{ route('admin.questions.destroy',$q) }}" style="display:inline" onsubmit="return confirm('Delete?')">
          @csrf @method('DELETE') <button class="btn btn-sm btn-danger">Delete</button>
        </form>
      </td>
    </tr>
    @empty
    <tr><td colspan="9" style="text-align:center;padding:30px;color:#9ca3af">কোনো question নেই।</td></tr>
    @endforelse
  </x-data-table>
  <div class="pagination">{{ $questions->links() }}</div>
</div>
@endsection
