@extends('layouts.student')
@section('title','Payments')
@section('page-title','My Payments')
@section('content')
<div class="page-header">
  <div class="page-title">Payments</div>
  @if($totalDue > 0)
    <span style="background:#fff1f2;color:#dc2626;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600">Due: ৳{{ number_format($totalDue) }}</span>
  @endif
</div>
<div class="card">
  <table class="dt-table">
    <thead><tr><th>Invoice</th><th>Amount</th><th>Paid</th><th>Method</th><th>Status</th><th>Date</th><th>Action</th></tr></thead>
    <tbody>
      @forelse($payments as $p)
      <tr>
        <td style="font-family:monospace;font-size:12px">{{ $p->invoice_number }}</td>
        <td>৳{{ number_format($p->amount) }}</td>
        <td style="color:#16a34a;font-weight:600">৳{{ number_format($p->paid_amount) }}</td>
        <td><span class="badge badge-gray">{{ ucfirst($p->payment_method) }}</span></td>
        <td><span class="badge {{ $p->status==='paid'?'badge-green':($p->status==='failed'?'badge-red':'badge-yellow') }}">{{ ucfirst($p->status) }}</span></td>
        <td style="font-size:12px">{{ $p->created_at->format('d M Y') }}</td>
        <td>
          @if($p->status !== 'paid')
            <form method="POST" action="/sslcommerz/pay" style="display:inline">
              @csrf
              <input type="hidden" name="payment_id" value="{{ $p->id }}">
              <button type="submit" class="btn btn-primary btn-sm" style="padding:4px 10px;font-size:11px">Pay Online</button>
            </form>
          @else
            <span style="color:#16a34a;font-weight:600;font-size:12px">Paid ✓</span>
          @endif
        </td>
      </tr>
      @empty
      <tr><td colspan="7" style="text-align:center;padding:30px;color:#9ca3af">কোনো payment নেই।</td></tr>
      @endforelse
    </tbody>
  </table>
  <div class="pagination">{{ $payments->links() }}</div>
</div>
@endsection
