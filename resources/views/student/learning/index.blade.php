@extends('layouts.student')
@section('title','My Classes')
@section('page-title','My Classes')
@section('content')
<div class="page-header">
  <div><div class="page-title">My Classes</div><div class="page-sub">আপনার সকল ক্লাস</div></div>
</div>
<div class="card">
  @forelse($lessons as $lesson)
  <div style="padding:16px 20px;border-bottom:1px solid #f0f4f8;display:flex;align-items:center;justify-content:space-between;gap:16px">
    <div style="display:flex;align-items:center;gap:14px">
      <div style="width:44px;height:44px;border-radius:10px;background:{{ $lesson->type==='live'?'#eff6ff':($lesson->type==='video'?'#fff1f2':'#f0fdf4') }};display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0">
        {{ $lesson->type==='live'?'📹':($lesson->type==='video'?'🎬':($lesson->type==='pdf'?'📄':'📝')) }}
      </div>
      <div>
        <div style="font-weight:600;font-size:14px">{{ $lesson->title }}</div>
        <div style="font-size:11px;color:#9ca3af">{{ $lesson->module->subject->name ?? '' }} → {{ $lesson->module->name ?? '' }}</div>
        @if($lesson->scheduled_at)
          <div style="font-size:11px;color:#4fc3f7;margin-top:2px">⏰ {{ $lesson->scheduled_at->format('d M, h:i A') }}</div>
        @endif
      </div>
    </div>
    <div style="display:flex;align-items:center;gap:8px;flex-shrink:0">
      @if(in_array($lesson->id, $completed))
        <span class="badge badge-green">✓ Completed</span>
      @else
        <button onclick="markDone({{ $lesson->id }},this)" class="btn btn-sm btn-outline">Mark Done</button>
      @endif
      @if($lesson->youtube_url)
        <a href="{{ $lesson->youtube_url }}" target="_blank" class="btn btn-sm btn-primary">▶ Watch</a>
      @elseif($lesson->meet_link)
        <a href="{{ $lesson->meet_link }}" target="_blank" class="btn btn-sm" style="background:#1a73e8;color:#fff">Meet</a>
      @elseif($lesson->zoom_link)
        <a href="{{ $lesson->zoom_link }}" target="_blank" class="btn btn-sm" style="background:#2D8CFF;color:#fff">Zoom</a>
      @endif
    </div>
  </div>
  @empty
  <div style="text-align:center;padding:40px;color:#9ca3af">কোনো class এখনো publish হয়নি।</div>
  @endforelse
  <div class="pagination">{{ $lessons->links() }}</div>
</div>
@push('scripts')
<script>
function markDone(id, btn) {
  fetch(`/student/learning/${id}/complete`, {
    method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'}
  }).then(r=>r.json()).then(()=>{
    btn.outerHTML = '<span class="badge badge-green">✓ Completed</span>';
  });
}
</script>
@endpush
@endsection
