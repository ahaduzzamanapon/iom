@extends('layouts.teacher')
@section('title','New Class')
@section('content')
<div class="page-header">
  <div class="page-title">New Class</div>
  <a href="{{ route('teacher.classes.index') }}" class="btn btn-outline">← Back</a>
</div>
<div class="card" style="max-width:680px">
  <div class="card-body">
    <form method="POST" action="{{ route('teacher.classes.store') }}">
      @csrf
      @if($errors->any())
        <div class="alert alert-danger" style="margin-bottom:16px">
          @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
        </div>
      @endif

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div class="form-group">
          <label class="form-label">Batch <span style="color:red">*</span></label>
          <select name="batch_id" class="form-control" required>
            <option value="">-- Select Batch --</option>
            @foreach($batches as $b)
              <option value="{{ $b->id }}" {{ old('batch_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Module <span style="color:red">*</span></label>
          <select name="module_id" class="form-control" required>
            <option value="">-- Select Module --</option>
            @foreach($modules as $m)
              <option value="{{ $m->id }}" {{ old('module_id') == $m->id ? 'selected' : '' }}>
                {{ $m->title }} ({{ $m->subject->name ?? '' }})
              </option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Title <span style="color:red">*</span></label>
        <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
      </div>

      <div class="form-group">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div class="form-group">
          <label class="form-label">Type <span style="color:red">*</span></label>
          <select name="type" class="form-control" required>
            <option value="recorded" {{ old('type')==='recorded'?'selected':'' }}>Recorded</option>
            <option value="live" {{ old('type')==='live'?'selected':'' }}>Live</option>
            <option value="text" {{ old('type')==='text'?'selected':'' }}>Text</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Duration (minutes)</label>
          <input type="number" name="duration_mins" class="form-control" min="1" value="{{ old('duration_mins') }}">
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Video URL</label>
        <input type="url" name="video_url" class="form-control" value="{{ old('video_url') }}" placeholder="https://youtube.com/...">
      </div>

      <div class="form-group">
        <label class="form-label">Meeting Link (Live Class)</label>
        <input type="url" name="meeting_link" class="form-control" value="{{ old('meeting_link') }}" placeholder="https://zoom.us/...">
      </div>

      <div class="form-group">
        <label class="form-label">Scheduled At</label>
        <input type="datetime-local" name="scheduled_at" class="form-control" value="{{ old('scheduled_at') }}">
      </div>

      <div style="display:flex;gap:12px">
        <button type="submit" class="btn btn-primary">Save Class</button>
        <a href="{{ route('teacher.classes.index') }}" class="btn btn-outline">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
