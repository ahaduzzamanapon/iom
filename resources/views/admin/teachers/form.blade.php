@extends('layouts.admin')
@section('title', isset($teacher) ? 'Edit Teacher' : 'Add Teacher')
@section('page-title', isset($teacher) ? 'Edit Teacher' : 'Add Teacher')
@section('content')
<div class="page-header">
  <div><div class="page-title">{{ isset($teacher) ? 'Edit Teacher' : 'Add Teacher' }}</div></div>
  <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline">← Back</a>
</div>
<div class="card"><div class="card-body">
  <form method="POST" action="{{ isset($teacher) ? route('admin.teachers.update',$teacher) : route('admin.teachers.store') }}">
    @csrf @if(isset($teacher)) @method('PUT') @endif
    <div class="form-grid">
      <div class="form-group">
        <label class="form-label">Full Name *</label>
        <input type="text" name="name" class="form-control" value="{{ old('name',$teacher->user->name??'') }}" required>
      </div>
      @if(!isset($teacher))
      <div class="form-group">
        <label class="form-label">Email *</label>
        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
      </div>
      @endif
      <div class="form-group">
        <label class="form-label">Phone</label>
        <input type="text" name="phone" class="form-control" value="{{ old('phone',$teacher->phone??'') }}">
      </div>
      <div class="form-group">
        <label class="form-label">Qualification</label>
        <input type="text" name="qualification" class="form-control" value="{{ old('qualification',$teacher->qualification??'') }}">
      </div>
      <div class="form-group">
        <label class="form-label">Specialization</label>
        <input type="text" name="specialization" class="form-control" value="{{ old('specialization',$teacher->specialization??'') }}">
      </div>
      @if(isset($teacher))
      <div class="form-group">
        <label class="form-label">Status</label>
        <select name="status" class="form-control">
          <option value="active" {{ $teacher->status==='active'?'selected':'' }}>Active</option>
          <option value="inactive" {{ $teacher->status==='inactive'?'selected':'' }}>Inactive</option>
        </select>
      </div>
      @endif
    </div>
    <button type="submit" class="btn btn-primary">{{ isset($teacher)?'Update':'Add' }} Teacher</button>
    <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline" style="margin-left:8px">Cancel</a>
  </form>
</div></div>
@endsection
