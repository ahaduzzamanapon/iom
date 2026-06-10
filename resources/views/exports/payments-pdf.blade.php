@extends('exports.pdf-layout')
@section('content')
<table>
  <thead>
    <tr>
      <th>#</th>
      <th>Invoice No.</th>
      <th>Student</th>
      <th>Fee Type</th>
      <th>Amount</th>
      <th>Discount</th>
      <th>Paid</th>
      <th>Method</th>
      <th>Status</th>
      <th>Date</th>
    </tr>
  </thead>
  <tbody>
    @forelse($payments as $i => $p)
    <tr>
      <td>{{ $i + 1 }}</td>
      <td style="font-family:monospace;font-size:10px">{{ $p->invoice_number }}</td>
      <td>{{ $p->student->name ?? '—' }}</td>
      <td>{{ $p->feeStructure->title ?? '—' }}</td>
      <td>৳ {{ number_format($p->amount, 2) }}</td>
      <td>৳ {{ number_format($p->discount ?? 0, 2) }}</td>
      <td style="font-weight:bold">৳ {{ number_format($p->paid_amount, 2) }}</td>
      <td>{{ ucfirst($p->payment_method) }}</td>
      <td style="text-transform:capitalize">{{ $p->status }}</td>
      <td>{{ $p->paid_at ? \Carbon\Carbon::parse($p->paid_at)->format('d M Y') : '—' }}</td>
    </tr>
    @empty
    <tr><td colspan="10" style="text-align:center;padding:16px">No records found.</td></tr>
    @endforelse
  </tbody>
</table>
@php
  $totalPaid = $payments->where('status','paid')->sum('paid_amount');
@endphp
<div style="margin-top:12px;font-size:11px;color:#555">
  Total Records: {{ $payments->count() }} &nbsp;|&nbsp; Total Collected: ৳ {{ number_format($totalPaid, 2) }}
</div>
@endsection
