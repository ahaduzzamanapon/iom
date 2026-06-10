@extends('layouts.admin')
@section('title','Support Tickets')
@section('page-title','Support Tickets')
@section('content')
<div class="page-header">
  <div><div class="page-title">Support Tickets</div></div>
</div>
<div class="card" style="margin-bottom:16px">
  <div class="card-body">
    <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end">
      <div class="form-group" style="margin:0;min-width:140px">
        <label class="form-label">Status</label>
        <select name="status" class="form-control">
          <option value="">All</option>
          <option value="open" {{ request('status')==='open'?'selected':'' }}>Open</option>
          <option value="in_progress" {{ request('status')==='in_progress'?'selected':'' }}>In Progress</option>
          <option value="closed" {{ request('status')==='closed'?'selected':'' }}>Closed</option>
        </select>
      </div>
      <div class="form-group" style="margin:0;min-width:140px">
        <label class="form-label">Type</label>
        <select name="type" class="form-control">
          <option value="">All</option>
          <option value="student" {{ request('type')==='student'?'selected':'' }}>Student</option>
          <option value="public" {{ request('type')==='public'?'selected':'' }}>Public</option>
        </select>
      </div>
      <button class="btn btn-primary">Filter</button>
    </form>
  </div>
</div>
<div class="card">
  @forelse($tickets as $t)
  <div style="padding:20px;border-bottom:1px solid #f0f4f8">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:16px">
      <div style="flex:1">
        <div style="display:flex;gap:8px;align-items:center;margin-bottom:6px">
          <span style="font-weight:700;font-size:14px">{{ $t->title }}</span>
          <span class="badge {{ $t->status==='open'?'badge-red':($t->status==='closed'?'badge-gray':'badge-yellow') }}">{{ ucfirst(str_replace('_',' ',$t->status)) }}</span>
          <span class="badge badge-blue">{{ ucfirst($t->type) }}</span>
        </div>
        <p style="font-size:13px;color:#4a5568;margin-bottom:8px">{{ $t->description }}</p>
        <div style="font-size:11px;color:#9ca3af">By: {{ $t->user->name ?? 'Public' }} | {{ $t->created_at->format('d M Y, h:i A') }}</div>
        @if($t->admin_reply)
          <div style="margin-top:10px;padding:10px;background:#f7fafc;border-radius:8px;border-left:3px solid #4fc3f7">
            <div style="font-size:11px;font-weight:600;color:#4a5568;margin-bottom:4px">Admin Reply:</div>
            <div style="font-size:13px;color:#374151">{{ $t->admin_reply }}</div>
          </div>
        @endif
      </div>
      @if($t->status !== 'closed')
      <div style="min-width:280px">
        <form method="POST" action="{{ route('admin.support.reply',$t) }}">
          @csrf @method('PUT')
          <textarea name="admin_reply" class="form-control" rows="2" placeholder="Reply লিখুন..." required style="margin-bottom:8px"></textarea>
          <button class="btn btn-primary btn-sm">Reply & Close</button>
        </form>
      </div>
      @endif
    </div>
  </div>
  @empty
  <div style="text-align:center;padding:40px;color:#9ca3af">কোনো ticket নেই।</div>
  @endforelse
  <div class="pagination">{{ $tickets->links() }}</div>
</div>
@endsection
