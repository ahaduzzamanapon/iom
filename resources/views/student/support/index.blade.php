@extends('layouts.student')
@section('title','Support')
@section('page-title','Support Tickets')
@section('content')
<div class="page-header">
  <div class="page-title">Support</div>
  <a href="{{ route('student.support.create') }}" class="btn btn-primary">+ New Ticket</a>
</div>
<div class="card">
  @forelse($tickets as $t)
  <div style="padding:16px 20px;border-bottom:1px solid #f0f4f8">
    <div style="font-weight:600">{{ $t->title }}</div>
    <div style="font-size:12px;color:#718096;margin-top:4px">{{ $t->created_at->format('d M Y') }}</div>
    @if($t->admin_reply)
      <div style="margin-top:10px;padding:10px;background:#f0fdf4;border-radius:8px;border-left:3px solid #16a34a">
        <div style="font-size:11px;font-weight:600;color:#16a34a;margin-bottom:3px">Admin Reply</div>
        <div style="font-size:13px">{{ $t->admin_reply }}</div>
      </div>
    @endif
    <div style="margin-top:8px"><span class="badge {{ $t->status==='open'?'badge-yellow':'badge-gray' }}">{{ ucfirst($t->status) }}</span></div>
  </div>
  @empty
  <div style="text-align:center;padding:40px;color:#9ca3af">কোনো ticket নেই। নতুন ticket খুলুন।</div>
  @endforelse
</div>
@endsection
