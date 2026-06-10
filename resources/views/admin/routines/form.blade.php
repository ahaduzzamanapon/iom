@extends('layouts.admin')
@section('title','Add Routine')
@section('page-title','Class Routine')
@section('content')
<div class="page-header">
  <div class="page-title">Add Routine Slot</div>
  <a href="{{ route('admin.routines.index') }}" class="btn btn-outline">← Back</a>
</div>
<div class="card" style="max-width:680px">
  <div class="card-body">
    <form method="POST" action="{{ route('admin.routines.store') }}">
      @csrf

      @if($errors->any())
        <div class="alert alert-danger" style="margin-bottom:16px">
          @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
        </div>
      @endif

      <div class="form-group">
        <label class="form-label">Batch <span style="color:red">*</span></label>
        <select name="batch_id" class="form-control" required>
          <option value="">-- Select Batch --</option>
          @foreach($batches as $b)
            <option value="{{ $b->id }}" {{ old('batch_id') == $b->id ? 'selected' : '' }}>
              {{ $b->name }} ({{ $b->course->name ?? '' }})
            </option>
          @endforeach
        </select>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div class="form-group">
          <label class="form-label">Subject <span style="color:red">*</span></label>
          <select name="subject_id" class="form-control" required>
            <option value="">-- Select Subject --</option>
            @foreach($subjects as $s)
              <option value="{{ $s->id }}" {{ old('subject_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Teacher <span style="color:red">*</span></label>
          <select name="teacher_id" class="form-control" required>
            <option value="">-- Select Teacher --</option>
            @foreach($teachers as $t)
              <option value="{{ $t->id }}" {{ old('teacher_id') == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Day <span style="color:red">*</span></label>
        <select name="day" class="form-control" required>
          <option value="">-- Select Day --</option>
          @foreach(['saturday','sunday','monday','tuesday','wednesday','thursday','friday'] as $d)
            <option value="{{ $d }}" {{ old('day') === $d ? 'selected' : '' }}>{{ ucfirst($d) }}</option>
          @endforeach
        </select>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div class="form-group">
          <label class="form-label">Start Time <span style="color:red">*</span></label>
          <input type="time" name="start_time" class="form-control" value="{{ old('start_time') }}" required>
        </div>
        <div class="form-group">
          <label class="form-label">End Time <span style="color:red">*</span></label>
          <input type="time" name="end_time" class="form-control" value="{{ old('end_time') }}" required>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div class="form-group">
          <label class="form-label">Room / Platform</label>
          <input type="text" name="room" class="form-control" value="{{ old('room') }}" placeholder="e.g. Zoom, Room-101">
        </div>
        <div class="form-group">
          <label class="form-label">Type <span style="color:red">*</span></label>
          <select name="type" class="form-control" required>
            <option value="class" {{ old('type') === 'class' ? 'selected' : '' }}>Class</option>
            <option value="exam" {{ old('type') === 'exam' ? 'selected' : '' }}>Exam</option>
          </select>
        </div>
      </div>

      <div style="display:flex;gap:12px;margin-top:8px">
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('admin.routines.index') }}" class="btn btn-outline">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
