@extends('layouts.teacher')
@section('title','Notices')
@section('content')
<div class="page-header">
  <div class="page-title">My Notices</div>
  <a href="{{ route('teacher.notices.create') }}" class="btn btn-primary">+ New Notice</a>
</div>
<div class="card">
  <x-data-table :headers="['#','Title','Type','Visible To','Expires','Actions']">
    @forelse($notices as $n)
    <tr>
      <td><input type="checkbox" name="ids[]" value="{{ $n->id }}"></td>
      <td style="font-weight:600">{{ $n->title }}</td>
      <td><span class="badge badge-blue">{{ ucfirst($n->type) }}</span></td>
      <td>{{ ucfirst($n->visible_to) }}</td>
      <td style="font-size:12px">{{ $n->expires_at ? \Carbon\Carbon::parse($n->expires_at)->format('d M Y') : '—' }}</td>
      <td>
        <form method="POST" action="{{ route('teacher.notices.destroy',$n) }}" onsubmit="return confirm('Delete?')">
          @csrf @method('DELETE') <button class="btn btn-sm btn-danger">Del</button>
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
