@extends('layouts.student')
@section('title','Exams')
@section('page-title','My Exams')
@section('content')
<div class="page-header">
  <div>
    <div class="page-title">📝 My Exams</div>
    <div class="page-sub">আপনার জন্য নির্ধারিত পরীক্ষা ও মূল্যায়নসমূহ</div>
  </div>
</div>

@php
  $regularExams = $exams->filter(fn($e) => in_array($e->type, ['mcq', 'written']));
  $specialExams = $exams->filter(fn($e) => in_array($e->type, ['re_exam', 'improvement']));
@endphp

{{-- Regular Exams Section --}}
<div class="card" style="margin-bottom:24px">
  <div class="card-header" style="background:#f8fafc">
    <div class="card-title">📋 Regular Exams</div>
  </div>
  <div class="card-body" style="padding:0">
    @forelse($regularExams as $e)
    <div style="padding:18px 20px;border-bottom:1px solid #f0f4f8">
      <div style="display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap">
        <div>
          <div style="display:flex;align-items:center;gap:10px">
            <span style="font-weight:700;font-size:15px;color:#1e293b">{{ $e->title }}</span>
            <span class="badge badge-gray">{{ strtoupper($e->type) }}</span>
          </div>
          <div style="font-size:13px;color:#64748b;margin-top:6px">
            {{ $e->subject->name ?? '—' }} | ⏱️ {{ $e->duration_minutes }} Min | 💯 Total: {{ $e->total_marks }} | 🎯 Pass: {{ $e->pass_marks }}
          </div>
          @if($e->start_at)
            <div style="font-size:12px;color:#0ea5e9;margin-top:4px;font-weight:500">
              📅 Available: {{ $e->start_at->format('d M Y, h:i A') }} — {{ $e->end_at?->format('d M Y, h:i A') ?? 'N/A' }}
            </div>
          @endif
        </div>
        <div>
          @if(in_array($e->id, $attempts))
            <span class="badge badge-green" style="padding:6px 14px;font-size:12px">✓ Submitted</span>
          @else
            <form method="POST" action="{{ route('student.exams.start',$e) }}" onsubmit="return confirm('Exam শুরু করবেন? একবার শুরু করলে সময় চলতে থাকবে।')">
              @csrf
              <button class="btn btn-primary btn-sm" style="padding:8px 16px">Start Exam →</button>
            </form>
          @endif
        </div>
      </div>
    </div>
    @empty
    <div style="text-align:center;padding:40px;color:#94a3b8;font-size:14px">কোনো নিয়মিত পরীক্ষা নেই।</div>
    @endforelse
  </div>
</div>

{{-- Re-Exams & Improvement Section --}}
<div class="card">
  <div class="card-header" style="background:#fff7ed">
    <div class="card-title" style="color:#c2410c">🔄 Re-Exams & Improvements (পূনঃপরীক্ষা ও মানোন্নয়ন)</div>
  </div>
  <div class="card-body" style="padding:0">
    @forelse($specialExams as $e)
    <div style="padding:18px 20px;border-bottom:1px solid #f0f4f8">
      <div style="display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap">
        <div>
          <div style="display:flex;align-items:center;gap:10px">
            <span style="font-weight:700;font-size:15px;color:#1e293b">{{ $e->title }}</span>
            <span class="badge {{ $e->type === 're_exam' ? 'badge-red' : 'badge-blue' }}">
              {{ $e->type === 're_exam' ? 'Re-Exam' : 'Improvement' }}
            </span>
          </div>
          <div style="font-size:13px;color:#64748b;margin-top:6px">
            {{ $e->subject->name ?? '—' }} | ⏱️ {{ $e->duration_minutes }} Min | 💯 Total: {{ $e->total_marks }} | 🎯 Pass: {{ $e->pass_marks }}
          </div>
          @if($e->start_at)
            <div style="font-size:12px;color:#e07a5f;margin-top:4px;font-weight:500">
              📅 Available: {{ $e->start_at->format('d M Y, h:i A') }} — {{ $e->end_at?->format('d M Y, h:i A') ?? 'N/A' }}
            </div>
          @endif
        </div>
        <div>
          @if(in_array($e->id, $attempts))
            <span class="badge badge-green" style="padding:6px 14px;font-size:12px">✓ Submitted</span>
          @else
            <form method="POST" action="{{ route('student.exams.start',$e) }}" onsubmit="return confirm('Exam শুরু করবেন? একবার শুরু করলে সময় চলতে থাকবে।')">
              @csrf
              <button class="btn btn-primary btn-sm" style="padding:8px 16px;background:{{ $e->type === 're_exam' ? '#dc2626' : '#2563eb' }}">Start Exam →</button>
            </form>
          @endif
        </div>
      </div>
    </div>
    @empty
    <div style="text-align:center;padding:40px;color:#94a3b8;font-size:14px">কোনো পুনঃপরীক্ষা বা মানোন্নয়ন পরীক্ষা নেই।</div>
    @endforelse
  </div>
</div>
@endsection
