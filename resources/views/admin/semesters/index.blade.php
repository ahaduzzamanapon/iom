@extends('layouts.admin')
@section('title','Semesters') @section('page-title','Semesters')
@section('content')
<div class="page-header"><div class="page-title">Semesters</div><a href="{{ route('admin.semesters.create') }}" class="btn btn-primary">+ New</a></div>
<div class="card">
  <x-data-table :headers="['#','Name','Course','Order','Status','Actions']">
    @forelse($semesters as $s)
    <tr>
      <td><input type="checkbox" name="ids[]" value="{{ $s->id }}"></td>
      <td>{{ $loop->iteration }}</td>
      <td style="font-weight:600">{{ $s->name }}</td>
      <td>{{ $s->course->name ?? '—' }}</td>
      <td>{{ $s->order }}</td>
      <td><span class="badge {{ $s->status==='active'?'badge-green':'badge-gray' }}">{{ ucfirst($s->status) }}</span></td>
      <td style="white-space:nowrap">
        <a href="{{ route('admin.semesters.edit',$s) }}" class="btn btn-sm btn-outline">Edit</a>
        <form method="POST" action="{{ route('admin.semesters.destroy',$s) }}" style="display:inline" onsubmit="return confirm('Delete?')">
          @csrf @method('DELETE') <button class="btn btn-sm btn-danger">Delete</button>
        </form>
      </td>
    </tr>
    @empty
    <tr><td colspan="6" style="text-align:center;padding:30px;color:#9ca3af">কোনো semester নেই।</td></tr>
    @endforelse
  </x-data-table>
  <div class="pagination">{{ $semesters->links() }}</div>
</div>
@endsection
