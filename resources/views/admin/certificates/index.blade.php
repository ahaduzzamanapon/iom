@extends('layouts.admin')
@section('title','Certificates')
@section('page-title','Certificate Management')
@section('content')
<div class="page-header">
  <div><div class="page-title">Certificates</div><div class="page-sub">কোর্স সম্পন্নকারীদের সার্টিফিকেট ইস্যু করুন</div></div>
</div>

{{-- Generate Certificate Modal Trigger --}}
<div class="card" style="margin-bottom:16px">
  <div class="card-body" style="display:flex;align-items:center;gap:16px;flex-wrap:wrap">
    <span style="font-size:14px;color:#6b7280">নতুন সার্টিফিকেট ইস্যু করতে Student Management থেকে student profile খুলুন।</span>
    <a href="{{ route('admin.students.index') }}" class="btn btn-primary">→ Student List</a>
  </div>
</div>

<div class="card">
  <x-data-table :headers="['#','Certificate No.','Student','Course','Issued Date','QR Code','Actions']">
    @forelse($certificates as $c)
    <tr>
      <td><input type="checkbox" name="ids[]" value="{{ $c->id }}"></td>
      <td>{{ $loop->iteration }}</td>
      <td style="font-family:monospace;font-weight:600;font-size:12px">{{ $c->certificate_number }}</td>
      <td>
        <div style="font-weight:600">{{ $c->student->name ?? '—' }}</div>
        <div style="font-size:11px;color:#9ca3af">{{ $c->student->studentProfile->student_id ?? '' }}</div>
      </td>
      <td>{{ $c->course->name ?? '—' }}</td>
      <td>{{ $c->issued_date ? \Carbon\Carbon::parse($c->issued_date)->format('d M Y') : '—' }}</td>
      <td>
        @if($c->qr_code)
          <img src="{{ asset('uploads/'.$c->qr_code) }}" style="width:40px;height:40px;border:1px solid #e5e7eb;border-radius:4px" alt="QR">
        @else —
        @endif
      </td>
      <td>
        <form method="POST" action="{{ route('admin.certificates.generate', $c->student_id) }}" style="display:inline">
          @csrf
          <input type="hidden" name="course_id" value="{{ $c->course_id }}">
          <button class="btn btn-sm btn-outline">↓ Re-Download</button>
        </form>
      </td>
    </tr>
    @empty
    <tr><td colspan="7" style="text-align:center;padding:30px;color:#9ca3af">কোনো certificate ইস্যু হয়নি।</td></tr>
    @endforelse
  </x-data-table>
  <div class="pagination">{{ $certificates->links() }}</div>
</div>
@endsection
