@extends('layouts.admin')
@section('title','Notices')
@section('page-title','Notice Management')
@section('content')
<div class="page-header">
  <div><div class="page-title">Notices</div></div>
  <a href="{{ route('admin.notices.create') }}" class="btn btn-primary">+ New Notice</a>
</div>
<div class="card">
  <x-data-table :headers="['#','Title','Scope','Author','Published','Actions']">
    @forelse($notices as $n)
    <tr>
      <td><input type="checkbox" name="ids[]" value="{{ $n->id }}"></td>
      <td>{{ $loop->iteration }}</td>
      <td style="font-weight:600">{{ $n->title }}</td>
      <td><span class="badge badge-blue">{{ ucfirst($n->scope) }}</span>
        @if($n->batch) <span style="font-size:11px;color:#9ca3af">→ {{ $n->batch->name }}</span> @endif
      </td>
      <td>{{ $n->author->name ?? '—' }}</td>
      <td style="font-size:12px">{{ $n->published_at?->format('d M Y') }}</td>
      <td>
        <form method="POST" action="{{ route('admin.notices.destroy',$n) }}" style="display:inline" onsubmit="return confirm('Delete?')">
          @csrf @method('DELETE') <button class="btn btn-sm btn-danger">Delete</button>
        </form>
      </td>
    </tr>
    @empty
    <tr><td colspan="6" style="text-align:center;padding:30px;color:#9ca3af">কোনো notice নেই।</td></tr>
    @endforelse
  </x-data-table>
  <div class="pagination">{{ $notices->links() }}</div>
</div>
@endsection
