@extends('layouts.admin')
@section('title','Record Payment')
@section('page-title','Record Payment')
@section('content')
<div class="page-header">
  <div><div class="page-title">Record Payment</div></div>
  <a href="{{ route('admin.payments.index') }}" class="btn btn-outline">← Back</a>
</div>
<div class="card"><div class="card-body">
  <form method="POST" action="{{ route('admin.payments.store') }}">
    @csrf
    <div class="form-grid">
      <div class="form-group">
        <label class="form-label">Student *</label>
        <select name="student_id" class="form-control" required>
          <option value="">Select Student</option>
          @foreach($students as $s)
            <option value="{{ $s->id }}" {{ old('student_id')==$s->id?'selected':'' }}>{{ $s->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Fee Structure</label>
        <select name="fee_structure_id" id="fee_structure_id" class="form-control">
          <option value="" data-amount="0">None (Custom Payment)</option>
          @foreach($feeStructures as $f)
            <option value="{{ $f->id }}" data-amount="{{ $f->amount }}" {{ old('fee_structure_id')==$f->id?'selected':'' }}>
              {{ $f->title }} (৳{{ number_format($f->amount, 2) }})
            </option>
          @endforeach
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Total Amount *</label>
        <input type="number" name="amount" id="amount" class="form-control" step="0.01" min="0" value="{{ old('amount', 0) }}" required>
      </div>
      <div class="form-group">
        <label class="form-label">Discount</label>
        <input type="number" name="discount" id="discount" class="form-control" step="0.01" min="0" value="{{ old('discount', 0) }}">
      </div>
      <div class="form-group">
        <label class="form-label">Paid Amount *</label>
        <input type="number" name="paid_amount" id="paid_amount" class="form-control" step="0.01" min="0" value="{{ old('paid_amount', 0) }}" required>
      </div>
      <div class="form-group">
        <label class="form-label">Method *</label>
        <select name="payment_method" class="form-control" required>
          <option value="manual">Manual (Cash)</option>
          <option value="online">Online</option>
          <option value="sslcommerz">SSLCommerz</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Due Date</label>
        <input type="date" name="due_date" class="form-control" value="{{ old('due_date') }}">
      </div>
      <div class="form-group" style="display: flex; flex-direction: column; justify-content: center;">
        <label class="form-label" style="color: var(--primary);">Calculated Due Amount</label>
        <div id="due_display" style="font-size: 20px; font-weight: 700; color: #dc2626;">৳ 0.00</div>
      </div>
    </div>
    <button type="submit" class="btn btn-primary">💾 Record Payment</button>
    <a href="{{ route('admin.payments.index') }}" class="btn btn-outline" style="margin-left:8px">Cancel</a>
  </form>
</div></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const feeSelect = document.getElementById('fee_structure_id');
  const amountInput = document.getElementById('amount');
  const discountInput = document.getElementById('discount');
  const paidInput = document.getElementById('paid_amount');
  const dueDisplay = document.getElementById('due_display');

  function calculateDue() {
    const amount = parseFloat(amountInput.value) || 0;
    const discount = parseFloat(discountInput.value) || 0;
    const paid = parseFloat(paidInput.value) || 0;
    
    // Net Amount = Amount - Discount
    // Due = Net Amount - Paid
    const net = amount - discount;
    const due = net - paid;
    
    dueDisplay.textContent = '৳ ' + (due > 0 ? due.toFixed(2) : '0.00');
    
    if (due > 0) {
      dueDisplay.style.color = '#dc2626'; // red
    } else {
      dueDisplay.style.color = '#16a34a'; // green
    }
  }

  // When fee structure changes, auto-populate the total amount
  feeSelect.addEventListener('change', function() {
    const selectedOption = feeSelect.options[feeSelect.selectedIndex];
    const amount = parseFloat(selectedOption.getAttribute('data-amount')) || 0;
    
    if (amount > 0) {
      amountInput.value = amount;
    }
    calculateDue();
  });

  // Attach input event listeners for live due calculation
  amountInput.addEventListener('input', calculateDue);
  discountInput.addEventListener('input', calculateDue);
  paidInput.addEventListener('input', calculateDue);

  // Initial calculation
  calculateDue();
});
</script>
@endsection

