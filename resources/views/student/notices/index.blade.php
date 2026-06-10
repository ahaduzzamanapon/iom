@extends('layouts.student')
@section('title','Notices')
@section('page-title','Notices')
@section('content')
<div class="card">
  @forelse($notices as $n)
  <div style="padding:18px 20px;border-bottom:1px solid #f0f4f8">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px">
      <div>
        <div style="font-weight:700;font-size:14px;margin-bottom:6px">{{ $n->title }}</div>
        <div style="font-size:13px;color:#4a5568;line-height:1.6">{{ $n->body }}</div>
      </div>
      <div style="flex-shrink:0;text-align:right">
        <span class="badge badge-blue">{{ ucfirst($n->scope) }}</span>
        <div style="font-size:11px;color:#9ca3af;margin-top:4px">{{ $n->published_at?->format('d M Y') }}</div>
      </div>
    </div>
  </div>
  @empty
  <div style="text-align:center;padding:40px;color:#9ca3af">কোনো notice নেই।</div>
  @endforelse
  <div class="pagination">{{ $notices->links() }}</div>
</div>
@endsection
