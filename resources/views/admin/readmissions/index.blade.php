@extends('layouts.admin')
@section('title', 'Readmission Applications')
@section('page-title', 'Readmission Applications')

@section('content')
<div class="page-header">
  <div>
    <div class="page-title">🔄 Readmission Applications</div>
  </div>
  <div style="display:flex;gap:8px">
    <a href="{{ route('admin.readmissions.create') }}" class="btn btn-primary">Direct Readmission</a>
    <a href="{{ route('admin.transfers.index') }}" class="btn btn-outline">Batch Transfers</a>
  </div>
</div>

<div class="card">
  <div class="card-body" style="padding:0">
    <table class="dt-table">
      <thead>
        <tr>
          <th>Student</th>
          <th>Course</th>
          <th>Reason</th>
          <th>Status</th>
          <th>Applied At</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($readmissions as $r)
        <tr>
          <td>
            <strong>{{ $r->user->name }}</strong><br>
            <span style="font-size:11px;color:#718096">{{ $r->user->studentProfile?->student_id }}</span>
          </td>
          <td>{{ $r->course->name }}</td>
          <td><span style="font-size:12px;color:#4a5568">{{ Str::limit($r->reason, 80) }}</span></td>
          <td>
            <span class="badge {{ $r->status === 'approved' ? 'badge-green' : ($r->status === 'rejected' ? 'badge-red' : 'badge-yellow') }}">
              {{ ucfirst($r->status) }}
            </span>
          </td>
          <td>{{ $r->created_at->format('d M Y') }}</td>
          <td style="white-space:nowrap">
            @if($r->status === 'pending')
              <button class="btn btn-sm btn-success" onclick="document.getElementById('approve-row-{{ $r->id }}').style.display='table-row'; document.getElementById('reject-row-{{ $r->id }}').style.display='none';">✓ Approve</button>
              <button class="btn btn-sm btn-danger" onclick="document.getElementById('reject-row-{{ $r->id }}').style.display='table-row'; document.getElementById('approve-row-{{ $r->id }}').style.display='none';">✗ Reject</button>
            @else
              <span style="font-size:12px;color:#718096">Reviewer: {{ $r->reviewer?->name ?? '—' }}</span>
            @endif
          </td>
        </tr>

        @if($r->status === 'pending')
        {{-- Inline Approve Form --}}
        <tr id="approve-row-{{ $r->id }}" style="display:none;background:#f0fdf4">
          <td colspan="6" style="padding:16px">
            <form method="POST" action="{{ route('admin.readmissions.approve', $r) }}" style="display:flex;gap:12px;align-items:flex-end">
              @csrf
              <div class="form-group" style="margin:0">
                <label class="form-label">Batch নির্ধারণ করুন *</label>
                <select name="batch_id" class="form-control" required style="min-width:260px">
                  <option value="">— Batch বেছে নিন —</option>
                  @foreach(\App\Models\Batch::where('status','active')->with('course')->get() as $b)
                    @if($b->course_id === $r->course_id)
                      <option value="{{ $b->id }}">{{ $b->name }} ({{ $b->course->name }})</option>
                    @endif
                  @endforeach
                </select>
              </div>
              <button class="btn btn-success">✓ Confirm Approve</button>
              <button type="button" class="btn btn-outline" onclick="document.getElementById('approve-row-{{ $r->id }}').style.display='none'">Cancel</button>
            </form>
          </td>
        </tr>

        {{-- Inline Reject Form --}}
        <tr id="reject-row-{{ $r->id }}" style="display:none;background:#fff1f2">
          <td colspan="6" style="padding:16px">
            <form method="POST" action="{{ route('admin.readmissions.reject', $r) }}" style="display:flex;gap:12px;align-items:flex-end;width:100%">
              @csrf
              <div class="form-group" style="margin:0;flex:1">
                <label class="form-label">প্রত্যাখ্যানের কারণ লিখুন (Remarks) *</label>
                <input type="text" name="remarks" class="form-control" required placeholder="কারণ লিখুন...">
              </div>
              <button class="btn btn-danger">✗ Confirm Reject</button>
              <button type="button" class="btn btn-outline" onclick="document.getElementById('reject-row-{{ $r->id }}').style.display='none'">Cancel</button>
            </form>
          </td>
        </tr>
        @endif

        @empty
        <tr>
          <td colspan="6" style="text-align:center;padding:30px;color:#9ca3af">কোনো readmission আবেদন নেই।</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
<div class="pagination">{{ $readmissions->links() }}</div>
@endsection
