@extends('layouts.student')
@section('title','Exam — ' . $exam->title)
@section('page-title','Exam: {{ $exam->title }}')
@section('content')
@push('styles')
<style>
#timer{font-size:22px;font-weight:800;color:#dc2626;font-family:monospace}
.option-label{display:flex;align-items:center;gap:10px;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:8px;cursor:pointer;transition:.2s;margin-bottom:8px}
.option-label:hover{background:#f0f4f8;border-color:#4fc3f7}
.option-label input[type=radio]{accent-color:#065f46}
</style>
@endpush
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
  <div>
    <div style="font-size:12px;color:#718096">{{ $exam->subject->name ?? '' }} | {{ $questions->count() }} Questions</div>
  </div>
  <div style="text-align:right">
    <div style="font-size:12px;color:#718096;margin-bottom:2px">Time Remaining</div>
    <div id="timer">
      @if($exam->duration_minutes)
        {{ sprintf('%02d:%02d', intdiv($secondsRemaining, 60), $secondsRemaining % 60) }}
      @else
        Unlimited
      @endif
    </div>
  </div>
</div>

<form method="POST" action="{{ route('student.exams.submit',$exam) }}" id="exam-form">
  @csrf
  @foreach($questions as $i => $q)
  <div class="card" style="margin-bottom:16px">
    <div class="card-body">
      <div style="font-weight:600;font-size:14px;margin-bottom:14px">{{ $i+1 }}. {{ $q->question_text }}</div>
      @foreach(['a'=>$q->option_a,'b'=>$q->option_b,'c'=>$q->option_c,'d'=>$q->option_d] as $key=>$val)
        @if($val)
        <label class="option-label">
          <input type="radio" name="answers[{{ $q->id }}]" value="{{ $key }}">
          <span style="font-size:13px">{{ strtoupper($key) }}. {{ $val }}</span>
        </label>
        @endif
      @endforeach
    </div>
  </div>
  @endforeach
  <div style="text-align:center;margin-top:24px">
    <button type="submit" class="btn btn-primary" style="padding:12px 40px;font-size:15px" onclick="return confirm('Submit করবেন? পরে পরিবর্তন করা যাবে না।')">
      📨 Submit Exam
    </button>
  </div>
</form>

@push('scripts')
<script>
let total = {{ $secondsRemaining ?? 999999 }};
const el = document.getElementById('timer');
if (total < 999999) {
  const t = setInterval(()=>{
    total--;
    if(total <= 0){ clearInterval(t); document.getElementById('exam-form').submit(); return; }
    const m = String(Math.floor(total/60)).padStart(2,'0');
    const s = String(total%60).padStart(2,'0');
    el.textContent = `${m}:${s}`;
    if(total < 300) el.style.color='#dc2626';
  }, 1000);
}
</script>
@endpush
@endsection
