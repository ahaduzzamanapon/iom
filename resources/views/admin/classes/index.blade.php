@extends('layouts.admin')
@section('title','Classes') @section('page-title','Class Management')
@section('content')
<div class="page-header"><div class="page-title">Classes</div><a href="{{ route('admin.classes.create') }}" class="btn btn-primary">+ New Class</a></div>
<div class="card">
  <x-data-table :headers="['#','Title','Module','Batch','Type','Scheduled','Published','Actions']">
    @forelse($classes as $c)
    <tr>
      <td><input type="checkbox" name="ids[]" value="{{ $c->id }}"></td>
      <td style="font-weight:600">{{ $c->title }}</td>
      <td style="font-size:12px">{{ $c->module->name ?? '—' }}</td>
      <td style="font-size:12px">{{ $c->batch->name ?? '—' }}</td>
      <td><span class="badge {{ $c->type==='live'?'badge-blue':($c->type==='video'?'badge-red':'badge-gray') }}">{{ strtoupper($c->type) }}</span></td>
      <td style="font-size:12px">{{ $c->scheduled_at?->format('d M, h:i A') ?? '—' }}</td>
      <td><span class="badge {{ $c->is_published?'badge-green':'badge-gray' }}">{{ $c->is_published?'Yes':'No' }}</span></td>
      <td style="white-space:nowrap">
        <a href="{{ route('admin.classes.edit',$c) }}" class="btn btn-sm btn-outline">Edit</a>
        <form method="POST" action="{{ route('admin.classes.destroy',$c) }}" style="display:inline" onsubmit="return confirm('Delete?')">
          @csrf @method('DELETE') <button class="btn btn-sm btn-danger">Del</button>
        </form>
      </td>
    </tr>
    @empty
    <tr><td colspan="8" style="text-align:center;padding:30px;color:#9ca3af">কোনো class নেই।</td></tr>
    @endforelse
  </x-data-table>
  <div class="pagination">{{ $classes->links() }}</div>
</div>
@endsection
