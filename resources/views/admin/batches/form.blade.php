@extends('layouts.admin')
@section('title', isset($batch)?'Edit Batch':'New Batch')
@section('page-title', isset($batch)?'Edit Batch':'New Batch')
@section('content')
<div class="page-header"><div class="page-title">{{ isset($batch)?'Edit Batch':'New Batch' }}</div><a href="{{ route('admin.batches.index') }}" class="btn btn-outline">← Back</a></div>
<div class="card"><div class="card-body">
  <form method="POST" action="{{ isset($batch)?route('admin.batches.update',$batch):route('admin.batches.store') }}">
    @csrf @if(isset($batch)) @method('PUT') @endif
    <div class="form-grid">
      <div class="form-group"><label class="form-label">Course *</label>
        <select name="course_id" class="form-control" required>
          <option value="">Select</option>
          @foreach($courses as $c)<option value="{{ $c->id }}" {{ old('course_id',$batch->course_id??'')==$c->id?'selected':'' }}>{{ $c->name }}</option>@endforeach
        </select></div>
      <div class="form-group"><label class="form-label">Semester *</label>
        <select name="semester_id" class="form-control" required>
          <option value="">Select</option>
          @foreach($semesters as $s)<option value="{{ $s->id }}" {{ old('semester_id',$batch->semester_id??'')==$s->id?'selected':'' }}>{{ $s->name }}</option>@endforeach
        </select></div>
      <div class="form-group"><label class="form-label">Batch Name *</label><input type="text" name="name" class="form-control" value="{{ old('name',$batch->name??'') }}" required></div>
      <div class="form-group"><label class="form-label">Batch Name (বাংলা)</label><input type="text" name="name_bn" class="form-control" value="{{ old('name_bn',$batch->name_bn??'') }}"></div>
      <div class="form-group"><label class="form-label">Capacity *</label><input type="number" name="capacity" class="form-control" value="{{ old('capacity',$batch->capacity??30) }}" required></div>
      <div class="form-group"><label class="form-label">Start Date</label><input type="date" name="start_date" class="form-control" value="{{ old('start_date',$batch->start_date??'') }}"></div>
      <div class="form-group"><label class="form-label">End Date</label><input type="date" name="end_date" class="form-control" value="{{ old('end_date',$batch->end_date??'') }}"></div>
      <div class="form-group"><label class="form-label">Status *</label>
        <select name="status" class="form-control" required>
          @foreach(['active'=>'Active','inactive'=>'Inactive','completed'=>'Completed'] as $v=>$l)<option value="{{ $v }}" {{ old('status',$batch->status??'active')===$v?'selected':'' }}>{{ $l }}</option>@endforeach
        </select></div>
    </div>
    <button type="submit" class="btn btn-primary">{{ isset($batch)?'Update':'Create' }} Batch</button>
  </form>
</div></div>
@endsection
