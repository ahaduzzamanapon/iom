@extends('layouts.admin')
@section('title', isset($exam) ? 'Edit Exam' : 'New Exam')
@section('page-title', isset($exam) ? 'Edit Exam' : 'New Exam')
@section('content')
<div class="page-header">
  <div><div class="page-title">{{ isset($exam) ? 'Edit Exam' : 'New Exam' }}</div></div>
  <a href="{{ route('admin.exams.index') }}" class="btn btn-outline">← Back</a>
</div>
<div class="card"><div class="card-body">
  <form method="POST" action="{{ isset($exam) ? route('admin.exams.update',$exam) : route('admin.exams.store') }}">
    @csrf @if(isset($exam)) @method('PUT') @endif
    <div class="form-grid">
      <div class="form-group">
        <label class="form-label">Title *</label>
        <input type="text" name="title" class="form-control" value="{{ old('title',$exam->title??'') }}" required>
      </div>
      <div class="form-group">
        <label class="form-label">Type *</label>
        <select name="type" class="form-control" required>
          @foreach(['mcq'=>'MCQ','written'=>'Written','re_exam'=>'Re-Exam','improvement'=>'Improvement'] as $v=>$l)
            <option value="{{ $v }}" {{ old('type',$exam->type??'')===$v?'selected':'' }}>{{ $l }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Batch *</label>
        <select name="batch_id" id="batch_id" class="form-control" required>
          <option value="">Select Batch</option>
          @foreach($batches as $b)
            <option value="{{ $b->id }}" {{ old('batch_id',$exam->batch_id??'')==$b->id?'selected':'' }}>{{ $b->name }} / {{ $b->course->name??'' }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Subject *</label>
        <select name="subject_id" id="subject_id" class="form-control" required>
          <option value="">Select Subject</option>
          @foreach($subjects as $s)
            <option value="{{ $s->id }}" {{ old('subject_id',$exam->subject_id??'')==$s->id?'selected':'' }}>{{ $s->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Semester *</label>
        <select name="semester_id" class="form-control" required>
          <option value="">Select Semester</option>
          @foreach($semesters as $sm)
            <option value="{{ $sm->id }}" {{ old('semester_id',$exam->semester_id??'')==$sm->id?'selected':'' }}>{{ $sm->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Duration (minutes) *</label>
        <input type="number" name="duration_minutes" class="form-control" value="{{ old('duration_minutes',$exam->duration_minutes??60) }}" required>
      </div>
      <div class="form-group">
        <label class="form-label">Total Marks *</label>
        <input type="number" name="total_marks" class="form-control" value="{{ old('total_marks',$exam->total_marks??100) }}" required>
      </div>
      <div class="form-group">
        <label class="form-label">Pass Marks *</label>
        <input type="number" name="pass_marks" class="form-control" value="{{ old('pass_marks',$exam->pass_marks??40) }}" required>
      </div>
      <div class="form-group">
        <label class="form-label">Start Date/Time</label>
        <input type="datetime-local" name="start_at" class="form-control" value="{{ old('start_at', (isset($exam) && $exam->start_at) ? $exam->start_at->format('Y-m-d\TH:i') : '') }}">
      </div>
      <div class="form-group">
        <label class="form-label">End Date/Time</label>
        <input type="datetime-local" name="end_at" class="form-control" value="{{ old('end_at', (isset($exam) && $exam->end_at) ? $exam->end_at->format('Y-m-d\TH:i') : '') }}">
      </div>
      @if(isset($exam))
      <div class="form-group">
        <label class="form-label">Status</label>
        <select name="status" class="form-control">
          @foreach(['draft','approved','published','completed'] as $st)
            <option value="{{ $st }}" {{ $exam->status===$st?'selected':'' }}>{{ ucfirst($st) }}</option>
          @endforeach
        </select>
      </div>
      @endif
    </div>
    <button type="submit" class="btn btn-primary">{{ isset($exam)?'Update':'Create' }} Exam</button>
    <a href="{{ route('admin.exams.index') }}" class="btn btn-outline" style="margin-left:8px">Cancel</a>
  </form>
</div></div>
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

    const selectedSubjectId = "{{ old('subject_id', $exam->subject_id ?? '') }}";

    function updateSubjects() {
        const batchId = batchSelect.value;
        const subjects = batchSubjects[batchId] || [];
        
        subjectSelect.innerHTML = '<option value="">Select Subject</option>';
        
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
