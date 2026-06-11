@extends('layouts.admin')
@section('title','Modules') @section('page-title','Modules')
@section('content')
<div class="page-header"><div class="page-title">Modules</div><a href="{{ route('admin.modules.create') }}" class="btn btn-primary">+ New</a></div>
<div class="card">
  <x-data-table :headers="['#','Name','Subject','Course','Order','Classes','Actions']">
    @forelse($modules as $m)
    <tr>
      <td><input type="checkbox" name="ids[]" value="{{ $m->id }}"></td>
      <td>{{ $loop->iteration }}</td>
      <td style="font-weight:600">{{ $m->name }}</td>
      <td>{{ $m->subject->name ?? '—' }}</td>
      <td>{{ $m->subject->course->name ?? '—' }}</td>
      <td>{{ $m->order }}</td>
      <td>{{ $m->class_lessons_count }}</td>
      <td style="white-space:nowrap">
        <a href="{{ route('admin.modules.edit',$m) }}" class="btn btn-sm btn-outline">Edit</a>
        <form method="POST" action="{{ route('admin.modules.destroy',$m) }}" style="display:inline" onsubmit="return confirm('Delete?')">
          @csrf @method('DELETE') <button class="btn btn-sm btn-danger">Delete</button>
        </form>
      </td>
    </tr>
    @empty
    <tr><td colspan="7" style="text-align:center;padding:30px;color:#9ca3af">কোনো module নেই।</td></tr>
    @endforelse
  </x-data-table>
  <div class="pagination">{{ $modules->links() }}</div>
</div>
@endsection
