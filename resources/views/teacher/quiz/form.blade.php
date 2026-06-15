@extends('layouts.teacher')
@section('title', 'New Quiz Room')
@section('page-title', 'Quiz Management')
@section('content')

<div class="page-header">
  <div>
    <div class="page-title">🎯 New Quiz Room</div>
    <div class="page-sub">নতুন কুইজ রুম তৈরি করুন</div>
  </div>
  <a href="{{ route('teacher.quiz.index') }}" class="btn btn-outline">Back to List</a>
</div>

<div class="card">
  <div class="card-header">
    <div class="card-title">Quiz Configuration</div>
  </div>
  <div class="card-body">
    <form method="POST" action="{{ route('teacher.quiz.store') }}">
      @csrf
      
      <div class="form-group">
        <label class="form-label" for="title">Title *</label>
        <input type="text" name="title" id="title" class="form-control" required value="{{ old('title') }}" placeholder="যেমন: Fiqh Taharah Quiz 1">
        @error('title') <span style="color:#dc2626;font-size:12px;margin-top:4px;display:block">{{ $message }}</span> @enderror
      </div>

      <div class="form-group">
        <label class="form-label" for="description">Description</label>
        <textarea name="description" id="description" class="form-control" rows="3" placeholder="কুইজের নির্দেশনা...">{{ old('description') }}</textarea>
        @error('description') <span style="color:#dc2626;font-size:12px;margin-top:4px;display:block">{{ $message }}</span> @enderror
      </div>

      <div class="form-grid">
        <div class="form-group">
          <label class="form-label" for="batch_id">Target Batch</label>
          <select name="batch_id" id="batch_id" class="form-control">
            <option value="">সকল ব্যাচ (Public)</option>
            @foreach($batches as $b)
              <option value="{{ $b->id }}" {{ old('batch_id') == $b->id ? 'selected' : '' }}>
                {{ $b->course->name ?? '' }} - {{ $b->name }}
              </option>
            @endforeach
          </select>
          @error('batch_id') <span style="color:#dc2626;font-size:12px;margin-top:4px;display:block">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
          <label class="form-label" for="duration_minutes">Duration (Minutes) *</label>
          <input type="number" name="duration_minutes" id="duration_minutes" class="form-control" required min="1" value="{{ old('duration_minutes', 30) }}">
          @error('duration_minutes') <span style="color:#dc2626;font-size:12px;margin-top:4px;display:block">{{ $message }}</span> @enderror
        </div>
      </div>

      <div class="form-grid">
        <div class="form-group">
          <label class="form-label" for="starts_at">Starts At</label>
          <input type="datetime-local" name="starts_at" id="starts_at" class="form-control" value="{{ old('starts_at') }}">
          @error('starts_at') <span style="color:#dc2626;font-size:12px;margin-top:4px;display:block">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
          <label class="form-label" for="ends_at">Ends At</label>
          <input type="datetime-local" name="ends_at" id="ends_at" class="form-control" value="{{ old('ends_at') }}">
          @error('ends_at') <span style="color:#dc2626;font-size:12px;margin-top:4px;display:block">{{ $message }}</span> @enderror
        </div>
      </div>

      <div style="margin-top:20px;display:flex;gap:10px">
        <button type="submit" class="btn btn-primary">Create Quiz Room</button>
        <a href="{{ route('teacher.quiz.index') }}" class="btn btn-outline">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection