@extends('layouts.admin')
@section('title','New Admission')
@section('page-title','Admissions')
@section('content')
<div class="page-header">
  <div class="page-title">New Admission (Admin)</div>
  <a href="{{ route('admin.admissions.index') }}" class="btn btn-outline">← Back</a>
</div>
<div class="card" style="max-width:700px">
  <div class="card-body">
    <form method="POST" action="{{ route('admin.admissions.store') }}" enctype="multipart/form-data">
      @csrf

      @if($errors->any())
        <div class="alert alert-danger" style="margin-bottom:16px">
          @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
        </div>
      @endif

      <div class="form-group">
        <label class="form-label">Course <span style="color:red">*</span></label>
        <select name="course_id" class="form-control" required>
          <option value="">-- Select Course --</option>
          @foreach($courses as $c)
            <option value="{{ $c->id }}" {{ old('course_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
          @endforeach
        </select>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div class="form-group">
          <label class="form-label">Applicant Name <span style="color:red">*</span></label>
          <input type="text" name="applicant_name" class="form-control" value="{{ old('applicant_name') }}" required>
        </div>
        <div class="form-group">
          <label class="form-label">Email <span style="color:red">*</span></label>
          <input type="email" name="applicant_email" class="form-control" value="{{ old('applicant_email') }}" required>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div class="form-group">
          <label class="form-label">Phone <span style="color:red">*</span></label>
          <input type="text" name="applicant_phone" class="form-control" value="{{ old('applicant_phone') }}" required>
        </div>
        <div class="form-group">
          <label class="form-label">Date of Birth</label>
          <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}">
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div class="form-group">
          <label class="form-label">Gender</label>
          <select name="gender" class="form-control">
            <option value="">-- Select --</option>
            <option value="male" {{ old('gender')==='male'?'selected':'' }}>Male</option>
            <option value="female" {{ old('gender')==='female'?'selected':'' }}>Female</option>
            <option value="other" {{ old('gender')==='other'?'selected':'' }}>Other</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Photo</label>
          <input type="file" name="photo" class="form-control" accept="image/*">
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div class="form-group">
          <label class="form-label">Guardian Name</label>
          <input type="text" name="guardian_name" class="form-control" value="{{ old('guardian_name') }}">
        </div>
        <div class="form-group">
          <label class="form-label">Guardian Phone</label>
          <input type="text" name="guardian_phone" class="form-control" value="{{ old('guardian_phone') }}">
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Address</label>
        <textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea>
      </div>

      <div style="display:flex;gap:12px;margin-top:8px">
        <button type="submit" class="btn btn-primary">Save Admission</button>
        <a href="{{ route('admin.admissions.index') }}" class="btn btn-outline">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
