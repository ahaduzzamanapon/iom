@extends('layouts.admin')
@section('title','Admissions')
@section('page-title','Admission Management')

@section('content')
<div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
  <div>
    <div class="page-title" style="margin: 0;">Admissions</div>
    <div class="page-sub" style="color: #718096; font-size: 14px;">ভর্তি আবেদন পরিচালনা করুন</div>
  </div>
  <div>
    <a href="{{ route('admin.admissions.create') }}" class="btn btn-primary">
      + New Admission
    </a>
  </div>
</div>

{{-- Filter --}}
<div class="card" style="margin-bottom:16px">
  <div class="card-body">
    <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end">
      <div class="form-group" style="margin:0;min-width:160px">
        <label class="form-label">Status</label>
        <select name="status" class="form-control">
          <option value="">All</option>
          <option value="pending"  {{ request('status')==='pending'  ? 'selected':'' }}>Pending</option>
          <option value="approved" {{ request('status')==='approved' ? 'selected':'' }}>Approved</option>
          <option value="rejected" {{ request('status')==='rejected' ? 'selected':'' }}>Rejected</option>
        </select>
      </div>
      <div class="form-group" style="margin:0;min-width:180px">
        <label class="form-label">Course</label>
        <select name="course_id" class="form-control">
          <option value="">All Courses</option>
          @foreach($courses as $c)
            <option value="{{ $c->id }}" {{ request('course_id')==$c->id ? 'selected':'' }}>{{ $c->name }}</option>
          @endforeach
        </select>
      </div>
      <button class="btn btn-primary">Filter</button>
      <a href="{{ route('admin.admissions.index') }}" class="btn btn-outline">Reset</a>
    </form>
  </div>
</div>

<div class="card">
  <x-data-table :headers="['#','Applicant','Course','Phone','Applied','Status','Actions']">
    @forelse($admissions as $a)
    <tr>
      <td><input type="checkbox" name="ids[]" value="{{ $a->id }}"></td>
      <td>{{ $loop->iteration }}</td>
      <td>
        <div style="font-weight:600">{{ $a->applicant_name }}</div>
        <div style="font-size:11px;color:#718096">{{ $a->applicant_email }}</div>
      </td>
      <td>{{ $a->course->name ?? '—' }}</td>
      <td>{{ $a->applicant_phone }}</td>
      <td>{{ $a->created_at->format('d M Y') }}</td>
      <td>
        <span class="badge {{ $a->status==='approved' ? 'badge-green' : ($a->status==='rejected' ? 'badge-red' : 'badge-yellow') }}">
          {{ ucfirst($a->status) }}
        </span>
      </td>
      <td style="white-space:nowrap">
        <a href="{{ route('admin.admissions.show',$a) }}" class="btn btn-sm btn-outline">View</a>
        
        @if($a->status==='pending')
          {{-- Approve Trigger Button --}}
          <button onclick="openModal('approveModal-{{ $a->id }}')" class="btn btn-sm btn-success">Approve</button>
          
          {{-- Reject Trigger Button --}}
          <button onclick="openModal('rejectModal-{{ $a->id }}')" class="btn btn-sm btn-danger">Reject</button>
        @endif
      </td>
    </tr>

    {{-- ---- POPUP MODALS ---- --}}
    @if($a->status==='pending')
      {{-- Approve Popup Modal --}}
      <div id="approveModal-{{ $a->id }}" class="custom-modal">
        <div class="custom-modal-content">
          <div class="custom-modal-header" style="border-bottom:1px solid #e2e8f0; padding-bottom:10px;">
            <h3 style="margin:0; color:#16a34a;">Approve Admission</h3>
            <span class="close-btn" onclick="closeModal('approveModal-{{ $a->id }}')">&times;</span>
          </div>
          <form method="POST" action="{{ route('admin.admissions.approve',$a) }}">
            @csrf
            <div class="custom-modal-body" style="padding: 20px 0;">
              <p>Applicant: <strong>{{ $a->applicant_name }}</strong></p>
              <div class="form-group">
                <label class="form-label">Assign Batch *</label>
                <select name="batch_id" class="form-control" required style="width:100%">
                  <option value="">Select Batch</option>
                  @foreach(\App\Models\Batch::where('status','active')->with('course')->get() as $b)
                    <option value="{{ $b->id }}">{{ $b->name }} ({{ $b->course->name ?? '' }})</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="custom-modal-footer" style="text-align:right; gap:10px; display:flex; justify-content:flex-end;">
              <button type="button" onclick="closeModal('approveModal-{{ $a->id }}')" class="btn btn-outline">Cancel</button>
              <button type="submit" class="btn btn-success">✓ Confirm Approve</button>
            </div>
          </form>
        </div>
      </div>

      {{-- Reject Popup Modal --}}
      <div id="rejectModal-{{ $a->id }}" class="custom-modal">
        <div class="custom-modal-content">
          <div class="custom-modal-header" style="border-bottom:1px solid #e2e8f0; padding-bottom:10px;">
            <h3 style="margin:0; color:#dc2626;">Reject Admission</h3>
            <span class="close-btn" onclick="closeModal('rejectModal-{{ $a->id }}')">&times;</span>
          </div>
          <form method="POST" action="{{ route('admin.admissions.reject',$a) }}">
            @csrf @method('POST')
            <div class="custom-modal-body" style="padding: 20px 0;">
              <p>Are you sure you want to reject <strong>{{ $a->applicant_name }}</strong>'s admission?</p>
              <div class="form-group">
                <label class="form-label">Rejection Remarks *</label>
                <input type="text" name="remarks" class="form-control" value="Admin rejected" required placeholder="Reason for rejection..." style="width:100%">
              </div>
            </div>
            <div class="custom-modal-footer" style="text-align:right; gap:10px; display:flex; justify-content:flex-end;">
              <button type="button" onclick="closeModal('rejectModal-{{ $a->id }}')" class="btn btn-outline">Cancel</button>
              <button type="submit" class="btn btn-danger">✕ Confirm Reject</button>
            </div>
          </form>
        </div>
      </div>
    @endif

    @empty
    <tr><td colspan="7" style="text-align:center;padding:30px;color:#9ca3af">কোনো admission নেই।</td></tr>
    @endforelse
  </x-data-table>
  <div class="pagination">{{ $admissions->links() }}</div>
</div>

{{-- Popup Modal CSS --}}
<style>
.custom-modal {
  display: none; 
  position: fixed; 
  z-index: 9999; 
  left: 0;
  top: 0;
  width: 100%; 
  height: 100%; 
  overflow: auto; 
  background-color: rgba(0,0,0,0.4); 
}
.custom-modal-content {
  background-color: #fefefe;
  margin: 15% auto; 
  padding: 20px;
  border: 1px solid #888;
  width: 100%;
  max-width: 500px;
  border-radius: 8px;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
  animation: fadeIn 0.3s;
}
.close-btn {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
  cursor: pointer;
}
.close-btn:hover {
  color: black;
}
@keyframes fadeIn {
  from {opacity: 0; transform: translateY(-10px);}
  to {opacity: 1; transform: translateY(0);}
}
</style>

{{-- Popup Modal JS --}}
<script>
function openModal(modalId) {
  document.getElementById(modalId).style.display = "block";
}

function closeModal(modalId) {
  document.getElementById(modalId).style.display = "none";
}

window.onclick = function(event) {
  if (event.target.classList.contains('custom-modal')) {
    event.target.style.display = "none";
  }
}
</script>
@endsection