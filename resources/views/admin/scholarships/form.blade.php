@extends('layouts.admin')
@section('title','New Scholarship')
@section('page-title','Scholarship')
@section('content')
<div class="page-header">
  <div class="page-title">New Scholarship</div>
  <a href="{{ route('admin.scholarships.index') }}" class="btn btn-outline">← Back</a>
</div>
<div class="card" style="max-width:600px">
  <div class="card-body">
    <form method="POST" action="{{ route('admin.scholarships.store') }}">
      @csrf

      @if($errors->any())
        <div class="alert alert-danger" style="margin-bottom:16px">
          @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
        </div>
      @endif

      <div class="form-group">
        <label class="form-label">Student <span style="color:red">*</span></label>
        <select name="student_id" class="form-control" required>
          <option value="">-- Select Student --</option>
          @foreach($students as $st)
            <option value="{{ $st->id }}" {{ old('student_id') == $st->id ? 'selected' : '' }}>
              {{ $st->name }} ({{ $st->studentProfile->student_id ?? $st->email }})
            </option>
          @endforeach
        </select>
      </div>

      <div class="form-group">
        <label class="form-label">Title <span style="color:red">*</span></label>
        <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="e.g. Merit Scholarship" required>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div class="form-group">
          <label class="form-label">Discount Amount (৳)</label>
          <input type="number" name="discount_amount" class="form-control" step="0.01" min="0" value="{{ old('discount_amount') }}">
        </div>
        <div class="form-group">
          <label class="form-label">Discount Percent (%)</label>
          <input type="number" name="discount_percent" class="form-control" step="0.01" min="0" max="100" value="{{ old('discount_percent') }}">
        </div>
      </div>
      <p style="font-size:12px;color:#9ca3af;margin-top:-8px">Amount অথবা Percent — যেকোনো একটি দিন।</p>

      <div class="form-group">
        <label class="form-label">Reason</label>
        <textarea name="reason" class="form-control" rows="3" placeholder="বৃত্তির কারণ লিখুন...">{{ old('reason') }}</textarea>
      </div>

      <div style="display:flex;gap:12px;margin-top:8px">
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('admin.scholarships.index') }}" class="btn btn-outline">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
