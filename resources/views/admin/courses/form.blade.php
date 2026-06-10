@extends('layouts.admin')
@section('title', isset($course) ? 'Edit Course' : 'New Course')
@section('page-title', isset($course) ? 'Edit Course' : 'New Course')

@section('content')
<div class="page-header">
  <div>
    <div class="page-title">{{ isset($course) ? 'Edit Course' : 'New Course' }}</div>
    <div class="page-sub">Course তথ্য পূরণ করুন</div>
  </div>
  <a href="{{ route('admin.courses.index') }}" class="btn btn-outline">← Back</a>
</div>

<div class="card">
  <div class="card-body">
    <form method="POST" action="{{ isset($course) ? route('admin.courses.update',$course) : route('admin.courses.store') }}">
      @csrf
      @if(isset($course)) @method('PUT') @endif

      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Course Name (English) *</label>
          <input type="text" name="name" class="form-control" value="{{ old('name',$course->name??'') }}" required>
          @error('name')<div style="color:#dc2626;font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
          <label class="form-label">Course Name (বাংলা)</label>
          <input type="text" name="name_bn" class="form-control" value="{{ old('name_bn',$course->name_bn??'') }}">
        </div>
        <div class="form-group">
          <label class="form-label">Type *</label>
          <select name="type" class="form-control" required>
            <option value="">Select Type</option>
            <option value="short_term" {{ old('type',$course->type??'') === 'short_term' ? 'selected' : '' }}>Short Term</option>
            <option value="long_term"  {{ old('type',$course->type??'') === 'long_term'  ? 'selected' : '' }}>Long Term</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Duration (Years) *</label>
          <input type="number" name="duration_years" class="form-control" min="1" max="10"
                 value="{{ old('duration_years',$course->duration_years??1) }}" required>
        </div>
        <div class="form-group">
          <label class="form-label">Status *</label>
          <select name="status" class="form-control" required>
            <option value="active"   {{ old('status',$course->status??'active')   === 'active'   ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status',$course->status??'active')   === 'inactive' ? 'selected' : '' }}>Inactive</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description',$course->description??'') }}</textarea>
      </div>

      <div style="display:flex;gap:12px;margin-top:8px">
        <button type="submit" class="btn btn-primary">{{ isset($course) ? 'Update Course' : 'Create Course' }}</button>
        <a href="{{ route('admin.courses.index') }}" class="btn btn-outline">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
