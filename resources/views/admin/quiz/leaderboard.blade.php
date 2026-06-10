@extends('layouts.admin')

@section('title', 'Leaderboard — ' . $quiz->title)

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.quiz.show', $quiz) }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> ফিরে যান
    </a>
    <h2 class="fw-bold d-inline ms-3">🏆 Leaderboard — {{ $quiz->title }}</h2>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Rank</th>
                    <th>Student</th>
                    <th>Student ID</th>
                    <th>Score</th>
                    <th>Total</th>
                    <th>Percentage</th>
                    <th>Submitted At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attempts as $i => $attempt)
                <tr class="{{ $i === 0 ? 'table-warning fw-bold' : '' }}">
                    <td>
                        @if($i === 0) 🥇
                        @elseif($i === 1) 🥈
                        @elseif($i === 2) 🥉
                        @else {{ $i + 1 }}
                        @endif
                    </td>
                    <td>{{ $attempt->user->name }}</td>
                    <td>{{ $attempt->user->studentProfile?->student_id ?? '—' }}</td>
                    <td>{{ $attempt->score }}</td>
                    <td>{{ $attempt->total }}</td>
                    <td>
                        @php $pct = $attempt->total > 0 ? round(($attempt->score / $attempt->total) * 100) : 0; @endphp
                        <div class="progress" style="height:18px; min-width:80px">
                            <div class="progress-bar bg-{{ $pct >= 70 ? 'success' : ($pct >= 40 ? 'warning' : 'danger') }}"
                                 style="width:{{ $pct }}%">{{ $pct }}%</div>
                        </div>
                    </td>
                    <td>{{ $attempt->submitted_at?->format('d M Y H:i') ?? '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">কেউ এখনো quiz submit করেনি।</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
