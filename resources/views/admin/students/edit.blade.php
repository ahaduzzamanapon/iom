@extends('layouts.admin')
@section('title','Edit Student')
@section('page-title','Students')
@section('content')
<div class="page-header">
  <div class="page-title">Edit Student: {{ $student->user->name }}</div>
  <a href="{{ route('admin.students.show',$student) }}" class="btn btn-outline">← Back</a>
</div>
<div class="card" style="max-width:680px">
  <div class="card-body">
    <form method="POST" action="{{ route('admin.students.update',$student) }}">
      @csrf @method('PUT')

      @if($errors->any())
        <div class="alert alert-danger" style="margin-bottom:16px">
          @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
        </div>
      @endif

      <div class="form-group">
        <label class="form-label">Full Name <span style="color:red">*</span></label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $student->user->name) }}" required>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div class="form-group">
          <label class="form-label">Phone</label>
          <input type="text" name="phone" class="form-control" value="{{ old('phone', $student->phone) }}">
        </div>
        <div class="form-group">
          <label class="form-label">Status <span style="color:red">*</span></label>
          <select name="status" class="form-control" required>
            @foreach(['active','inactive','transferred','suspended'] as $s)
              <option value="{{ $s }}" {{ old('status',$student->status) === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div class="form-group">
          <label class="form-label">Guardian Name</label>
          <input type="text" name="guardian_name" class="form-control" value="{{ old('guardian_name',$student->guardian_name) }}">
        </div>
        <div class="form-group">
          <label class="form-label">Guardian Phone</label>
          <input type="text" name="guardian_phone" class="form-control" value="{{ old('guardian_phone',$student->guardian_phone) }}">
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Address</label>
        <textarea name="address" class="form-control" rows="2">{{ old('address',$student->address) }}</textarea>
      </div>

      <div style="display:flex;gap:12px;margin-top:8px">
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('admin.students.show',$student) }}" class="btn btn-outline">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
