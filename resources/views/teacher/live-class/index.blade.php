@extends('layouts.teacher')
@section('title','Live Class History')
@section('page-title','Live Classes')
@section('content')
<div class="page-header">
  <div>
    <div class="page-title">🎥 Live Class History</div>
    <div class="page-sub">আপনার schedule করা সকল live class</div>
  </div>
  <a href="{{ route('teacher.live-class.create') }}" class="btn btn-primary">+ New Live Class</a>
</div>

<div class="card">
  <div class="dt-wrapper">
    <div class="dt-table-wrap">
      <table class="dt-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Title</th>
            <th>Batch / Subject</th>
            <th>Platform</th>
            <th>Scheduled</th>
            <th>Duration</th>
            <th>Meeting Link</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($liveClasses as $lc)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td><strong>{{ $lc->title }}</strong></td>
            <td>
              {{ $lc->batch->name ?? '—' }}
              <div style="font-size:11px;color:#718096">{{ $lc->module->subject->name ?? '—' }}</div>
            </td>
            <td>
              @php
                $providerColors = ['zoom'=>'badge-blue','google_meet'=>'badge-green','manual'=>'badge-gray'];
                $provider = $lc->meeting_provider ?? 'manual';
              @endphp
              <span class="badge {{ $providerColors[$provider] ?? 'badge-gray' }}">
                {{ $provider === 'zoom' ? '🎦 Zoom' : ($provider === 'google_meet' ? '📹 Meet' : '🔗 Manual') }}
              </span>
            </td>
            <td style="white-space:nowrap">
              @if($lc->scheduled_at)
                {{ $lc->scheduled_at->format('d M Y') }}<br>
                <span style="font-size:11px;color:#718096">{{ $lc->scheduled_at->format('h:i A') }}</span>
              @else
                —
              @endif
            </td>
            <td>{{ $lc->duration_mins ? $lc->duration_mins.' min' : '—' }}</td>
            <td>
              @php $link = $lc->zoom_link ?? $lc->meet_link ?? $lc->meeting_link; @endphp
              @if($link)
                <a href="{{ $link }}" target="_blank" class="btn btn-sm btn-primary">🔗 Join</a>
              @else
                <span style="color:#aaa">—</span>
              @endif
            </td>
            <td>
              <form method="POST" action="{{ route('teacher.live-class.destroy', $lc) }}">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-danger" onclick="return confirm('Delete করবেন?')">Delete</button>
              </form>
            </td>
          </tr>
          @empty
          <tr><td colspan="8" style="text-align:center;padding:30px;color:#9ca3af">কোনো live class নেই।</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
  <div class="pagination">{{ $liveClasses->links() }}</div>
</div>
@endsection
