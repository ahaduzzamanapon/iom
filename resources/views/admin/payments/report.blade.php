@extends('layouts.admin')
@section('title','Finance Report')
@section('page-title','Finance Report')
@section('content')
<div class="page-header">
  <div><div class="page-title">Finance Report</div><div class="page-sub">আর্থিক সংক্ষিপ্ত বিবরণ</div></div>
  <div style="display:flex;gap:10px">
    <a href="{{ route('admin.finance.export','pdf') }}" class="btn btn-outline">↓ PDF</a>
    <a href="{{ route('admin.payments.index') }}" class="btn btn-outline">← Back</a>
  </div>
</div>

<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px">
  <div class="card">
    <div class="card-body" style="text-align:center">
      <div style="font-size:28px;font-weight:700;color:#10b981">৳ {{ number_format($total, 2) }}</div>
      <div style="color:#6b7280;font-size:13px;margin-top:4px">Total Collected</div>
    </div>
  </div>
  <div class="card">
    <div class="card-body" style="text-align:center">
      <div style="font-size:28px;font-weight:700;color:#f59e0b">{{ $pending }}</div>
      <div style="color:#6b7280;font-size:13px;margin-top:4px">Pending / Partial</div>
    </div>
  </div>
  <div class="card">
    <div class="card-body" style="text-align:center">
      @php $thisMonth = \App\Models\Payment::where('status','paid')->whereMonth('paid_at', now()->month)->sum('paid_amount'); @endphp
      <div style="font-size:28px;font-weight:700;color:var(--primary)">৳ {{ number_format($thisMonth, 2) }}</div>
      <div style="color:#6b7280;font-size:13px;margin-top:4px">This Month</div>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-body">
    <div style="font-size:15px;font-weight:700;margin-bottom:16px">Recent Payments</div>
    <table style="width:100%;border-collapse:collapse;font-size:13px">
      <thead>
        <tr style="border-bottom:1px solid var(--border)">
          <th style="padding:8px;text-align:left;color:#6b7280">Invoice</th>
          <th style="padding:8px;text-align:left;color:#6b7280">Student</th>
          <th style="padding:8px;text-align:left;color:#6b7280">Amount</th>
          <th style="padding:8px;text-align:left;color:#6b7280">Status</th>
          <th style="padding:8px;text-align:left;color:#6b7280">Date</th>
        </tr>
      </thead>
      <tbody>
        @foreach(\App\Models\Payment::with('student')->latest()->take(10)->get() as $p)
        <tr style="border-bottom:1px solid var(--border)">
          <td style="padding:8px;font-family:monospace;font-size:11px">{{ $p->invoice_number }}</td>
          <td style="padding:8px">{{ $p->student->name ?? '—' }}</td>
          <td style="padding:8px;font-weight:600">৳ {{ number_format($p->paid_amount, 2) }}</td>
          <td style="padding:8px"><span class="badge {{ $p->status==='paid'?'badge-green':($p->status==='pending'?'badge-yellow':'badge-blue') }}">{{ ucfirst($p->status) }}</span></td>
          <td style="padding:8px;font-size:11px">{{ $p->created_at->format('d M Y') }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
