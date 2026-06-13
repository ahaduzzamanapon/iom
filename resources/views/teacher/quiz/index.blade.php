@extends('layouts.teacher')
@section('title','My Quiz Rooms')
@section('page-title','Quiz Management')
@section('content')
<div class="page-header">
  <div>
    <div class="page-title">🎯 My Quiz Rooms</div>
    <div class="page-sub">আপনার তৈরি করা সকল quiz room</div>
  </div>
  <a href="{{ route('teacher.quiz.create') }}" class="btn btn-primary">+ New Quiz Room</a>
</div>

<div class="card">
  <div class="dt-wrapper">
    <div class="dt-table-wrap">
      <table class="dt-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Title</th>
            <th>Room Code</th>
            <th>Batch</th>
            <th>Status</th>
            <th>Duration</th>
            <th>Attempts</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($quizzes as $q)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td><strong>{{ $q->title }}</strong></td>
            <td><code style="background:#f3f4f6;padding:2px 8px;border-radius:4px">{{ $q->room_code }}</code></td>
            <td>{{ $q->batch->name ?? 'সকল Batch' }}</td>
            <td>
              @php $sc=['active'=>'badge-green','closed'=>'badge-gray','draft'=>'badge-yellow']; @endphp
              <span class="badge {{ $sc[$q->status] ?? 'badge-gray' }}">{{ ucfirst($q->status) }}</span>
            </td>
            <td>{{ $q->duration_minutes }} min</td>
            <td>{{ $q->attempts_count }}</td>
            <td style="display:flex;gap:6px">
              <a href="{{ route('teacher.quiz.show', $q) }}" class="btn btn-sm btn-primary">Manage</a>
              <form method="POST" action="{{ route('teacher.quiz.destroy', $q) }}">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</button>
              </form>
            </td>
          </tr>
          @empty
          <tr><td colspan="8" style="text-align:center;padding:30px;color:#9ca3af">কোনো quiz room নেই।</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
  <div class="pagination">{{ $quizzes->links() }}</div>
</div>
@endsection
