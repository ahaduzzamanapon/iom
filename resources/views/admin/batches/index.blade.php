@extends('layouts.admin')
@section('title','Batches')
@section('page-title','Batch Management')
@section('content')
<div class="page-header">
  <div><div class="page-title">Batches</div></div>
  <a href="{{ route('admin.batches.create') }}" class="btn btn-primary">+ New Batch</a>
</div>
<div class="card">
  <x-data-table :headers="['#','Name','Course','Semester','Students','Status','Actions']">
    @forelse($batches as $b)
    <tr>
      <td><input type="checkbox" name="ids[]" value="{{ $b->id }}"></td>
      <td>{{ $loop->iteration }}</td>
      <td><div style="font-weight:600">{{ $b->name }}</div>@if($b->name_bn)<div style="font-size:11px;color:#9ca3af">{{ $b->name_bn }}</div>@endif</td>
      <td>{{ $b->course->name ?? '—' }}</td>
      <td>{{ $b->semester->name ?? '—' }}</td>
      <td>{{ $b->students_count }}</td>
      <td><span class="badge {{ $b->status==='active'?'badge-green':'badge-gray' }}">{{ ucfirst($b->status) }}</span></td>
      <td style="white-space:nowrap">
        <a href="{{ route('admin.batches.edit',$b) }}" class="btn btn-sm btn-outline">Edit</a>
        <form method="POST" action="{{ route('admin.batches.destroy',$b) }}" style="display:inline" onsubmit="return confirm('Delete?')">
          @csrf @method('DELETE') <button class="btn btn-sm btn-danger">Delete</button>
        </form>
      </td>
    </tr>
    @empty
    <tr><td colspan="7" style="text-align:center;padding:30px;color:#9ca3af">কোনো batch নেই।</td></tr>
    @endforelse
  </x-data-table>
  <div class="pagination">{{ $batches->links() }}</div>
</div>
@endsection
