@extends('layouts.admin')
@section('title','Fee Structures')
@section('page-title','Fee Structure Management')
@section('content')
<div class="page-header">
  <div><div class="page-title">Fee Structures</div><div class="page-sub">কোর্সের ফি কাঠামো পরিচালনা করুন</div></div>
  <a href="{{ route('admin.fee-structures.create') }}" class="btn btn-primary">+ New Fee Structure</a>
</div>
<div class="card">
  <x-data-table :headers="['#','Title','Course','Type','Amount','Status','Actions']">
    @forelse($fees as $f)
    <tr>
      <td><input type="checkbox" name="ids[]" value="{{ $f->id }}"></td>
      <td style="font-weight:600">{{ $f->title }}</td>
      <td>{{ $f->course->name ?? '—' }}</td>
      <td><span class="badge badge-blue">{{ ucfirst($f->type) }}</span></td>
      <td style="font-weight:600">৳ {{ number_format($f->amount, 2) }}</td>
      <td><span class="badge {{ $f->status==='active'?'badge-green':'badge-gray' }}">{{ ucfirst($f->status) }}</span></td>
      <td style="white-space:nowrap">
        <a href="{{ route('admin.fee-structures.edit',$f) }}" class="btn btn-sm btn-outline">Edit</a>
        <form method="POST" action="{{ route('admin.fee-structures.destroy',$f) }}" style="display:inline" onsubmit="return confirm('Delete?')">
          @csrf @method('DELETE') <button class="btn btn-sm btn-danger">Del</button>
        </form>
      </td>
    </tr>
    @empty
    <tr><td colspan="7" style="text-align:center;padding:30px;color:#9ca3af">কোনো fee structure নেই।</td></tr>
    @endforelse
  </x-data-table>
  <div class="pagination">{{ $fees->links() }}</div>
</div>
@endsection
