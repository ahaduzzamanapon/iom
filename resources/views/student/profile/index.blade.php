@extends('layouts.student')
@section('title','My Profile')
@section('content')

<div class="container-fluid" style="padding: 24px; max-width: 1200px; margin: 0 auto; font-family: 'Inter', sans-serif;">
  
  {{-- Page Header --}}
  <div class="page-header" style="margin-bottom: 24px;">
    <h2 class="page-title" style="font-size: 24px; font-weight: 700; color: #1f2937; margin: 0;">My Profile</h2>
    <p style="font-size: 14px; color: #6b7280; margin: 4px 0 0 0;">Manage your personal information and security settings.</p>
  </div>

  {{-- Success Alert --}}
  @if(session('success'))
    <div class="alert alert-success" style="margin-bottom: 24px; padding: 16px; background: #ecfdf5; border: 1px solid #10b981; border-radius: 12px; color: #065f46; display: flex; align-items: center; gap: 10px; font-weight: 500; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
      <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
      {{ session('success') }}
    </div>
  @endif

  {{-- Main Layout Grid --}}
  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; align-items: start;">
    
    @php $profile = auth()->user()->studentProfile; @endphp

    {{-- Left Column: Avatar Card --}}
    <div class="card" style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 16px; padding: 32px 24px; text-align: center; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);">
      <div style="position: relative; display: inline-block; margin-bottom: 16px;">
        @if($profile?->photo)
          <img src="{{ asset('storage/'.$profile->photo) }}" style="width: 110px; height: 110px; object-fit: cover; border-radius: 50%; border: 4px solid #ffffff; box-shadow: 0 4px 10px rgba(0,0,0,0.15);">
        @else
          <div style="width: 110px; height: 110px; border-radius: 50%; background: linear-gradient(135deg, #3b82f6, #1d4ed8); display: flex; align-items: center; justify-content: center; font-size: 40px; color: white; font-weight: 700; box-shadow: 0 4px 10px rgba(37, 99, 235, 0.3);">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
          </div>
        @endif
      </div>
      
      <h3 style="font-size: 20px; font-weight: 700; color: #111827; margin: 0 0 4px 0;">{{ auth()->user()->name }}</h3>
      <p style="color: #6b7280; font-size: 14px; margin: 0 0 12px 0;">{{ auth()->user()->email }}</p>
      
      @if($profile)
        <span style="font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; font-size: 12px; font-weight: 600; color: #4b5563; background: #f3f4f6; padding: 6px 14px; border-radius: 9999px; display: inline-block;">
          ID: {{ $profile->student_id }}
        </span>
      @endif
    </div>

    {{-- Right Column: Edit Form --}}
    <div class="card" style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 16px; padding: 32px; grid-column: span 2; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);">
      <h3 style="font-size: 18px; font-weight: 700; color: #111827; margin-top: 0; margin-bottom: 24px; border-bottom: 1px solid #f3f4f6; padding-bottom: 12px;">Update Information</h3>
      
      <form method="POST" action="{{ route('student.profile.update') }}">
        @csrf @method('PUT')

        {{-- Error Alert --}}
        @if($errors->any())
          <div class="alert alert-danger" style="margin-bottom: 24px; padding: 16px; background: #fef2f2; border: 1px solid #fca5a5; border-radius: 12px; color: #991b1b; font-size: 14px;">
            <div style="font-weight: 600; margin-bottom: 6px;">Please fix the following errors:</div>
            @foreach($errors->all() as $e)<div style="margin-left: 8px;">• {{ $e }}</div>@endforeach
          </div>
        @endif

        {{-- Form Fields Group --}}
        <div style="display: flex; flex-direction: column; gap: 20px;">
          
          {{-- Phone --}}
          <div class="form-group">
            <label class="form-label" style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 6px;">Phone Number</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $profile?->phone) }}" placeholder="01XXXXXXXXX" style="width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; box-sizing: border-box; transition: border-color 0.2s;">
          </div>

          {{-- Guardian Info (Two Columns) --}}
          <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
            <div class="form-group">
              <label class="form-label" style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 6px;">Guardian Name</label>
              <input type="text" name="guardian_name" class="form-control" value="{{ old('guardian_name', $profile?->guardian_name) }}" style="width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
            </div>
            <div class="form-group">
              <label class="form-label" style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 6px;">Guardian Phone</label>
              <input type="text" name="guardian_phone" class="form-control" value="{{ old('guardian_phone', $profile?->guardian_phone) }}" style="width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
            </div>
          </div>

          {{-- Address --}}
          <div class="form-group">
            <label class="form-label" style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 6px;">Address</label>
            <textarea name="address" class="form-control" rows="3" style="width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; font-family: inherit; box-sizing: border-box; resize: vertical;">{{ old('address', $profile?->address) }}</textarea>
          </div>

          {{-- Password Section --}}
          <div style="border-top: 1px solid #e5e7eb; padding-top: 20px; margin-top: 8px;">
            <div style="font-size: 14px; font-weight: 700; margin-bottom: 14px; color: #4b5563;">Change Password <span style="font-size: 12px; font-weight: 400; color: #9ca3af;">(Optional)</span></div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
              <div class="form-group">
                <label class="form-label" style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 6px;">New Password</label>
                <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current" style="width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
              </div>
              <div class="form-group">
                <label class="form-label" style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 6px;">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
              </div>
            </div>
          </div>

        </div>

        {{-- Submit Button --}}
        <div style="margin-top: 32px; text-align: right;">
          <button type="submit" class="btn btn-primary" style="background: #2563eb; color: white; border: none; padding: 12px 24px; font-size: 14px; font-weight: 600; border-radius: 8px; cursor: pointer; box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2); transition: background 0.2s;">
            Save Changes
          </button>
        </div>

      </form>
    </div>
  </div>
</div>

@endsection