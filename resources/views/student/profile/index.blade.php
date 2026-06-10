@extends('layouts.student')
@section('title','My Profile')
@section('content')
<div class="page-header">
  <div class="page-title">My Profile</div>
</div>

@if(session('success'))
  <div class="alert alert-success" style="margin-bottom:16px;padding:12px 16px;background:#d1fae5;border:1px solid #6ee7b7;border-radius:8px;color:#065f46">
    {{ session('success') }}
  </div>
@endif

<div style="display:grid;grid-template-columns:1fr 2fr;gap:20px">
  {{-- Avatar Card --}}
  <div class="card" style="text-align:center;padding:32px 20px">
    @php $profile = auth()->user()->studentProfile; @endphp
    @if($profile?->photo)
      <img src="{{ asset('storage/'.$profile->photo) }}" style="width:100px;height:100px;object-fit:cover;border-radius:50%;margin:0 auto 12px;border:4px solid var(--primary)">
    @else
      <div style="width:100px;height:100px;border-radius:50%;background:var(--primary);display:flex;align-items:center;justify-content:center;font-size:36px;color:white;font-weight:700;margin:0 auto 12px">
        {{ strtoupper(substr(auth()->user()->name,0,1)) }}
      </div>
    @endif
    <div style="font-size:18px;font-weight:700">{{ auth()->user()->name }}</div>
    <div style="color:#9ca3af;font-size:13px;margin:4px 0">{{ auth()->user()->email }}</div>
    @if($profile)
      <div style="font-family:monospace;font-size:12px;background:var(--sidebar-bg);padding:4px 10px;border-radius:20px;display:inline-block;margin-top:8px">
        {{ $profile->student_id }}
      </div>
    @endif
  </div>

  {{-- Edit Form --}}
  <div class="card">
    <div class="card-body">
      <h3 style="font-size:16px;font-weight:700;margin-bottom:20px">Update Information</h3>
      <form method="POST" action="{{ route('student.profile.update') }}">
        @csrf @method('PUT')

        @if($errors->any())
          <div class="alert alert-danger" style="margin-bottom:16px">
            @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
          </div>
        @endif

        <div class="form-group">
          <label class="form-label">Phone</label>
          <input type="text" name="phone" class="form-control" value="{{ old('phone', $profile?->phone) }}" placeholder="01XXXXXXXXX">
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
          <div class="form-group">
            <label class="form-label">Guardian Name</label>
            <input type="text" name="guardian_name" class="form-control" value="{{ old('guardian_name', $profile?->guardian_name) }}">
          </div>
          <div class="form-group">
            <label class="form-label">Guardian Phone</label>
            <input type="text" name="guardian_phone" class="form-control" value="{{ old('guardian_phone', $profile?->guardian_phone) }}">
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Address</label>
          <textarea name="address" class="form-control" rows="3">{{ old('address', $profile?->address) }}</textarea>
        </div>

        <div style="border-top:1px solid var(--border);padding-top:16px;margin-top:8px">
          <div style="font-size:13px;font-weight:600;margin-bottom:12px;color:#6b7280">Change Password (optional)</div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
            <div class="form-group">
              <label class="form-label">New Password</label>
              <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current">
            </div>
            <div class="form-group">
              <label class="form-label">Confirm Password</label>
              <input type="password" name="password_confirmation" class="form-control">
            </div>
          </div>
        </div>

        <button type="submit" class="btn btn-primary">Update Profile</button>
      </form>
    </div>
  </div>
</div>
@endsection
