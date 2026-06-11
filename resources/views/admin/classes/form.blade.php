@extends('layouts.admin')
@section('title', (isset($class) && $class->exists)?'Edit Class':'New Class')
@section('page-title', (isset($class) && $class->exists)?'Edit Class':'New Class')
@section('content')
<div class="page-header"><div class="page-title">{{ (isset($class) && $class->exists)?'Edit Class':'New Class' }}</div><a href="{{ route('admin.classes.index') }}" class="btn btn-outline">← Back</a></div>
<div class="card"><div class="card-body">
  <form method="POST" action="{{ (isset($class) && $class->exists)?route('admin.classes.update',$class):route('admin.classes.store') }}" enctype="multipart/form-data">
    @csrf @if(isset($class) && $class->exists) @method('PUT') @endif
    <div class="form-grid">
      <div class="form-group"><label class="form-label">Module *</label>
        <select name="module_id" class="form-control" required>
          <option value="">Select</option>
          @foreach($modules as $m)<option value="{{ $m->id }}" {{ old('module_id',($class ?? null)?->module_id??'')==$m->id?'selected':'' }}>{{ $m->name }} / {{ $m->subject->name??'' }}</option>@endforeach
        </select></div>
      <div class="form-group"><label class="form-label">Batch *</label>
        <select name="batch_id" class="form-control" required>
          <option value="">Select</option>
          @foreach($batches as $b)<option value="{{ $b->id }}" {{ old('batch_id',($class ?? null)?->batch_id??'')==$b->id?'selected':'' }}>{{ $b->name }}</option>@endforeach
        </select></div>
      <div class="form-group"><label class="form-label">Title *</label><input type="text" name="title" class="form-control" value="{{ old('title',($class ?? null)?->title??'') }}" required></div>
      <div class="form-group"><label class="form-label">Title (বাংলা)</label><input type="text" name="title_bn" class="form-control" value="{{ old('title_bn',($class ?? null)?->title_bn??'') }}"></div>
      <div class="form-group"><label class="form-label">Type *</label>
        <select name="type" id="type-select" class="form-control" required>
          @foreach(['video'=>'Video','live'=>'Live','pdf'=>'PDF','note'=>'Note'] as $v=>$l)<option value="{{ $v }}" {{ old('type',($class ?? null)?->type??'')===$v?'selected':'' }}>{{ $l }}</option>@endforeach
        </select></div>
      <div class="form-group"><label class="form-label">Order *</label><input type="number" name="order" class="form-control" value="{{ old('order',($class ?? null)?->order??1) }}" required></div>
      
      <div class="form-group" id="youtube-wrap"><label class="form-label">YouTube URL</label><input type="url" name="youtube_url" class="form-control" value="{{ old('youtube_url',($class ?? null)?->youtube_url??'') }}"></div>
      
      <div class="form-group" id="provider-wrap" style="display:none"><label class="form-label">Meeting Provider *</label>
        <select name="meeting_provider" id="provider-select" class="form-control">
          <option value="manual" {{ old('meeting_provider',($class ?? null)?->meeting_provider??'manual')==='manual'?'selected':'' }}>Manual Link</option>
          <option value="zoom" {{ old('meeting_provider',($class ?? null)?->meeting_provider??'')==='zoom'?'selected':'' }}>Zoom (Auto-Generate)</option>
          <option value="google_meet" {{ old('meeting_provider',($class ?? null)?->meeting_provider??'')==='google_meet'?'selected':'' }}>Google Meet (Auto-Generate)</option>
        </select></div>

      <div class="form-group" id="meet-wrap"><label class="form-label">Google Meet Link</label><input type="url" name="meet_link" class="form-control" value="{{ old('meet_link',($class ?? null)?->meet_link??'') }}"></div>
      <div class="form-group" id="zoom-wrap"><label class="form-label">Zoom Link</label><input type="url" name="zoom_link" class="form-control" value="{{ old('zoom_link',($class ?? null)?->zoom_link??'') }}"></div>
      
      <div class="form-group" id="scheduled-wrap"><label class="form-label">Scheduled At</label><input type="datetime-local" name="scheduled_at" class="form-control" value="{{ old('scheduled_at',($class ?? null)?->scheduled_at?->format('Y-m-d\TH:i')??'') }}"></div>
      
      {{-- FIXED DURATION WRAPPER AND INPUT VALUE --}}
      <div class="form-group" id="duration-wrap" style="display: {{ old('type', ($class ?? null)?->type ?? '') === 'live' ? 'block' : 'none' }}">
          <label class="form-label">Duration (minutes)</label>
          <input type="number" name="duration_mins" class="form-control" value="{{ old('duration_mins', ($class ?? null)?->duration_mins ?? 60) }}" min="1">
      </div>
      
      <div class="form-group" id="file-wrap"><label class="form-label">File (PDF/Doc)</label><input type="file" name="file_path" class="form-control"></div>
      <div class="form-group" style="display:flex;align-items:center;gap:8px;margin-top:24px">
        <input type="checkbox" name="is_published" id="pub" value="1" {{ old('is_published',($class ?? null)?->is_published??false)?'checked':'' }}>
        <label for="pub" class="form-label" style="margin:0;cursor:pointer">Publish immediately</label>
      </div>
    </div>
    <button type="submit" class="btn btn-primary">{{ (isset($class) && $class->exists)?'Update':'Create' }} Class</button>
  </form>
</div></div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var typeSelect = document.getElementById('type-select');
    var providerSelect = document.getElementById('provider-select');
    
    function toggleFields() {
        var type = typeSelect.value;
        var provider = providerSelect.value;
        
        // Hide all initially
        document.getElementById('youtube-wrap').style.display = 'none';
        document.getElementById('provider-wrap').style.display = 'none';
        document.getElementById('meet-wrap').style.display = 'none';
        document.getElementById('zoom-wrap').style.display = 'none';
        document.getElementById('scheduled-wrap').style.display = 'none';
        document.getElementById('duration-wrap').style.display = 'none';
        document.getElementById('file-wrap').style.display = 'none';
        
        if (type === 'video') {
            document.getElementById('youtube-wrap').style.display = 'block';
        } else if (type === 'live') {
            document.getElementById('provider-wrap').style.display = 'block';
            document.getElementById('scheduled-wrap').style.display = 'block';
            document.getElementById('duration-wrap').style.display = 'block';
            
            if (provider === 'manual') {
                document.getElementById('meet-wrap').style.display = 'block';
                document.getElementById('zoom-wrap').style.display = 'block';
            }
        } else if (type === 'pdf' || type === 'note') {
            document.getElementById('file-wrap').style.display = 'block';
        }
    }
    
    typeSelect.addEventListener('change', toggleFields);
    providerSelect.addEventListener('change', toggleFields);
    toggleFields(); // run initial load
});
</script>
@endpush
@endsection