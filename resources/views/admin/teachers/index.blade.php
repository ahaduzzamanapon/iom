@extends('layouts.admin')
@section('title','Teachers')
@section('page-title','Teacher Management')
@section('content')
<div class="page-header">
  <div><div class="page-title">Teachers</div></div>
  <a href="{{ route('admin.teachers.create') }}" class="btn btn-primary">+ Add Teacher</a>
</div>
<div class="card">
  <x-data-table :headers="['#','Teacher ID','Name','Email','Specialization','Status','Actions']">
    @forelse($teachers as $t)
    <tr>
      <td><input type="checkbox" name="ids[]" value="{{ $t->id }}"></td>
      <td>{{ $loop->iteration }}</td>
      <td style="font-family:monospace;font-weight:600;color:#1a5276">{{ $t->teacher_id }}</td>
      <td>{{ $t->user->name ?? '—' }}</td>
      <td style="font-size:12px">{{ $t->user->email ?? '—' }}</td>
      <td>{{ $t->specialization ?? '—' }}</td>
      <td><span class="badge {{ $t->status==='active'?'badge-green':'badge-gray' }}">{{ ucfirst($t->status) }}</span></td>
      <td style="white-space:nowrap">
        <a href="{{ route('admin.teachers.edit',$t) }}" class="btn btn-sm btn-outline">Edit</a>
        <form method="POST" action="{{ route('admin.teachers.destroy',$t) }}" style="display:inline" onsubmit="return confirm('Delete?')">
          @csrf @method('DELETE') <button class="btn btn-sm btn-danger">Delete</button>
        </form>
      </td>
    </tr>
    @empty
    <tr><td colspan="7" style="text-align:center;padding:30px;color:#9ca3af">কোনো teacher নেই।</td></tr>
    @endforelse
  </x-data-table>
  <div class="pagination">{{ $teachers->links() }}</div>
</div>
@endsection
