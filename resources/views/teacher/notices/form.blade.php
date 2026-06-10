@extends('layouts.teacher')
@section('title','New Notice')
@section('content')
<div class="page-header">
  <div class="page-title">New Notice</div>
  <a href="{{ route('teacher.notices.index') }}" class="btn btn-outline">← Back</a>
</div>
<div class="card" style="max-width:680px">
  <div class="card-body">
    <form method="POST" action="{{ route('teacher.notices.store') }}">
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

      <div class="form-group">
        <label class="form-label">Body <span style="color:red">*</span></label>
        <textarea name="body" class="form-control" rows="5" required>{{ old('body') }}</textarea>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px">
        <div class="form-group">
          <label class="form-label">Type <span style="color:red">*</span></label>
          <select name="type" class="form-control" required>
            @foreach(['general','exam','holiday','urgent'] as $t)
              <option value="{{ $t }}" {{ old('type')===$t?'selected':'' }}>{{ ucfirst($t) }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Visible To <span style="color:red">*</span></label>
          <select name="visible_to" class="form-control" required>
            <option value="all" {{ old('visible_to')==='all'?'selected':'' }}>All</option>
            <option value="student" {{ old('visible_to')==='student'?'selected':'' }}>Students</option>
            <option value="teacher" {{ old('visible_to')==='teacher'?'selected':'' }}>Teachers</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Expires At</label>
          <input type="date" name="expires_at" class="form-control" value="{{ old('expires_at') }}">
        </div>
      </div>

      <div style="display:flex;gap:12px">
        <button type="submit" class="btn btn-primary">Publish Notice</button>
        <a href="{{ route('teacher.notices.index') }}" class="btn btn-outline">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
