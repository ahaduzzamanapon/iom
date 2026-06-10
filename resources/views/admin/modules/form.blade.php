@extends('layouts.admin')
@section('title', isset($module)?'Edit Module':'New Module')
@section('page-title', isset($module)?'Edit Module':'New Module')
@section('content')
<div class="page-header"><div class="page-title">{{ isset($module)?'Edit Module':'New Module' }}</div><a href="{{ route('admin.modules.index') }}" class="btn btn-outline">← Back</a></div>
<div class="card"><div class="card-body">
  <form method="POST" action="{{ isset($module)?route('admin.modules.update',$module):route('admin.modules.store') }}">
    @csrf @if(isset($module)) @method('PUT') @endif
    <div class="form-grid">
      <div class="form-group"><label class="form-label">Subject *</label>
        <select name="subject_id" class="form-control" required>
          <option value="">Select</option>
          @foreach($subjects as $s)<option value="{{ $s->id }}" {{ old('subject_id',$module->subject_id??'')==$s->id?'selected':'' }}>{{ $s->name }} / {{ $s->course->name??'' }}</option>@endforeach
        </select></div>
      <div class="form-group"><label class="form-label">Module Name *</label><input type="text" name="name" class="form-control" value="{{ old('name',$module->name??'') }}" required></div>
      <div class="form-group"><label class="form-label">Name (বাংলা)</label><input type="text" name="name_bn" class="form-control" value="{{ old('name_bn',$module->name_bn??'') }}"></div>
      <div class="form-group"><label class="form-label">Order *</label><input type="number" name="order" class="form-control" value="{{ old('order',$module->order??1) }}" required></div>
    </div>
    <div class="form-group"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="3">{{ old('description',$module->description??'') }}</textarea></div>
    <button type="submit" class="btn btn-primary">{{ isset($module)?'Update':'Create' }} Module</button>
  </form>
</div></div>
@endsection
