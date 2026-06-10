@extends('layouts.student')
@section('title','Support Ticket')
@section('content')
<div class="page-header">
  <div class="page-title">Ticket #{{ $support->id }}</div>
  <a href="{{ route('student.support.index') }}" class="btn btn-outline">← Back</a>
</div>

<div class="card">
  <div class="card-body">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:12px;margin-bottom:20px">
      <div>
        <div style="font-size:18px;font-weight:700">{{ $support->title }}</div>
        <div style="color:#9ca3af;font-size:13px;margin-top:4px">{{ $support->created_at->format('d M Y, h:i A') }}</div>
      </div>
      <span class="badge {{ $support->status==='open'?'badge-yellow':($support->status==='resolved'?'badge-green':'badge-blue') }}" style="font-size:13px;padding:6px 12px">
        {{ ucfirst($support->status) }}
      </span>
    </div>

    <div style="background:var(--sidebar-bg);border-radius:8px;padding:16px;font-size:14px;line-height:1.7;margin-bottom:20px">
      {{ $support->description }}
    </div>

    @if($support->admin_reply)
    <div style="border-left:4px solid var(--primary);padding:12px 16px;background:rgba(var(--primary-rgb),0.05);border-radius:0 8px 8px 0">
      <div style="font-size:12px;font-weight:700;color:var(--primary);margin-bottom:6px">Admin Reply</div>
      <div style="font-size:14px;line-height:1.7">{{ $support->admin_reply }}</div>
      @if($support->replied_at)
        <div style="font-size:11px;color:#9ca3af;margin-top:6px">{{ \Carbon\Carbon::parse($support->replied_at)->format('d M Y, h:i A') }}</div>
      @endif
    </div>
    @else
    <div style="color:#9ca3af;font-size:13px;font-style:italic">এখনো কোনো reply আসেনি। অনুগ্রহ করে অপেক্ষা করুন।</div>
    @endif
  </div>
</div>
@endsection
