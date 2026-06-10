@extends('layouts.admin')
@section('title', 'Batch Transfers')
@section('page-title', 'Batch Transfers')

@section('content')
<div class="page-header">
  <div>
    <div class="page-title">🔀 Batch / Course Transfers</div>
  </div>
  <button class="btn btn-primary" onclick="var el = document.getElementById('transferFormCard'); el.style.display = el.style.display === 'none' ? 'block' : 'none';">
    + New Transfer
  </button>
</div>

{{-- Transfer Form Card (Toggleable) --}}
<div class="card" id="transferFormCard" style="display:none;margin-bottom:20px">
  <div class="card-header">
    <span class="card-title">🔀 Create New Batch Transfer</span>
  </div>
  <div class="card-body">
    <form action="{{ route('admin.transfers.store') }}" method="POST">
      @csrf
      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Student *</label>
          <select name="user_id" class="form-control" required>
            <option value="">— বেছে নিন —</option>
            @foreach(\App\Models\StudentProfile::with('user')->get() as $s)
              <option value="{{ $s->user_id }}">{{ $s->student_id }} — {{ $s->user->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">From Batch *</label>
          <select name="from_batch_id" class="form-control" required>
            <option value="">— বেছে নিন —</option>
            @foreach(\App\Models\Batch::with('course')->get() as $b)
              <option value="{{ $b->id }}">{{ $b->name }} ({{ $b->course->name ?? '' }})</option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">To Batch *</label>
          <select name="to_batch_id" class="form-control" required>
            <option value="">— বেছে নিন —</option>
            @foreach(\App\Models\Batch::with('course')->get() as $b)
              <option value="{{ $b->id }}">{{ $b->name }} ({{ $b->course->name ?? '' }})</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="form-group" style="margin-top:16px">
        <label class="form-label">Reason</label>
        <textarea name="reason" class="form-control" rows="3" placeholder="Transfer করার কারণ লিখুন..."></textarea>
      </div>

      <div style="margin-top:20px">
        <button type="submit" class="btn btn-primary">Transfer সম্পন্ন করুন</button>
        <button type="button" class="btn btn-outline" style="margin-left:8px" onclick="document.getElementById('transferFormCard').style.display='none'">Cancel</button>
      </div>
    </form>
  </div>
</div>

<div class="card">
  <div class="card-body" style="padding:0">
    <table class="dt-table">
      <thead>
        <tr>
          <th>Student</th>
          <th>From Batch</th>
          <th>To Batch</th>
          <th>Status</th>
          <th>Transferred At</th>
          <th>Approved By</th>
        </tr>
      </thead>
      <tbody>
        @forelse($transfers as $t)
        <tr>
          <td>
            <strong>{{ $t->user->name }}</strong><br>
            <span style="font-size:11px;color:#718096">{{ $t->user->studentProfile?->student_id }}</span>
          </td>
          <td>{{ $t->fromBatch->name }} <span style="font-size:11px;color:#718096">({{ $t->fromBatch->course->name ?? '' }})</span></td>
          <td>{{ $t->toBatch->name }} <span style="font-size:11px;color:#718096">({{ $t->toBatch->course->name ?? '' }})</span></td>
          <td><span class="badge badge-green">Approved</span></td>
          <td>{{ $t->transferred_at?->format('d M Y') }}</td>
          <td>{{ $t->approver?->name ?? 'Admin' }}</td>
        </tr>
        @empty
        <tr>
          <td colspan="6" style="text-align:center;padding:30px;color:#9ca3af">কোনো transfer রেকর্ড নেই।</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="pagination">{{ $transfers->links() }}</div>
@endsection
