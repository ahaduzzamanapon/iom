@extends('layouts.admin')
@section('title','Admission Detail')
@section('page-title','Admissions')
@section('content')
<div class="page-header">
  <div class="page-title">Admission Detail</div>
  <a href="{{ route('admin.admissions.index') }}" class="btn btn-outline">← Back</a>
</div>

@if(session('success'))
  <div class="alert alert-success" style="margin-bottom:16px;padding:12px 16px;background:#d1fae5;border:1px solid #6ee7b7;border-radius:8px;color:#065f46">
    {{ session('success') }}
  </div>
@endif

<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px">
  {{-- Details --}}
  <div class="card">
    <div class="card-body">
      <h3 style="font-size:16px;font-weight:700;margin-bottom:16px;color:var(--text-primary)">Applicant Info</h3>
      <table style="width:100%;border-collapse:collapse;font-size:14px">
        <tr><td style="padding:8px 0;color:#6b7280;width:160px">Name</td><td style="padding:8px 0;font-weight:600">{{ $admission->applicant_name }}</td></tr>
        <tr><td style="padding:8px 0;color:#6b7280">Email</td><td style="padding:8px 0">{{ $admission->applicant_email }}</td></tr>
        <tr><td style="padding:8px 0;color:#6b7280">Phone</td><td style="padding:8px 0">{{ $admission->applicant_phone }}</td></tr>
        <tr><td style="padding:8px 0;color:#6b7280">Date of Birth</td><td style="padding:8px 0">{{ $admission->date_of_birth ?? '—' }}</td></tr>
        <tr><td style="padding:8px 0;color:#6b7280">Gender</td><td style="padding:8px 0">{{ ucfirst($admission->gender ?? '—') }}</td></tr>
        <tr><td style="padding:8px 0;color:#6b7280">Guardian</td><td style="padding:8px 0">{{ $admission->guardian_name ?? '—' }} ({{ $admission->guardian_phone ?? '' }})</td></tr>
        <tr><td style="padding:8px 0;color:#6b7280">Address</td><td style="padding:8px 0">{{ $admission->address ?? '—' }}</td></tr>
        <tr><td style="padding:8px 0;color:#6b7280">Course</td><td style="padding:8px 0;font-weight:600">{{ $admission->course->name ?? '—' }}</td></tr>
        <tr><td style="padding:8px 0;color:#6b7280">Applied At</td><td style="padding:8px 0">{{ $admission->created_at->format('d M Y, h:i A') }}</td></tr>
        @if($admission->remarks)
        <tr><td style="padding:8px 0;color:#6b7280">Remarks</td><td style="padding:8px 0;color:#dc2626">{{ $admission->remarks }}</td></tr>
        @endif
      </table>

      @if($admission->photo)
        <div style="margin-top:16px">
          <label style="font-size:12px;color:#6b7280;display:block;margin-bottom:6px">Photo</label>
          <img src="{{ asset('storage/'.$admission->photo) }}" style="width:100px;height:100px;object-fit:cover;border-radius:8px;border:1px solid var(--border)">
        </div>
      @endif
    </div>
  </div>

  {{-- Actions --}}
  <div>
    <div class="card" style="margin-bottom:16px">
      <div class="card-body">
        <div style="font-size:14px;font-weight:600;margin-bottom:8px">Status</div>
        <span class="badge {{ $admission->status==='approved'?'badge-green':($admission->status==='rejected'?'badge-red':'badge-yellow') }}" style="font-size:14px;padding:6px 12px">
          {{ ucfirst($admission->status) }}
        </span>
        @if($admission->reviewer)
          <div style="font-size:12px;color:#6b7280;margin-top:8px">Reviewed by: {{ $admission->reviewer->name }}</div>
        @endif
      </div>
    </div>

    @if($admission->status === 'pending')
    {{-- Approve --}}
    <div class="card" style="margin-bottom:16px">
      <div class="card-body">
        <div style="font-size:14px;font-weight:600;margin-bottom:12px;color:#065f46">✓ Approve Admission</div>
        <form method="POST" action="{{ route('admin.admissions.approve',$admission) }}">
          @csrf
          <div class="form-group">
            <label class="form-label">Assign to Batch <span style="color:red">*</span></label>
            <select name="batch_id" class="form-control" required>
              <option value="">-- Select Batch --</option>
              @foreach(\App\Models\Batch::where('status','active')->with('course')->get() as $b)
                <option value="{{ $b->id }}">{{ $b->name }} ({{ $b->course->name ?? '' }})</option>
              @endforeach
            </select>
          </div>
          <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">Approve & Create Account</button>
        </form>
      </div>
    </div>

    {{-- Reject --}}
    <div class="card">
      <div class="card-body">
        <div style="font-size:14px;font-weight:600;margin-bottom:12px;color:#dc2626">✕ Reject Admission</div>
        <form method="POST" action="{{ route('admin.admissions.reject',$admission) }}">
          @csrf
          <div class="form-group">
            <label class="form-label">Remarks <span style="color:red">*</span></label>
            <textarea name="remarks" class="form-control" rows="3" required placeholder="কারণ লিখুন..."></textarea>
          </div>
          <button type="submit" class="btn btn-danger" style="width:100%;justify-content:center" onclick="return confirm('Reject করবেন?')">Reject</button>
        </form>
      </div>
    </div>
    @endif
  </div>
</div>
@endsection
