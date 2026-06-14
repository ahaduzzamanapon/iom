@extends('layouts.admin')
@section('title','Scholarships')
@section('page-title','Scholarship Management')
@section('content')
<div class="page-header">
  <div><div class="page-title">Scholarships</div><div class="page-sub">বৃত্তি ও ছাড় পরিচালনা করুন</div></div>
  <a href="{{ route('admin.scholarships.create') }}" class="btn btn-primary">+ New Scholarship</a>
</div>
<div class="card">
  <x-data-table :headers="['#','Student','Title','Discount','Reason','Status','Approved By','Actions']">
    @forelse($scholarships as $s)
    <tr>
      <td><input type="checkbox" name="ids[]" value="{{ $s->id }}"></td>
      <td>{{ $loop->iteration }}</td>
      <td>
        <div style="font-weight:600">{{ $s->student->name ?? '—' }}</div>
        <div style="font-size:11px;color:#9ca3af">{{ $s->student->studentProfile->student_id ?? '' }}</div>
      </td>
      <td>{{ $s->title }}</td>
      <td>
        @if($s->discount_amount) <div>৳ {{ number_format($s->discount_amount,2) }}</div> @endif
        @if($s->discount_percent) <div>{{ $s->discount_percent }}%</div> @endif
      </td>
      <td style="max-width:180px;font-size:12px">{{ Str::limit($s->reason,60) }}</td>
      <td>
        <span class="badge {{ $s->status==='approved'?'badge-green':($s->status==='rejected'?'badge-red':'badge-yellow') }}">
          {{ ucfirst($s->status) }}
        </span>
      </td>
      <td style="font-size:12px">{{ $s->approver->name ?? '—' }}</td>
      <td style="white-space:nowrap">
        @if($s->status === 'pending')
          <form method="POST" action="{{ route('admin.scholarships.approve',$s) }}" style="display:inline">
            @csrf <button class="btn btn-sm btn-primary">Approve</button>
          </form>
        @endif
        <form method="POST" action="{{ route('admin.scholarships.destroy',$s) }}" style="display:inline" onsubmit="return confirm('Delete?')">
          @csrf @method('DELETE') <button class="btn btn-sm btn-danger">Delete</button>
        </form>
      </td>
    </tr>
    @empty
    <tr><td colspan="8" style="text-align:center;padding:30px;color:#9ca3af">কোনো scholarship নেই।</td></tr>
    @endforelse
  </x-data-table>
  <div class="pagination">{{ $scholarships->links() }}</div>
</div>
@endsection
