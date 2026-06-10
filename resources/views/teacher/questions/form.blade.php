@extends('layouts.teacher')
@section('title','Add Question')
@section('content')
<div class="page-header">
  <div class="page-title">Add Question</div>
  <a href="{{ route('teacher.questions.index') }}" class="btn btn-outline">← Back</a>
</div>
<div class="card" style="max-width:700px">
  <div class="card-body">
    <form method="POST" action="{{ route('teacher.questions.store') }}">
      @csrf
      @if($errors->any())
        <div class="alert alert-danger" style="margin-bottom:16px">
          @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
        </div>
      @endif

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div class="form-group">
          <label class="form-label">Exam</label>
          <select name="exam_id" class="form-control">
            <option value="">-- General Question Bank (No Exam) --</option>
            @foreach($exams as $e)
              <option value="{{ $e->id }}" {{ old('exam_id') == $e->id ? 'selected' : '' }}>{{ $e->title }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Subject <span style="color:red">*</span></label>
          <select name="subject_id" class="form-control" required>
            <option value="">-- Select Subject --</option>
            @foreach($subjects as $s)
              <option value="{{ $s->id }}" {{ old('subject_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Question <span style="color:red">*</span></label>
        <textarea name="question_text" class="form-control" rows="3" required>{{ old('question_text') }}</textarea>
      </div>

      <div style="background:var(--sidebar-bg);border-radius:8px;padding:16px;margin-bottom:16px">
        <div style="font-size:13px;font-weight:600;margin-bottom:12px;color:var(--text-primary)">MCQ Options (optional)</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
          <div class="form-group" style="margin:0">
            <label class="form-label">Option A</label>
            <input type="text" name="option_a" class="form-control" value="{{ old('option_a') }}">
          </div>
          <div class="form-group" style="margin:0">
            <label class="form-label">Option B</label>
            <input type="text" name="option_b" class="form-control" value="{{ old('option_b') }}">
          </div>
          <div class="form-group" style="margin:0">
            <label class="form-label">Option C</label>
            <input type="text" name="option_c" class="form-control" value="{{ old('option_c') }}">
          </div>
          <div class="form-group" style="margin:0">
            <label class="form-label">Option D</label>
            <input type="text" name="option_d" class="form-control" value="{{ old('option_d') }}">
          </div>
        </div>
        <div class="form-group" style="margin-top:12px;margin-bottom:0">
          <label class="form-label">Correct Answer</label>
          <select name="correct_answer" class="form-control">
            <option value="">-- None --</option>
            @foreach(['a','b','c','d'] as $opt)
              <option value="{{ $opt }}" {{ old('correct_answer') === $opt ? 'selected' : '' }}>Option {{ strtoupper($opt) }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Marks <span style="color:red">*</span></label>
        <input type="number" name="marks" class="form-control" value="{{ old('marks', 1) }}" min="1" style="max-width:120px" required>
      </div>

      <div style="display:flex;gap:12px">
        <button type="submit" class="btn btn-primary">Submit for Review</button>
        <a href="{{ route('teacher.questions.index') }}" class="btn btn-outline">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
