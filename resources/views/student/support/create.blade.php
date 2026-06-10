@extends('layouts.student')
@section('title','New Ticket')
@section('page-title','New Support Ticket')
@section('content')
<div class="page-header"><div class="page-title">New Ticket</div><a href="{{ route('student.support.index') }}" class="btn btn-outline">← Back</a></div>
<div class="card"><div class="card-body">
  <form method="POST" action="{{ route('student.support.store') }}">
    @csrf
    <div class="form-group"><label class="form-label">Subject *</label><input type="text" name="title" class="form-control" required></div>
    <div class="form-group"><label class="form-label">Category</label>
      <select name="category" class="form-control">
        <option value="">Select</option>
        <option value="payment">Payment</option><option value="exam">Exam</option>
        <option value="class">Class</option><option value="other">Other</option>
      </select></div>
    <div class="form-group"><label class="form-label">Description *</label><textarea name="description" class="form-control" rows="5" required></textarea></div>
    <button type="submit" class="btn btn-primary">Submit Ticket</button>
  </form>
</div></div>
@endsection
