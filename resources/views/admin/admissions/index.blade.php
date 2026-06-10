@extends('layouts.admin')
@section('title','Admissions')
@section('page-title','Admission Management')

@section('content')
<div class="page-header">
  <div>
    <div class="page-title">Admissions</div>
    <div class="page-sub">ভর্তি আবেদন পরিচালনা করুন</div>
  </div>
</div>

{{-- Filter --}}
<div class="card" style="margin-bottom:16px">
  <div class="card-body">
    <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end">
      <div class="form-group" style="margin:0;min-width:160px">
        <label class="form-label">Status</label>
        <select name="status" class="form-control">
          <option value="">All</option>
          <option value="pending"  {{ request('status')==='pending'  ? 'selected':'' }}>Pending</option>
          <option value="approved" {{ request('status')==='approved' ? 'selected':'' }}>Approved</option>
          <option value="rejected" {{ request('status')==='rejected' ? 'selected':'' }}>Rejected</option>
        </select>
      </div>
      <div class="form-group" style="margin:0;min-width:180px">
        <label class="form-label">Course</label>
        <select name="course_id" class="form-control">
          <option value="">All Courses</option>
          @foreach($courses as $c)
            <option value="{{ $c->id }}" {{ request('course_id')==$c->id ? 'selected':'' }}>{{ $c->name }}</option>
          @endforeach
        </select>
      </div>
      <button class="btn btn-primary">Filter</button>
      <a href="{{ route('admin.admissions.index') }}" class="btn btn-outline">Reset</a>
    </form>
  </div>
</div>

<div class="card">
  <x-data-table :headers="['#','Applicant','Course','Phone','Applied','Status','Actions']">
    @forelse($admissions as $a)
    <tr>
      <td><input type="checkbox" name="ids[]" value="{{ $a->id }}"></td>
      <td>
        <div style="font-weight:600">{{ $a->applicant_name }}</div>
        <div style="font-size:11px;color:#718096">{{ $a->applicant_email }}</div>
      </td>
      <td>{{ $a->course->name ?? '—' }}</td>
      <td>{{ $a->applicant_phone }}</td>
      <td>{{ $a->created_at->format('d M Y') }}</td>
      <td>
        <span class="badge {{ $a->status==='approved' ? 'badge-green' : ($a->status==='rejected' ? 'badge-red' : 'badge-yellow') }}">
          {{ ucfirst($a->status) }}
        </span>
      </td>
      <td style="white-space:nowrap">
        <a href="{{ route('admin.admissions.show',$a) }}" class="btn btn-sm btn-outline">View</a>
        @if($a->status==='pending')
          <button onclick="document.getElementById('approve-{{ $a->id }}').style.display='block'" class="btn btn-sm btn-success">Approve</button>
          <form method="POST" action="{{ route('admin.admissions.reject',$a) }}" style="display:inline" onsubmit="return confirm('Reject করবেন?')">
            @csrf @method('POST')
            <input type="hidden" name="remarks" value="Admin rejected">
            <button class="btn btn-sm btn-danger">Reject</button>
          </form>
        @endif
      </td>
    </tr>
    {{-- Approve modal inline --}}
    @if($a->status==='pending')
    <tr id="approve-{{ $a->id }}" style="display:none;background:#f0fdf4">
      <td colspan="7" style="padding:16px">
        <form method="POST" action="{{ route('admin.admissions.approve',$a) }}" style="display:flex;gap:12px;align-items:flex-end">
          @csrf
          <div class="form-group" style="margin:0">
            <label class="form-label">Assign Batch *</label>
            <select name="batch_id" class="form-control" required>
              <option value="">Select Batch</option>
              @foreach(\App\Models\Batch::where('status','active')->with('course')->get() as $b)
                <option value="{{ $b->id }}">{{ $b->name }} ({{ $b->course->name ?? '' }})</option>
              @endforeach
            </select>
          </div>
          <button class="btn btn-success">✓ Confirm Approve</button>
          <button type="button" onclick="document.getElementById('approve-{{ $a->id }}').style.display='none'" class="btn btn-outline">Cancel</button>
        </form>
      </td>
    </tr>
    @endif
    @empty
    <tr><td colspan="7" style="text-align:center;padding:30px;color:#9ca3af">কোনো admission নেই।</td></tr>
    @endforelse
  </x-data-table>
  <div class="pagination">{{ $admissions->links() }}</div>
</div>
@endsection
