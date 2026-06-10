@extends('layouts.admin')
@section('title', isset($subject)?'Edit Subject':'New Subject')
@section('page-title', isset($subject)?'Edit Subject':'New Subject')
@section('content')
<div class="page-header"><div class="page-title">{{ isset($subject)?'Edit Subject':'New Subject' }}</div><a href="{{ route('admin.subjects.index') }}" class="btn btn-outline">← Back</a></div>
<div class="card"><div class="card-body">
  <form method="POST" action="{{ isset($subject)?route('admin.subjects.update',$subject):route('admin.subjects.store') }}">
    @csrf @if(isset($subject)) @method('PUT') @endif
    <div class="form-grid">
      <div class="form-group"><label class="form-label">Course *</label>
        <select name="course_id" class="form-control" required>
          <option value="">Select</option>
          @foreach($courses as $c)<option value="{{ $c->id }}" {{ old('course_id',$subject->course_id??'')==$c->id?'selected':'' }}>{{ $c->name }}</option>@endforeach
        </select></div>
      <div class="form-group"><label class="form-label">Subject Name *</label><input type="text" name="name" class="form-control" value="{{ old('name',$subject->name??'') }}" required></div>
      <div class="form-group"><label class="form-label">Name (বাংলা)</label><input type="text" name="name_bn" class="form-control" value="{{ old('name_bn',$subject->name_bn??'') }}"></div>
      <div class="form-group"><label class="form-label">Subject Code</label><input type="text" name="code" class="form-control" value="{{ old('code',$subject->code??'') }}"></div>
      <div class="form-group"><label class="form-label">Credit Hours *</label><input type="number" name="credit_hours" class="form-control" value="{{ old('credit_hours',$subject->credit_hours??3) }}" required></div>
      <div class="form-group"><label class="form-label">Status *</label>
        <select name="status" class="form-control" required>
          <option value="active" {{ old('status',$subject->status??'active')==='active'?'selected':'' }}>Active</option>
          <option value="inactive" {{ old('status',$subject->status??'active')==='inactive'?'selected':'' }}>Inactive</option>
        </select></div>
    </div>
    <div class="form-group"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="3">{{ old('description',$subject->description??'') }}</textarea></div>
    <button type="submit" class="btn btn-primary">{{ isset($subject)?'Update':'Create' }} Subject</button>
  </form>
</div></div>
@endsection
