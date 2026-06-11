@extends('layouts.admin')
@section('title','Subjects') @section('page-title','Subjects')
@section('content')
<div class="page-header"><div class="page-title">Subjects</div><a href="{{ route('admin.subjects.create') }}" class="btn btn-primary">+ New</a></div>
<div class="card">
  <x-data-table :headers="['#','Code','Name','Course','Credits','Status','Actions']">
    @forelse($subjects as $s)
    <tr>
      <td><input type="checkbox" name="ids[]" value="{{ $s->id }}"></td>
      <td>{{ $loop->iteration }}</td>
      <td style="font-family:monospace;font-size:12px">{{ $s->code ?? '—' }}</td>
      <td style="font-weight:600">{{ $s->name }}</td>
      <td>{{ $s->course->name ?? '—' }}</td>
      <td>{{ $s->credit_hours }}</td>
      <td><span class="badge {{ $s->status==='active'?'badge-green':'badge-gray' }}">{{ ucfirst($s->status) }}</span></td>
      <td style="white-space:nowrap">
        <a href="{{ route('admin.subjects.edit',$s) }}" class="btn btn-sm btn-outline">Edit</a>
        <form method="POST" action="{{ route('admin.subjects.destroy',$s) }}" style="display:inline" onsubmit="return confirm('Delete?')">
          @csrf @method('DELETE') <button class="btn btn-sm btn-danger">Delete</button>
        </form>
      </td>
    </tr>
    @empty
    <tr><td colspan="7" style="text-align:center;padding:30px;color:#9ca3af">কোনো subject নেই।</td></tr>
    @endforelse
  </x-data-table>
  <div class="pagination">{{ $subjects->links() }}</div>
</div>
@endsection
