@extends('layouts.teacher')
@section('title','Schedule Live Class')
@section('content')
<div class="page-header">
  <div class="page-title">Schedule Live Class</div>
  <a href="{{ route('teacher.classes.index') }}" class="btn btn-outline">← Back</a>
</div>

@if(session('error'))
  <div class="alert alert-danger" style="margin-bottom:16px;padding:12px 16px;background:#fee2e2;border:1px solid #fca5a5;border-radius:8px;color:#991b1b">
    {{ session('error') }}
  </div>
@endif

<div class="card" style="max-width:680px">
  <div class="card-body">
    <form method="POST" action="{{ route('teacher.live-class.store') }}">
      @csrf
      @if($errors->any())
        <div class="alert alert-danger" style="margin-bottom:16px">
          @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
        </div>
      @endif

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div class="form-group">
          <label class="form-label">Batch <span style="color:red">*</span></label>
          <select name="batch_id" class="form-control" required>
            <option value="">-- Select Batch --</option>
            @foreach($batches as $b)
              <option value="{{ $b->id }}" {{ old('batch_id') == $b->id ? 'selected':'' }}>
                {{ $b->name }} ({{ $b->course->name ?? '' }})
              </option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Module <span style="color:red">*</span></label>
          <select name="module_id" class="form-control" required>
            <option value="">-- Select Module --</option>
            @foreach($modules as $m)
              <option value="{{ $m->id }}" {{ old('module_id') == $m->id ? 'selected':'' }}>
                {{ $m->name }} ({{ $m->subject->name ?? '' }})
              </option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Class Title <span style="color:red">*</span></label>
        <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
      </div>

      {{-- Platform Selector --}}
      <div class="form-group">
        <label class="form-label">Meeting Platform <span style="color:red">*</span></label>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px">
          @foreach([
            'zoom'        => ['icon'=>'🎥','label'=>'Zoom','sub'=>'Auto-generate'],
            'google_meet' => ['icon'=>'📹','label'=>'Google Meet','sub'=>'Auto-generate'],
            'manual'      => ['icon'=>'🔗','label'=>'Manual Link','sub'=>'Paste your own'],
          ] as $val => $opt)
          <label style="cursor:pointer">
            <input type="radio" name="platform" value="{{ $val }}" class="platform-radio"
              {{ old('platform','zoom') === $val ? 'checked':'' }} style="display:none">
            <div class="platform-card {{ old('platform','zoom') === $val ? 'selected':'' }}"
              style="border:2px solid {{ old('platform','zoom') === $val ? 'var(--primary)':'var(--border)' }};border-radius:10px;padding:14px;text-align:center;transition:all .2s">
              <div style="font-size:24px">{{ $opt['icon'] }}</div>
              <div style="font-weight:700;font-size:13px;margin-top:4px">{{ $opt['label'] }}</div>
              <div style="font-size:11px;color:#9ca3af">{{ $opt['sub'] }}</div>
            </div>
          </label>
          @endforeach
        </div>
      </div>

      {{-- Manual link (hidden by default) --}}
      <div id="manual-link-wrap" class="form-group" style="display:{{ old('platform') === 'manual' ? 'block':'none' }}">
        <label class="form-label">Meeting Link <span style="color:red">*</span></label>
        <input type="url" name="meeting_link" class="form-control" value="{{ old('meeting_link') }}"
          placeholder="https://zoom.us/j/... or https://meet.google.com/...">
        <div style="font-size:12px;color:#9ca3af;margin-top:4px">Zoom বা Google Meet link manually paste করুন।</div>
      </div>

      {{-- Info box for auto platforms --}}
      <div id="auto-info" style="display:{{ old('platform','zoom') !== 'manual' ? 'block':'none' }};background:rgba(99,102,241,.08);border:1px solid rgba(99,102,241,.3);border-radius:8px;padding:12px 14px;font-size:13px;color:var(--primary);margin-bottom:16px">
        ✨ Meeting link স্বয়ংক্রিয়ভাবে তৈরি হবে — কোনো link paste করতে হবে না।
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div class="form-group">
          <label class="form-label">Scheduled At <span style="color:red">*</span></label>
          <input type="datetime-local" name="scheduled_at" class="form-control" value="{{ old('scheduled_at') }}" required>
        </div>
        <div class="form-group">
          <label class="form-label">Duration (minutes)</label>
          <input type="number" name="duration_mins" class="form-control" value="{{ old('duration_mins', 60) }}" min="1">
        </div>
      </div>

      <div style="display:flex;gap:12px">
        <button type="submit" class="btn btn-primary">Schedule Live Class</button>
        <a href="{{ route('teacher.classes.index') }}" class="btn btn-outline">Cancel</a>
      </div>
    </form>
  </div>
</div>

<style>
.platform-radio:checked + .platform-card,
.platform-card.selected {
  border-color: var(--primary) !important;
  background: rgba(99,102,241,.06);
}
.platform-card:hover { border-color: var(--primary) !important; }
</style>

<script>
document.querySelectorAll('.platform-radio').forEach(function(radio) {
  radio.addEventListener('change', function() {
    // Update card styles
    document.querySelectorAll('.platform-card').forEach(c => {
      c.style.borderColor = 'var(--border)';
      c.classList.remove('selected');
    });
    this.nextElementSibling.style.borderColor = 'var(--primary)';
    this.nextElementSibling.classList.add('selected');

    // Show/hide manual link
    var isManual = this.value === 'manual';
    document.getElementById('manual-link-wrap').style.display = isManual ? 'block' : 'none';
    document.getElementById('auto-info').style.display        = isManual ? 'none'  : 'block';

    // Required toggle
    var linkInput = document.querySelector('[name="meeting_link"]');
    linkInput.required = isManual;
  });
});
</script>
@endsection
