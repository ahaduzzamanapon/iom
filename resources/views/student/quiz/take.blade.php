@extends('layouts.student')

@section('title', 'Quiz — ' . $quiz->title)

@section('content')
<div class="mb-4">
    <h2 class="fw-bold">🎯 {{ $quiz->title }}</h2>
    <div class="d-flex gap-3 text-muted small">
        <span><i class="bi bi-clock me-1"></i> {{ $quiz->duration_minutes }} minutes</span>
        <span><i class="bi bi-question-circle me-1"></i> {{ $questions->count() }} প্রশ্ন</span>
    </div>
</div>

<form action="{{ route('student.quiz.submit', $quiz) }}" method="POST" id="quizForm">
    @csrf

    @foreach($questions as $i => $q)
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <p class="fw-semibold mb-3">
                <span class="badge bg-primary me-2">{{ $i + 1 }}</span>
                {{ $q->question }}
                <span class="badge bg-secondary ms-2">{{ $q->marks }} mark</span>
            </p>
            <div class="row g-2">
                @foreach(['a' => $q->option_a, 'b' => $q->option_b, 'c' => $q->option_c, 'd' => $q->option_d] as $key => $val)
                    @if($val)
                    <div class="col-md-6">
                        <label class="d-flex align-items-center gap-2 border rounded p-2 cursor-pointer option-label"
                               for="q{{ $q->id }}_{{ $key }}">
                            <input type="radio" name="answers[{{ $q->id }}]" value="{{ $key }}"
                                   id="q{{ $q->id }}_{{ $key }}" class="form-check-input m-0">
                            <span><strong>{{ strtoupper($key) }}.</strong> {{ $val }}</span>
                        </label>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    @endforeach

    <div class="d-flex justify-content-between align-items-center mt-4">
        <div id="timer" class="fs-5 fw-bold text-danger"></div>
        <button type="submit" class="btn btn-success btn-lg px-5"
                onclick="return confirm('Quiz submit করবেন? একবার submit করলে পরিবর্তন করা যাবে না।')">
            <i class="bi bi-send-fill me-2"></i> Submit Quiz
        </button>
    </div>
</form>

@push('scripts')
<script>
// Timer
let seconds = {{ $quiz->duration_minutes * 60 }};
const timerEl = document.getElementById('timer');
const interval = setInterval(() => {
    seconds--;
    const m = Math.floor(seconds / 60);
    const s = seconds % 60;
    timerEl.textContent = `⏱ ${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')} বাকি`;
    if (seconds <= 0) {
        clearInterval(interval);
        document.getElementById('quizForm').submit();
    }
}, 1000);

// Highlight selected option
document.querySelectorAll('input[type=radio]').forEach(radio => {
    radio.addEventListener('change', function() {
        const name = this.name;
        document.querySelectorAll(`input[name="${name}"]`).forEach(r => {
            r.closest('label').classList.remove('border-primary', 'bg-primary', 'bg-opacity-10');
        });
        this.closest('label').classList.add('border-primary', 'bg-primary', 'bg-opacity-10');
    });
});
</script>
@endpush

<style>
.option-label { cursor: pointer; transition: all 0.15s; }
.option-label:hover { border-color: #0d6efd !important; background: rgba(13,110,253,.05); }
</style>
@endsection
