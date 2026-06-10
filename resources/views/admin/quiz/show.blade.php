@extends('layouts.admin')

@section('title', $quiz->title)

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.quiz.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> ফিরে যান
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="row g-4">
    <!-- Quiz Info -->
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold">{{ $quiz->title }}</h5>
                <p class="text-muted small">{{ $quiz->description }}</p>
                <hr>
                <p><strong>Room Code:</strong> <code class="fs-5">{{ $quiz->room_code }}</code></p>
                <p><strong>Batch:</strong> {{ $quiz->batch?->name ?? 'সকল Batch' }}</p>
                <p><strong>Duration:</strong> {{ $quiz->duration_minutes }} minutes</p>
                <p><strong>Status:</strong>
                    <span class="badge bg-{{ $quiz->status === 'active' ? 'success' : ($quiz->status === 'closed' ? 'secondary' : 'warning') }}">
                        {{ ucfirst($quiz->status) }}
                    </span>
                </p>
                <p><strong>Attempts:</strong> {{ $quiz->attempts->count() }}</p>
                <a href="{{ route('admin.quiz.leaderboard', $quiz) }}" class="btn btn-info btn-sm mt-2">
                    <i class="bi bi-trophy me-1"></i> Leaderboard
                </a>
                <a href="{{ route('admin.quiz.edit', $quiz) }}" class="btn btn-secondary btn-sm mt-2">
                    <i class="bi bi-pencil me-1"></i> Edit
                </a>
            </div>
        </div>
    </div>

    <!-- Questions -->
    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header fw-semibold">প্রশ্ন যোগ করুন</div>
            <div class="card-body">
                <form action="{{ route('admin.quiz.questions.store', $quiz) }}" method="POST">
                    @csrf
                    <div class="row g-2">
                        <div class="col-12">
                            <textarea name="question" class="form-control" rows="2" placeholder="প্রশ্ন লিখুন..." required></textarea>
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="option_a" class="form-control" placeholder="Option A *" required>
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="option_b" class="form-control" placeholder="Option B *" required>
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="option_c" class="form-control" placeholder="Option C (optional)">
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="option_d" class="form-control" placeholder="Option D (optional)">
                        </div>
                        <div class="col-md-4">
                            <select name="correct_option" class="form-select" required>
                                <option value="">Correct Option *</option>
                                <option value="a">A</option>
                                <option value="b">B</option>
                                <option value="c">C</option>
                                <option value="d">D</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="number" name="marks" class="form-control" placeholder="Marks" value="1" min="1" required>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100">+ Add Question</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Question List -->
        <div class="card shadow-sm">
            <div class="card-header fw-semibold">সব প্রশ্ন ({{ $quiz->questions->count() }})</div>
            <div class="list-group list-group-flush">
                @forelse($quiz->questions as $i => $q)
                <div class="list-group-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="badge bg-secondary me-1">{{ $i + 1 }}</span>
                            <strong>{{ $q->question }}</strong>
                            <div class="mt-1 small text-muted">
                                A: {{ $q->option_a }} | B: {{ $q->option_b }}
                                @if($q->option_c) | C: {{ $q->option_c }} @endif
                                @if($q->option_d) | D: {{ $q->option_d }} @endif
                            </div>
                            <span class="badge bg-success mt-1">✓ {{ strtoupper($q->correct_option) }}</span>
                            <span class="badge bg-info text-dark ms-1">{{ $q->marks }} mark</span>
                        </div>
                        <form action="{{ route('admin.quiz.questions.destroy', [$quiz, $q->id]) }}" method="POST"
                              onsubmit="return confirm('মুছে ফেলবেন?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-x-lg"></i></button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="list-group-item text-center text-muted py-3">এখনো কোনো প্রশ্ন যোগ করা হয়নি।</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
