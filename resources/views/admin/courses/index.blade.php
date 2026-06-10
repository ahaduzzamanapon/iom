@extends('layouts.admin')
@section('title','Courses')
@section('page-title','Course Management')

@section('content')
<div class="page-header">
  <div>
    <div class="page-title">Courses</div>
    <div class="page-sub">সকল Course পরিচালনা করুন</div>
  </div>
  <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">+ New Course</a>
</div>

<div class="card">
  <x-data-table
    :headers="['#','Name','Type','Duration','Batches','Subjects','Status','Actions']"
    :export-pdf-url="route('admin.reports.students.export','pdf')"
    :export-url="route('admin.reports.students.export','excel')"
  >
    @forelse($courses as $c)
    <tr>
      <td><input type="checkbox" name="ids[]" value="{{ $c->id }}"></td>
      <td>
        <div style="font-weight:600">{{ $c->name }}</div>
        @if($c->name_bn)<div style="font-size:11px;color:#718096">{{ $c->name_bn }}</div>@endif
      </td>
      <td><span class="badge badge-blue">{{ ucfirst(str_replace('_',' ',$c->type)) }}</span></td>
      <td>{{ $c->duration_years }} বছর</td>
      <td>{{ $c->batches_count }}</td>
      <td>{{ $c->subjects_count }}</td>
      <td>
        <span class="badge {{ $c->status==='active' ? 'badge-green' : 'badge-gray' }}">
          {{ ucfirst($c->status) }}
        </span>
      </td>
      <td style="white-space:nowrap">
        <a href="{{ route('admin.courses.edit',$c) }}" class="btn btn-sm btn-outline">Edit</a>
        <form method="POST" action="{{ route('admin.courses.destroy',$c) }}" style="display:inline"
              onsubmit="return confirm('Delete করবেন?')">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-danger">Delete</button>
        </form>
      </td>
    </tr>
    @empty
    <tr><td colspan="8" style="text-align:center;padding:30px;color:#9ca3af">কোনো course নেই।</td></tr>
    @endforelse
  </x-data-table>
  <div class="pagination">{{ $courses->links() }}</div>
</div>
@endsection
