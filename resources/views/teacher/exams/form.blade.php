@extends('layouts.teacher')
@section('title','New Exam')
@section('content')
<div class="page-header">
  <div class="page-title">New Exam</div>
  <a href="{{ route('teacher.exams.index') }}" class="btn btn-outline">← Back</a>
</div>
<div class="card" style="max-width:680px">
  <div class="card-body">
    <form method="POST" action="{{ route('teacher.exams.store') }}">
      @csrf
      @if($errors->any())
        <div class="alert alert-danger" style="margin-bottom:16px">
          @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
        </div>
      @endif

      <div class="form-group">
        <label class="form-label">Title <span style="color:red">*</span></label>
        <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div class="form-group">
          <label class="form-label">Batch <span style="color:red">*</span></label>
          <select name="batch_id" id="batch_id" class="form-control" required>
            <option value="">-- Select Batch --</option>
            @foreach($batches as $b)
              <option value="{{ $b->id }}" {{ old('batch_id') == $b->id ? 'selected':'' }}>
                {{ $b->name }} ({{ $b->course->name ?? '' }})
              </option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Subject <span style="color:red">*</span></label>
          <select name="subject_id" id="subject_id" class="form-control" required>
            <option value="">-- Select Subject --</option>
            @foreach($subjects as $s)
              <option value="{{ $s->id }}" {{ old('subject_id') == $s->id ? 'selected':'' }}>{{ $s->name }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div class="form-group">
          <label class="form-label">Semester</label>
          <select name="semester_id" class="form-control">
            <option value="">-- Optional --</option>
            @foreach($semesters as $sm)
              <option value="{{ $sm->id }}" {{ old('semester_id') == $sm->id ? 'selected':'' }}>{{ $sm->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Type <span style="color:red">*</span></label>
          <select name="type" class="form-control" required>
            <option value="mcq" {{ old('type')==='mcq'?'selected':'' }}>MCQ</option>
            <option value="written" {{ old('type')==='written'?'selected':'' }}>Written</option>
            <option value="re_exam" {{ old('type')==='re_exam'?'selected':'' }}>Re-Exam</option>
          </select>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px">
        <div class="form-group">
          <label class="form-label">Total Marks <span style="color:red">*</span></label>
          <input type="number" name="total_marks" class="form-control" value="{{ old('total_marks',100) }}" min="1" required>
        </div>
        <div class="form-group">
          <label class="form-label">Pass Marks <span style="color:red">*</span></label>
          <input type="number" name="pass_marks" class="form-control" value="{{ old('pass_marks',40) }}" min="1" required>
        </div>
        <div class="form-group">
          <label class="form-label">Duration (min)</label>
          <input type="number" name="duration_minutes" class="form-control" value="{{ old('duration_minutes',60) }}" min="1">
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div class="form-group">
          <label class="form-label">Start At</label>
          <input type="datetime-local" name="start_at" class="form-control" value="{{ old('start_at') }}">
        </div>
        <div class="form-group">
          <label class="form-label">End At</label>
          <input type="datetime-local" name="end_at" class="form-control" value="{{ old('end_at') }}">
        </div>
      </div>

      <div style="display:flex;gap:12px">
        <button type="submit" class="btn btn-primary">Submit (Draft)</button>
        <a href="{{ route('teacher.exams.index') }}" class="btn btn-outline">Cancel</a>
      </div>
      <p style="font-size:12px;color:#9ca3af;margin-top:8px">* Submit করার পর Admin approve করলে exam publish হবে।</p>
    </form>
  </div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const batchSelect = document.getElementById('batch_id');
    const subjectSelect = document.getElementById('subject_id');
    
    const batchSubjects = {
        @foreach($batches as $b)
            "{{ $b->id }}": [
                @foreach($b->subjects as $s)
                    { id: "{{ $s->id }}", name: "{{ addslashes($s->name) }}" },
                @endforeach
            ],
        @endforeach
    };

    const selectedSubjectId = "{{ old('subject_id') }}";

    function updateSubjects() {
        const batchId = batchSelect.value;
        const subjects = batchSubjects[batchId] || [];
        
        subjectSelect.innerHTML = '<option value="">-- Select Subject --</option>';
        
        subjects.forEach(function(s) {
            const opt = document.createElement('option');
            opt.value = s.id;
            opt.textContent = s.name;
            if (s.id == selectedSubjectId) {
                opt.selected = true;
            }
            subjectSelect.appendChild(opt);
        });
    }

    batchSelect.addEventListener('change', updateSubjects);
    if (batchSelect.value) {
        updateSubjects();
    }
});
</script>
@endpush
@endsection
