@extends('layouts.teacher')
@section('title', 'My Quiz Rooms')
@section('page-title', 'Quiz Management')
@section('content')

<div class="page-header">
  <div>
    <div class="page-title">🎯 My Quiz Rooms</div>
    <div class="page-sub">আপনার তৈরি করা সকল quiz room</div>
  </div>
  <a href="{{ route('teacher.quiz.create') }}" class="btn btn-primary">+ New Quiz Room</a>
</div>

{{-- Success and Error Alert Messages --}}
@if(session('success'))
  <div style="background:#def7ec; color:#03543f; padding:12px 16px; border-radius:6px; margin-bottom:20px; font-size:14px; font-weight:600; display:flex; align-items:center; gap:8px">
    🎉 {{ session('success') }}
  </div>
@endif

@if(session('error'))
  <div style="background:#fde8e8; color:#9b1c1c; padding:12px 16px; border-radius:6px; margin-bottom:20px; font-size:14px; font-weight:600; display:flex; align-items:center; gap:8px">
    ⚠️ {{ session('error') }}
  </div>
@endif

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
            <td>{{ ($quizzes->currentPage() - 1) * $quizzes->perPage() + $loop->iteration }}</td>
            <td><strong>{{ $q->title }}</strong></td>
            <td><code style="background:#f3f4f6;padding:2px 8px;border-radius:4px;font-weight:600;color:#1f2937">{{ $q->room_code }}</code></td>
            <td>{{ $q->batch->name ?? 'সকল Batch' }}</td>
            <td>
              @php 
                $sc = ['active' => 'badge-green', 'closed' => 'badge-gray', 'draft' => 'badge-yellow']; 
              @endphp
              <span class="badge {{ $sc[$q->status] ?? 'badge-gray' }}">{{ ucfirst($q->status) }}</span>
            </td>
            <td>{{ $q->duration_minutes }} min</td>
            <td>
              <span style="font-weight:600;color:#4b5563">{{ $q->attempts_count }}</span>
            </td>
            <td style="display:flex;gap:6px;align-items:center">
              <a href="{{ route('teacher.quiz.show', $q) }}" class="btn btn-sm btn-primary" style="padding:6px 12px;font-size:12px">Manage</a>
              
              {{-- Edit Button --}}
              <a href="{{ route('teacher.quiz.edit', $q) }}" class="btn btn-sm btn-outline" style="padding:6px 12px;font-size:12px;border:1px solid #d1d5db;color:#374151">Edit</a>
              
              {{-- Delete Button with Form --}}
              <form method="POST" action="{{ route('teacher.quiz.destroy', $q) }}" style="margin:0">
                @csrf 
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger" style="padding:6px 12px;font-size:12px" onclick="return confirm('আপনি কি নিশ্চিতভাবে এই কুইজ রুমটি মুছে ফেলতে চান?')">Delete</button>
              </form>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8" style="text-align:center;padding:40px;color:#9ca3af;font-size:14px">
              📭 কোনো quiz room তৈরি করা হয়নি। নতুন রুম তৈরি করতে ওপরের বাটনে ক্লিক করুন।
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
  
  {{-- Pagination Link Wrapper --}}
  @if($quizzes->hasPages())
    <div class="pagination" style="padding:15px;display:flex;justify-content:flex-end">
        {{ $quizzes->links('pagination::simple-bootstrap-4') }}
    </div>
  @endif
</div>

@endsection