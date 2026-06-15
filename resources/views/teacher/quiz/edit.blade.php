@extends('layouts.teacher')
@section('title', 'Edit Quiz Room')
@section('page-title', 'Quiz Management')
@section('content')

<div class="page-header">
  <div>
    <div class="page-title">✏️ Edit Quiz Room</div>
    <div class="page-sub">কুইজ রুমের তথ্য বা স্ট্যাটাস পরিবর্তন করুন</div>
  </div>
  <a href="{{ route('teacher.quiz.index') }}" class="btn btn-outline">Back to List</a>
</div>

<div class="card">
  <div class="card-header">
    <div class="card-title">Modify Configuration: {{ $quiz->title }}</div>
  </div>
  <div class="card-body">
    <form method="POST" action="{{ route('teacher.quiz.update', $quiz) }}">
      @csrf
      @method('PUT') {{-- লারাভেলে আপডেট রিকোয়েস্টের জন্য এই লাইনটি আবশ্যক --}}
      
      <div class="form-group">
        <label class="form-label" for="title">Title *</label>
        <input type="text" name="title" id="title" class="form-control" required value="{{ old('title', $quiz->title) }}">
        @error('title') <span style="color:#dc2626;font-size:12px;margin-top:4px;display:block">{{ $message }}</span> @enderror
      </div>

      <div class="form-group">
        <label class="form-label" for="description">Description</label>
        <textarea name="description" id="description" class="form-control" rows="3" placeholder="কুইজের নির্দেশনা...">{{ old('description', $quiz->description) }}</textarea>
        @error('description') <span style="color:#dc2626;font-size:12px;margin-top:4px;display:block">{{ $message }}</span> @enderror
      </div>

      <div class="form-grid" style="grid-template-columns:1fr 1fr 1fr">
        <div class="form-group">
          <label class="form-label" for="batch_id">Target Batch</label>
          <select name="batch_id" id="batch_id" class="form-control">
            <option value="">সকল ব্যাচ (Public)</option>
            @foreach($batches as $b)
              <option value="{{ $b->id }}" {{ old('batch_id', $quiz->batch_id) == $b->id ? 'selected' : '' }}>
                {{ $b->course->name ?? '' }} - {{ $b->name }}
              </option>
            @endforeach
          </select>
          @error('batch_id') <span style="color:#dc2626;font-size:12px;margin-top:4px;display:block">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
          <label class="form-label" for="duration_minutes">Duration (Minutes) *</label>
          <input type="number" name="duration_minutes" id="duration_minutes" class="form-control" required min="1" value="{{ old('duration_minutes', $quiz->duration_minutes) }}">
          @error('duration_minutes') <span style="color:#dc2626;font-size:12px;margin-top:4px;display:block">{{ $message }}</span> @enderror
        </div>

        {{-- কুইজ স্ট্যাটাস পরিবর্তনের ড্রপডাউন --}}
        <div class="form-group">
          <label class="form-label" for="status">Quiz Status *</label>
          <select name="status" id="status" class="form-control" required>
            <option value="draft" {{ old('status', $quiz->status) == 'draft' ? 'selected' : '' }}>Draft (খসড়া)</option>
            <option value="active" {{ old('status', $quiz->status) == 'active' ? 'selected' : '' }}>Active (চলমান)</option>
            <option value="closed" {{ old('status', $quiz->status) == 'closed' ? 'selected' : '' }}>Closed (বন্ধ)</option>
          </select>
          @error('status') <span style="color:#dc2626;font-size:12px;margin-top:4px;display:block">{{ $message }}</span> @enderror
        </div>
      </div>

      <div class="form-grid">
        <div class="form-group">
          <label class="form-label" for="starts_at">Starts At</label>
          <input type="datetime-local" name="starts_at" id="starts_at" class="form-control" value="{{ old('starts_at', $quiz->starts_at ? $quiz->starts_at->format('Y-m-d\TH:i') : '') }}">
          @error('starts_at') <span style="color:#dc2626;font-size:12px;margin-top:4px;display:block">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
          <label class="form-label" for="ends_at">Ends At</label>
          <input type="datetime-local" name="ends_at" id="ends_at" class="form-control" value="{{ old('ends_at', $quiz->ends_at ? $quiz->ends_at->format('Y-m-d\TH:i') : '') }}">
          @error('ends_at') <span style="color:#dc2626;font-size:12px;margin-top:4px;display:block">{{ $message }}</span> @enderror
        </div>
      </div>

      <div style="margin-top:20px;display:flex;gap:10px">
        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="{{ route('teacher.quiz.index') }}" class="btn btn-outline">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection