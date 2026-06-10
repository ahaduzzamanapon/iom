@extends('layouts.admin')
@section('title','Results')
@section('page-title','Result Management')
@section('content')
<div class="page-header">
  <div><div class="page-title">Results</div></div>
  <div style="display:flex;gap:8px">
    <a href="{{ route('admin.reports.examination.export','pdf') }}" class="btn-export btn-pdf">📄 PDF</a>
    <a href="{{ route('admin.reports.examination.export','excel') }}" class="btn-export btn-excel">📊 Excel</a>
  </div>
</div>
<div class="card" style="margin-bottom:16px">
  <div class="card-body">
    <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end">
      <div class="form-group" style="margin:0;min-width:160px">
        <label class="form-label">Batch</label>
        <select name="batch_id" class="form-control">
          <option value="">All Batches</option>
          @foreach($batches as $b)
            <option value="{{ $b->id }}" {{ request('batch_id')==$b->id?'selected':'' }}>{{ $b->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group" style="margin:0;min-width:160px">
        <label class="form-label">Semester</label>
        <select name="semester_id" class="form-control">
          <option value="">All Semesters</option>
          @foreach($semesters as $s)
            <option value="{{ $s->id }}" {{ request('semester_id')==$s->id?'selected':'' }}>{{ $s->name }}</option>
          @endforeach
        </select>
      </div>
      <button class="btn btn-primary">Filter</button>
    </form>
  </div>
</div>
<div class="card">
  <x-data-table
    :headers="['#','Student','Batch','Semester','CGPA','Grade','Published','Published Date','Actions']"
    :export-pdf-url="route('admin.reports.examination.export','pdf')"
    :export-url="route('admin.reports.examination.export','excel')"
  >
    @forelse($results as $r)
    <tr>
      <td><input type="checkbox" name="ids[]" value="{{ $r->id }}"></td>
      <td>{{ $r->student->name ?? '—' }}</td>
      <td>{{ $r->batch->name ?? '—' }}</td>
      <td>{{ $r->semester->name ?? '—' }}</td>
      <td><strong>{{ $r->cgpa ?? '—' }}</strong></td>
      <td><span class="badge badge-blue">{{ $r->overall_grade ?? '—' }}</span></td>
      <td>
        @if($r->is_published)
          <span class="badge badge-green">Published</span>
        @else
          <form method="POST" action="{{ route('admin.results.publish',$r) }}" style="display:inline">
            @csrf
            <button class="btn btn-sm btn-success">Publish</button>
          </form>
        @endif
      </td>
      <td>{{ $r->published_at?->format('d M Y') ?? '—' }}</td>
      <td>
        @if($r->student_id)
          <a href="{{ route('admin.results.transcript', $r->student_id) }}" class="btn btn-sm btn-outline">📄 Transcript</a>
        @endif
      </td>
    </tr>
    @empty
    <tr><td colspan="9" style="text-align:center;padding:30px;color:#9ca3af">কোনো result নেই।</td></tr>
    @endforelse
  </x-data-table>
  <div class="pagination">{{ $results->links() }}</div>
</div>
@endsection
