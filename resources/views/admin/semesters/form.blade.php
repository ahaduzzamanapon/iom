@extends('layouts.admin')
@section('title', isset($semester)?'Edit Semester':'New Semester')
@section('page-title', isset($semester)?'Edit Semester':'New Semester')
@section('content')
<div class="page-header"><div class="page-title">{{ isset($semester)?'Edit Semester':'New Semester' }}</div><a href="{{ route('admin.semesters.index') }}" class="btn btn-outline">← Back</a></div>
<div class="card"><div class="card-body">
  <form method="POST" action="{{ isset($semester)?route('admin.semesters.update',$semester):route('admin.semesters.store') }}">
    @csrf @if(isset($semester)) @method('PUT') @endif
    <div class="form-grid">
      <div class="form-group"><label class="form-label">Course *</label>
        <select name="course_id" class="form-control" required>
          <option value="">Select</option>
          @foreach($courses as $c)<option value="{{ $c->id }}" {{ old('course_id',$semester->course_id??'')==$c->id?'selected':'' }}>{{ $c->name }}</option>@endforeach
        </select></div>
      <div class="form-group"><label class="form-label">Semester Name *</label><input type="text" name="name" class="form-control" value="{{ old('name',$semester->name??'') }}" required></div>
      <div class="form-group"><label class="form-label">Name (বাংলা)</label><input type="text" name="name_bn" class="form-control" value="{{ old('name_bn',$semester->name_bn??'') }}"></div>
      <div class="form-group"><label class="form-label">Order *</label><input type="number" name="order" class="form-control" value="{{ old('order',$semester->order??1) }}" required></div>
      <div class="form-group"><label class="form-label">Start Date</label><input type="date" name="start_date" class="form-control" value="{{ old('start_date',$semester->start_date??'') }}"></div>
      <div class="form-group"><label class="form-label">End Date</label><input type="date" name="end_date" class="form-control" value="{{ old('end_date',$semester->end_date??'') }}"></div>
      <div class="form-group"><label class="form-label">Status *</label>
        <select name="status" class="form-control" required>
          @foreach(['upcoming','active','completed'] as $st)<option value="{{ $st }}" {{ old('status',$semester->status??'upcoming')===$st?'selected':'' }}>{{ ucfirst($st) }}</option>@endforeach
        </select></div>
    </div>
    <button type="submit" class="btn btn-primary">{{ isset($semester)?'Update':'Create' }} Semester</button>
  </form>
</div></div>
@endsection
