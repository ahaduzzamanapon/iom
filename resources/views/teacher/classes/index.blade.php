@extends('layouts.teacher')
@section('title','My Classes')
@section('content')
<div class="page-header">
  <div><div class="page-title">My Classes</div><div class="page-sub">ক্লাস লেসন পরিচালনা করুন</div></div>
  <div style="display:flex;gap:10px">
    <a href="{{ route('teacher.live-class.create') }}" class="btn btn-primary">🎥 Schedule Live Class</a>
    <a href="{{ route('teacher.classes.create') }}" class="btn btn-outline">+ Recorded/Text Class</a>
  </div>
</div>

@if(session('success'))
  <div class="alert alert-success" style="margin-bottom:16px;padding:12px 16px;background:#d1fae5;border:1px solid #6ee7b7;border-radius:8px;color:#065f46">
    {{ session('success') }}
  </div>
@endif
@if(session('error'))
  <div class="alert alert-danger" style="margin-bottom:16px;padding:12px 16px;background:#fee2e2;border:1px solid #fca5a5;border-radius:8px;color:#991b1b">
    {{ session('error') }}
  </div>
@endif

<div class="card">
  <x-data-table :headers="['#','Title','Batch','Type','Platform','Meeting Link','Scheduled','Action']">
    @forelse($classes as $c)
    <tr>
      <td><input type="checkbox" name="ids[]" value="{{ $c->id }}"></td>
      <td>{{ $loop->iteration }}</td>
      <td>
        <div style="font-weight:600">{{ $c->title }}</div>
        <div style="font-size:11px;color:#9ca3af">{{ $c->module->subject->name ?? '' }}</div>
      </td>
      <td>{{ $c->batch->name ?? '—' }}</td>
      <td>
        <span class="badge {{ $c->type==='live'?'badge-green':($c->type==='recorded'?'badge-blue':'badge-gray') }}">
          {{ ucfirst($c->type) }}
        </span>
      </td>
      <td>
        @if($c->meeting_provider === 'zoom')
          <span style="display:inline-flex;align-items:center;gap:4px;font-size:12px;font-weight:600;color:#2D8CFF">🎥 Zoom</span>
        @elseif($c->meeting_provider === 'google_meet')
          <span style="display:inline-flex;align-items:center;gap:4px;font-size:12px;font-weight:600;color:#34A853">📹 Google Meet</span>
        @elseif($c->meeting_provider === 'manual')
          <span style="font-size:12px;color:#9ca3af">🔗 Manual</span>
        @else
          —
        @endif
      </td>
      <td>
        @php
          $joinLink  = $c->zoom_link ?: $c->meet_link;
          $startLink = $c->zoom_start_url;
        @endphp
        @if($joinLink)
          <a href="{{ $joinLink }}" target="_blank" class="btn btn-sm btn-primary" style="font-size:11px;padding:3px 8px">
            Join
          </a>
          @if($startLink)
            <a href="{{ $startLink }}" target="_blank" class="btn btn-sm btn-outline" style="font-size:11px;padding:3px 8px;margin-left:4px" title="Start as Host">
              Host ↗
            </a>
          @endif
        @else
          <span style="color:#9ca3af;font-size:12px">—</span>
        @endif
      </td>
      <td style="font-size:12px">
        {{ $c->scheduled_at ? $c->scheduled_at->format('d M Y') : '—' }}
        @if($c->scheduled_at)
          <div style="color:#9ca3af">{{ $c->scheduled_at->format('h:i A') }}</div>
        @endif
      </td>
      <td>
        <form method="POST" action="{{ route('teacher.classes.destroy',$c) }}" onsubmit="return confirm('Delete?')">
          @csrf @method('DELETE') <button class="btn btn-sm btn-danger">Delete</button>
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
