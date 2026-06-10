@extends('layouts.teacher')
@section('title', 'Manage Quiz Room')
@section('page-title', 'Quiz Management')
@section('content')
<div class="page-header">
  <div>
    <div class="page-title">🎯 Manage: {{ $quiz->title }}</div>
    <div class="page-sub">রুম কোড: <code style="background:#e0f2fe;color:#0369a1;padding:2px 8px;border-radius:4px;font-weight:700">{{ $quiz->room_code }}</code></div>
  </div>
  <a href="{{ route('teacher.quiz.index') }}" class="btn btn-outline">Back to List</a>
</div>

<div class="form-grid" style="grid-template-columns:1fr 2fr;align-items:start">
  
  {{-- Left column: Quiz details --}}
  <div style="display:flex;flex-direction:column;gap:20px">
    <div class="card">
      <div class="card-header">
        <div class="card-title">Quiz Details</div>
      </div>
      <div class="card-body">
        <p style="margin-bottom:12px;font-size:14px"><strong>Batch:</strong> {{ $quiz->batch->name ?? 'সকল Batch' }}</p>
        <p style="margin-bottom:12px;font-size:14px"><strong>Duration:</strong> {{ $quiz->duration_minutes }} Minutes</p>
        <p style="margin-bottom:12px;font-size:14px"><strong>Starts:</strong> {{ $quiz->starts_at ? $quiz->starts_at->format('d M Y, h:i A') : 'N/A' }}</p>
        <p style="margin-bottom:12px;font-size:14px"><strong>Ends:</strong> {{ $quiz->ends_at ? $quiz->ends_at->format('d M Y, h:i A') : 'N/A' }}</p>
        <p style="margin-bottom:12px;font-size:14px"><strong>Status:</strong> 
          @php $sc=['active'=>'badge-green','closed'=>'badge-gray','draft'=>'badge-yellow']; @endphp
          <span class="badge {{ $sc[$quiz->status] ?? 'badge-gray' }}">{{ ucfirst($quiz->status) }}</span>
        </p>
        @if($quiz->description)
          <div style="margin-top:12px;padding-top:12px;border-top:1px solid #e2e8f0;font-size:13px;color:#4a5568">
            <strong>Description:</strong><br>
            {{ $quiz->description }}
          </div>
        @endif
      </div>
    </div>

    {{-- Add Question form --}}
    <div class="card">
      <div class="card-header">
        <div class="card-title">➕ Add Question</div>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('teacher.quiz.questions.store', $quiz) }}">
          @csrf
          <div class="form-group">
            <label class="form-label" for="question">Question Text *</label>
            <input type="text" name="question" id="question" class="form-control" required placeholder="প্রশ্ন লিখুন...">
          </div>
          
          <div class="form-group">
            <label class="form-label" for="option_a">Option A *</label>
            <input type="text" name="option_a" id="option_a" class="form-control" required placeholder="অপশন এ">
          </div>
          
          <div class="form-group">
            <label class="form-label" for="option_b">Option B *</label>
            <input type="text" name="option_b" id="option_b" class="form-control" required placeholder="অপশন বি">
          </div>

          <div class="form-group">
            <label class="form-label" for="option_c">Option C</label>
            <input type="text" name="option_c" id="option_c" class="form-control" placeholder="অপশন সি (ঐচ্ছিক)">
          </div>

          <div class="form-group">
            <label class="form-label" for="option_d">Option D</label>
            <input type="text" name="option_d" id="option_d" class="form-control" placeholder="অপশন ডি (ঐচ্ছিক)">
          </div>

          <div class="form-grid" style="grid-template-columns:1fr 1fr">
            <div class="form-group">
              <label class="form-label" for="correct_option">Correct *</label>
              <select name="correct_option" id="correct_option" class="form-control" required>
                <option value="a">A</option>
                <option value="b">B</option>
                <option value="c">C</option>
                <option value="d">D</option>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label" for="marks">Marks *</label>
              <input type="number" name="marks" id="marks" class="form-control" value="1" min="1" required>
            </div>
          </div>

          <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:10px">Add Question</button>
        </form>
      </div>
    </div>
  </div>

  {{-- Right column: Questions & Attempts --}}
  <div style="display:flex;flex-direction:column;gap:20px">
    
    {{-- Questions --}}
    <div class="card">
      <div class="card-header">
        <div class="card-title">Questions ({{ $quiz->questions->count() }})</div>
      </div>
      <div class="card-body" style="padding:0">
        @forelse($quiz->questions as $index => $q)
          <div style="padding:16px;border-bottom:1px solid #f0f4f8;display:flex;justify-content:space-between;align-items:start">
            <div style="flex:1">
              <div style="font-size:14px;font-weight:600;margin-bottom:8px">
                {{ $index + 1 }}. {{ $q->question }} 
                <span class="badge badge-blue" style="margin-left:8px">{{ $q->marks }} Marks</span>
              </div>
              <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px 16px;font-size:13px;color:#4a5568">
                <div style="{{ $q->correct_option === 'a' ? 'color:#16a34a;font-weight:600' : '' }}">A: {{ $q->option_a }}</div>
                <div style="{{ $q->correct_option === 'b' ? 'color:#16a34a;font-weight:600' : '' }}">B: {{ $q->option_b }}</div>
                @if($q->option_c)
                  <div style="{{ $q->correct_option === 'c' ? 'color:#16a34a;font-weight:600' : '' }}">C: {{ $q->option_c }}</div>
                @endif
                @if($q->option_d)
                  <div style="{{ $q->correct_option === 'd' ? 'color:#16a34a;font-weight:600' : '' }}">D: {{ $q->option_d }}</div>
                @endif
              </div>
            </div>
            <form method="POST" action="{{ route('teacher.quiz.questions.destroy', [$quiz, $q->id]) }}">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-danger" style="padding:4px 8px" onclick="return confirm('Delete this question?')">✕</button>
            </form>
          </div>
        @empty
          <div style="padding:30px;text-align:center;color:#9ca3af;font-size:13px">কোনো question যোগ করা হয়নি। পাশের ফর্ম থেকে যোগ করুন।</div>
        @endforelse
      </div>
    </div>

    {{-- Attempts --}}
    <div class="card">
      <div class="card-header">
        <div class="card-title">Student Attempts ({{ $quiz->attempts->count() }})</div>
      </div>
      <div class="dt-wrapper">
        <div class="dt-table-wrap">
          <table class="dt-table">
            <thead>
              <tr>
                <th>Student</th>
                <th>Score</th>
                <th>Percentage</th>
                <th>Submitted At</th>
              </tr>
            </thead>
            <tbody>
              @forelse($quiz->attempts as $att)
              <tr>
                <td><strong>{{ $att->user->name }}</strong></td>
                <td>{{ $att->score }} / {{ $att->total }}</td>
                <td>
                  @php
                    $pct = $att->total > 0 ? round(($att->score / $att->total) * 100) : 0;
                  @endphp
                  <div style="display:flex;align-items:center;gap:8px">
                    <div style="width:60px;background:#e2e8f0;height:6px;border-radius:3px;overflow:hidden">
                      <div style="width:{{ $pct }}%;background:#16a34a;height:100%"></div>
                    </div>
                    <span>{{ $pct }}%</span>
                  </div>
                </td>
                <td>{{ $att->submitted_at ? $att->submitted_at->format('d M Y, h:i A') : 'N/A' }}</td>
              </tr>
              @empty
              <tr>
                <td colspan="4" style="text-align:center;padding:24px;color:#9ca3af;font-size:13px">এখন পর্যন্ত কোনো student অংশগ্রহণ করেনি।</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection
