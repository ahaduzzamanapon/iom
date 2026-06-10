@extends('layouts.admin')

@section('title', 'Quiz Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0">🎯 Quiz Rooms</h2>
    <a href="{{ route('admin.quiz.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> নতুন Quiz Room
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Title</th>
                    <th>Room Code</th>
                    <th>Batch</th>
                    <th>Status</th>
                    <th>Attempts</th>
                    <th>Duration</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($quizzes as $quiz)
                <tr>
                    <td><strong>{{ $quiz->title }}</strong></td>
                    <td><code class="fs-6">{{ $quiz->room_code }}</code></td>
                    <td>{{ $quiz->batch?->name ?? 'সকল Batch' }}</td>
                    <td>
                        <span class="badge bg-{{ $quiz->status === 'active' ? 'success' : ($quiz->status === 'closed' ? 'secondary' : 'warning') }}">
                            {{ ucfirst($quiz->status) }}
                        </span>
                    </td>
                    <td>{{ $quiz->attempts_count }}</td>
                    <td>{{ $quiz->duration_minutes }} min</td>
                    <td>
                        <a href="{{ route('admin.quiz.show', $quiz) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('admin.quiz.edit', $quiz) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="{{ route('admin.quiz.leaderboard', $quiz) }}" class="btn btn-sm btn-outline-info">
                            <i class="bi bi-trophy"></i>
                        </a>
                        <form action="{{ route('admin.quiz.destroy', $quiz) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('এই quiz room মুছে ফেলবেন?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">কোনো quiz room নেই।</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $quizzes->links() }}</div>
@endsection
