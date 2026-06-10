@extends('layouts.admin')
@section('title', isset($feeStructure) ? 'Edit Fee Structure' : 'New Fee Structure')
@section('page-title','Fee Structure')
@section('content')
<div class="page-header">
  <div class="page-title">{{ isset($feeStructure) ? 'Edit Fee Structure' : 'New Fee Structure' }}</div>
  <a href="{{ route('admin.fee-structures.index') }}" class="btn btn-outline">← Back</a>
</div>
<div class="card" style="max-width:700px">
  <div class="card-body">
    <form method="POST" action="{{ isset($feeStructure) ? route('admin.fee-structures.update',$feeStructure) : route('admin.fee-structures.store') }}">
      @csrf
      @if(isset($feeStructure)) @method('PUT') @endif

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
            <option value="{{ $c->id }}" {{ old('course_id', $feeStructure->course_id ?? '') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="form-group">
        <label class="form-label">Title <span style="color:red">*</span></label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $feeStructure->title ?? '') }}" required>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div class="form-group">
          <label class="form-label">Type <span style="color:red">*</span></label>
          <select name="type" class="form-control" required>
            @foreach(['admission','monthly','package','other'] as $t)
              <option value="{{ $t }}" {{ old('type', $feeStructure->type ?? '') == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Amount (৳) <span style="color:red">*</span></label>
          <input type="number" name="amount" class="form-control" step="0.01" min="0" value="{{ old('amount', $feeStructure->amount ?? '') }}" required>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $feeStructure->description ?? '') }}</textarea>
      </div>

      <div class="form-group">
        <label class="form-label">Status <span style="color:red">*</span></label>
        <select name="status" class="form-control" required>
          <option value="active" {{ old('status', $feeStructure->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
          <option value="inactive" {{ old('status', $feeStructure->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
      </div>

      <div style="display:flex;gap:12px;margin-top:8px">
        <button type="submit" class="btn btn-primary">{{ isset($feeStructure) ? 'Update' : 'Save' }}</button>
        <a href="{{ route('admin.fee-structures.index') }}" class="btn btn-outline">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
