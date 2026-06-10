@extends('layouts.admin')
@section('title','New Notice')
@section('page-title','New Notice')
@section('content')
<div class="page-header">
  <div><div class="page-title">New Notice</div></div>
  <a href="{{ route('admin.notices.index') }}" class="btn btn-outline">← Back</a>
</div>
<div class="card"><div class="card-body">
  <form method="POST" action="{{ route('admin.notices.store') }}">
    @csrf
    <div class="form-group">
      <label class="form-label">Title *</label>
      <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
    </div>
    <div class="form-group">
      <label class="form-label">Body *</label>
      <textarea name="body" class="form-control" rows="5" required>{{ old('body') }}</textarea>
    </div>
    <div class="form-grid">
      <div class="form-group">
        <label class="form-label">Scope *</label>
        <select name="scope" class="form-control" id="scope-sel" required>
          <option value="universal">Universal (সবার জন্য)</option>
          <option value="batch">Specific Batch</option>
          <option value="course">Specific Course</option>
        </select>
      </div>
      <div class="form-group" id="batch-field" style="display:none">
        <label class="form-label">Batch</label>
        <select name="batch_id" class="form-control">
          <option value="">Select Batch</option>
          @foreach($batches as $b)
            <option value="{{ $b->id }}">{{ $b->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group" id="course-field" style="display:none">
        <label class="form-label">Course</label>
        <select name="course_id" class="form-control">
          <option value="">Select Course</option>
          @foreach($courses as $c)
            <option value="{{ $c->id }}">{{ $c->name }}</option>
          @endforeach
        </select>
      </div>
    </div>
    <button type="submit" class="btn btn-primary">Publish Notice</button>
  </form>
</div></div>
@push('scripts')
<script>
document.getElementById('scope-sel').addEventListener('change', function(){
  document.getElementById('batch-field').style.display = this.value==='batch' ? 'block':'none';
  document.getElementById('course-field').style.display = this.value==='course' ? 'block':'none';
});
</script>
@endpush
@endsection
