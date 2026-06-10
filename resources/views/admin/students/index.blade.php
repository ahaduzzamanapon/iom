@extends('layouts.admin')
@section('title','Students')
@section('page-title','Student Management')

@section('content')
<div class="page-header">
  <div><div class="page-title">Students</div><div class="page-sub">সকল শিক্ষার্থী পরিচালনা করুন</div></div>
  <div style="display:flex;gap:8px">
    <a href="{{ route('admin.reports.students.export','pdf') }}" class="btn-export btn-pdf">📄 PDF</a>
    <a href="{{ route('admin.reports.students.export','excel') }}" class="btn-export btn-excel">📊 Excel</a>
  </div>
</div>

{{-- Search --}}
<div class="card" style="margin-bottom:16px">
  <div class="card-body">
    <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end">
      <div class="form-group" style="margin:0;flex:1;min-width:220px">
        <label class="form-label">Search</label>
        <input type="text" name="search" class="form-control" placeholder="Name or Student ID..." value="{{ request('search') }}">
      </div>
      <div class="form-group" style="margin:0;min-width:160px">
        <label class="form-label">Status</label>
        <select name="status" class="form-control">
          <option value="">All</option>
          <option value="active"      {{ request('status')==='active'      ?'selected':'' }}>Active</option>
          <option value="inactive"    {{ request('status')==='inactive'    ?'selected':'' }}>Inactive</option>
          <option value="transferred" {{ request('status')==='transferred' ?'selected':'' }}>Transferred</option>
        </select>
      </div>
      <button class="btn btn-primary">Search</button>
      <a href="{{ route('admin.students.index') }}" class="btn btn-outline">Reset</a>
    </form>
  </div>
</div>

<div class="card">
  <x-data-table
    :headers="['#','Student ID','Name','Batch/Course','Phone','Status','Actions']"
    :export-pdf-url="route('admin.reports.students.export','pdf')"
    :export-url="route('admin.reports.students.export','excel')"
  >
    @forelse($students as $s)
    <tr>
      <td><input type="checkbox" name="ids[]" value="{{ $s->id }}"></td>
      <td><span style="font-family:monospace;font-weight:600;color:#1a5276">{{ $s->student_id }}</span></td>
      <td>
        <div style="font-weight:600">{{ $s->user->name ?? '—' }}</div>
        <div style="font-size:11px;color:#718096">{{ $s->user->email ?? '' }}</div>
      </td>
      <td>
        @foreach($s->batches->where('status','active') as $sb)
          <div style="font-size:12px">{{ $sb->batch->name ?? '' }}
            <span style="color:#9ca3af">/ {{ $sb->batch->course->name ?? '' }}</span>
          </div>
        @endforeach
      </td>
      <td>{{ $s->phone ?? '—' }}</td>
      <td>
        <span class="badge {{ $s->status==='active' ? 'badge-green' : ($s->status==='inactive' ? 'badge-gray' : 'badge-yellow') }}">
          {{ ucfirst($s->status) }}
        </span>
      </td>
      <td style="white-space:nowrap">
        <a href="{{ route('admin.students.show',$s) }}" class="btn btn-sm btn-outline">View</a>
        <a href="{{ route('admin.students.edit',$s) }}" class="btn btn-sm btn-outline">Edit</a>
      </td>
    </tr>
    @empty
    <tr><td colspan="7" style="text-align:center;padding:30px;color:#9ca3af">কোনো student নেই।</td></tr>
    @endforelse
  </x-data-table>
  <div class="pagination">{{ $students->links() }}</div>
</div>
@endsection
