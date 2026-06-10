@extends('layouts.admin')
@section('title', 'Direct Readmission')
@section('page-title', 'Direct Readmission')

@section('content')
<div class="page-header">
  <div>
    <div class="page-title">🔄 Direct Readmission</div>
  </div>
  <a href="{{ route('admin.readmissions.index') }}" class="btn btn-outline">← Back</a>
</div>

<div class="card">
  <div class="card-body">
    <form action="{{ route('admin.readmissions.store') }}" method="POST">
      @csrf
      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Student *</label>
          <select name="user_id" class="form-control" required>
            <option value="">— Student বেছে নিন —</option>
            @foreach($students as $s)
              <option value="{{ $s->user_id }}">{{ $s->student_id }} — {{ $s->user->name }}</option>
            @endforeach
          </select>
          <small style="display:block;margin-top:4px;color:#6b7280;font-size:12px">Inactive students দেখানো হচ্ছে</small>
        </div>

        <div class="form-group">
          <label class="form-label">Course *</label>
          <select name="course_id" class="form-control" required>
            <option value="">— Course বেছে নিন —</option>
            @foreach($courses as $c)
              <option value="{{ $c->id }}">{{ $c->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Target Batch *</label>
          <select name="batch_id" class="form-control" required>
            <option value="">— Batch বেছে নিন —</option>
            @foreach($batches as $b)
              <option value="{{ $b->id }}">{{ $b->name }} ({{ $b->course->name ?? '' }})</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="form-group" style="margin-top:16px">
        <label class="form-label">Reason *</label>
        <textarea name="reason" class="form-control" rows="4" required placeholder="Readmission এর কারণ লিখুন..."></textarea>
      </div>

      <div style="margin-top:24px">
        <button type="submit" class="btn btn-primary">Readmission সম্পন্ন করুন</button>
        <a href="{{ route('admin.readmissions.index') }}" class="btn btn-outline" style="margin-left:8px">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
