@extends('layouts.admin')
@section('title','Payments')
@section('page-title','Payment Management')
@section('content')
<div class="page-header">
  <div><div class="page-title">Payments</div><div class="page-sub">সকল পেমেন্ট পরিচালনা করুন</div></div>
  <div style="display:flex;gap:8px">
    <a href="{{ route('admin.payments.create') }}" class="btn btn-primary">+ Record Payment</a>
    <a href="{{ route('admin.finance.export','pdf') }}" class="btn-export btn-pdf">📄 PDF</a>
    <a href="{{ route('admin.finance.export','excel') }}" class="btn-export btn-excel">📊 Excel</a>
  </div>
</div>
<div class="card" style="margin-bottom:16px">
  <div class="card-body">
    <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end">
      <div class="form-group" style="margin:0;flex:1;min-width:200px">
        <label class="form-label">Search Student</label>
        <input type="text" name="search" class="form-control" placeholder="Student name..." value="{{ request('search') }}">
      </div>
      <div class="form-group" style="margin:0;min-width:140px">
        <label class="form-label">Status</label>
        <select name="status" class="form-control">
          <option value="">All</option>
          <option value="paid" {{ request('status')==='paid'?'selected':'' }}>Paid</option>
          <option value="pending" {{ request('status')==='pending'?'selected':'' }}>Pending</option>
          <option value="partial" {{ request('status')==='partial'?'selected':'' }}>Partial</option>
        </select>
      </div>
      <button class="btn btn-primary">Filter</button>
      <a href="{{ route('admin.payments.index') }}" class="btn btn-outline">Reset</a>
    </form>
  </div>
</div>
<div class="card">
  <x-data-table
    :headers="['#','Invoice','Student','Amount','Paid','Due','Method','Status','Date']"
    :export-pdf-url="route('admin.finance.export','pdf')"
    :export-url="route('admin.finance.export','excel')"
  >
    @forelse($payments as $p)
    <tr>
      <td><input type="checkbox" name="ids[]" value="{{ $p->id }}"></td>
      <td style="font-family:monospace;font-size:12px">{{ $p->invoice_number }}</td>
      <td>{{ $p->student->name ?? '—' }}</td>
      <td>৳{{ number_format($p->amount) }}</td>
      <td style="color:#16a34a;font-weight:600">৳{{ number_format($p->paid_amount) }}</td>
      <td style="color:{{ $p->due_amount > 0 ? '#dc2626' : '#16a34a' }};font-weight:600">৳{{ number_format($p->due_amount) }}</td>
      <td><span class="badge badge-gray">{{ ucfirst($p->payment_method) }}</span></td>
      <td><span class="badge {{ $p->status==='paid'?'badge-green':($p->status==='failed'?'badge-red':'badge-yellow') }}">{{ ucfirst($p->status) }}</span></td>
      <td style="font-size:12px">{{ $p->created_at->format('d M Y') }}</td>
    </tr>
    @empty
    <tr><td colspan="9" style="text-align:center;padding:30px;color:#9ca3af">কোনো payment নেই।</td></tr>
    @endforelse
  </x-data-table>
  <div class="pagination">{{ $payments->links() }}</div>
</div>
@endsection
