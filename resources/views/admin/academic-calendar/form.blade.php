@extends('layouts.admin')
@section('title', isset($event) ? 'Edit Event' : 'New Event')
@section('page-title', 'Academic Calendar')
@section('content')
<div class="page-header">
  <div class="page-title">{{ isset($event) ? '✏️ Event Edit' : '+ নতুন Event' }}</div>
  <a href="{{ route('admin.academic-calendars.index') }}" class="btn btn-outline">← Back</a>
</div>

<div class="card" style="max-width:700px">
  <div class="card-body">
    <form method="POST" action="{{ isset($event) ? route('admin.academic-calendars.update', $event) : route('admin.academic-calendars.store') }}">
      @csrf
      @if(isset($event)) @method('PUT') @endif

      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Title *</label>
          <input type="text" name="title" class="form-control" value="{{ old('title', $event->title ?? '') }}" required placeholder="e.g. ঈদুল ফিতরের ছুটি">
          @error('title')<div style="color:#dc2626;font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
          <label class="form-label">Type *</label>
          <select name="type" class="form-control" required>
            @foreach(['holiday'=>'🏖️ Holiday','exam'=>'📝 Exam','event'=>'🎉 Event','class_suspension'=>'⏸️ Class Suspension','other'=>'📌 Other'] as $val=>$lbl)
              <option value="{{ $val }}" {{ old('type', $event->type ?? '') == $val ? 'selected' : '' }}>{{ $lbl }}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Start Date *</label>
          <input type="date" name="start_date" class="form-control" value="{{ old('start_date', isset($event) ? $event->start_date->format('Y-m-d') : '') }}" required>
        </div>

        <div class="form-group">
          <label class="form-label">End Date <small style="color:#718096">(optional, for multi-day)</small></label>
          <input type="date" name="end_date" class="form-control" value="{{ old('end_date', isset($event) && $event->end_date ? $event->end_date->format('Y-m-d') : '') }}">
        </div>

        <div class="form-group">
          <label class="form-label">Course <small style="color:#718096">(optional — Universal হলে ফাঁকা রাখুন)</small></label>
          <select name="course_id" class="form-control">
            <option value="">-- Universal (সব Course) --</option>
            @foreach($courses as $c)
              <option value="{{ $c->id }}" {{ old('course_id', $event->course_id ?? '') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Semester <small style="color:#718096">(optional)</small></label>
          <select name="semester_id" class="form-control">
            <option value="">-- All Semesters --</option>
            @foreach($semesters as $s)
              <option value="{{ $s->id }}" {{ old('semester_id', $event->semester_id ?? '') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3" placeholder="বিস্তারিত...">{{ old('description', $event->description ?? '') }}</textarea>
      </div>

      <div class="form-group" style="display:flex;align-items:center;gap:10px">
        <input type="checkbox" name="is_published" value="1" id="pub"
          {{ old('is_published', isset($event) ? $event->is_published : true) ? 'checked' : '' }}>
        <label for="pub" class="form-label" style="margin:0">Published (সবাই দেখতে পাবে)</label>
      </div>

      <div style="display:flex;gap:10px">
        <button type="submit" class="btn btn-primary">
          {{ isset($event) ? '💾 Update' : '✅ Save Event' }}
        </button>
        <a href="{{ route('admin.academic-calendars.index') }}" class="btn btn-outline">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
