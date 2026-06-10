@extends('layouts.admin')
@section('title','Notifications')
@section('page-title','Notifications')
@section('content')
<div class="page-header">
  <div class="page-title">🔔 Notifications</div>
  <form method="POST" action="{{ route('notifications.read-all') }}">
    @csrf
    <button class="btn btn-outline">✅ Mark All Read</button>
  </form>
</div>

<div class="card">
  <div class="dt-wrapper">
    <div class="dt-table-wrap">
      <table class="dt-table">
        <thead>
          <tr><th>Type</th><th>Title</th><th>Message</th><th>Time</th><th></th></tr>
        </thead>
        <tbody>
          @forelse($notifications as $n)
          <tr style="{{ !$n->is_read ? 'background:#f0f9ff;' : '' }}">
            <td><span class="badge badge-blue">{{ ucfirst($n->type) }}</span></td>
            <td>
              <strong>{{ $n->title }}</strong>
              @if(!$n->is_read)<span class="badge badge-red" style="margin-left:6px;font-size:10px">New</span>@endif
            </td>
            <td style="font-size:12px;color:#4a5568">{{ $n->message }}</td>
            <td style="white-space:nowrap;font-size:12px;color:#718096">{{ $n->created_at->diffForHumans() }}</td>
            <td>
              @if($n->action_url)
                <a href="{{ $n->action_url }}" class="btn btn-sm btn-primary">View</a>
              @endif
            </td>
          </tr>
          @empty
          <tr><td colspan="5" style="text-align:center;padding:40px;color:#9ca3af">কোনো notification নেই।</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
  <div class="pagination">{{ $notifications->links() }}</div>
</div>
@endsection
