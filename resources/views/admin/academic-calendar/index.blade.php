@extends('layouts.admin')
@section('title','Academic Calendar')
@section('page-title','Academic Calendar')
@section('content')
<div class="page-header">
  <div>
    <div class="page-title">Academic Calendar</div>
    <div class="page-sub">সকল একাডেমিক ইভেন্ট, ছুটি ও পরীক্ষার সময়সূচি</div>
  </div>
  <a href="{{ route('admin.academic-calendars.create') }}" class="btn btn-primary">+ নতুন Event</a>
</div>

{{-- Filters --}}
<div class="card" style="margin-bottom:16px">
  <div class="card-body">
    <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end">
      <div class="form-group" style="margin:0;min-width:160px">
        <label class="form-label">Type</label>
        <select name="type" class="form-control">
          <option value="">সব Type</option>
          @foreach(['holiday'=>'🏖️ Holiday','exam'=>'📝 Exam','event'=>'🎉 Event','class_suspension'=>'⏸️ Class Suspension','other'=>'📌 Other'] as $val=>$lbl)
            <option value="{{ $val }}" {{ request('type')==$val?'selected':'' }}>{{ $lbl }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group" style="margin:0;min-width:160px">
        <label class="form-label">Course</label>
        <select name="course_id" class="form-control">
          <option value="">সব Course</option>
          @foreach($courses as $c)
            <option value="{{ $c->id }}" {{ request('course_id')==$c->id?'selected':'' }}>{{ $c->name }}</option>
          @endforeach
        </select>
      </div>
      <button class="btn btn-primary">Filter</button>
      <a href="{{ route('admin.academic-calendars.index') }}" class="btn btn-outline">Reset</a>
    </form>
  </div>
</div>

{{-- Calendar Events Table --}}
<div class="card">
  <div class="card-header">
    <span class="card-title">📅 Events ({{ $events->total() }})</span>
  </div>
  <div class="dt-wrapper">
    <div class="dt-table-wrap">
      <table class="dt-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Title</th>
            <th>Type</th>
            <th>Date / Range</th>
            <th>Course</th>
            <th>Semester</th>
            <th>Published</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($events as $e)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>
              <strong>{{ $e->title }}</strong>
              @if($e->description)
                <div style="font-size:11px;color:#718096;margin-top:2px">{{ Str::limit($e->description, 60) }}</div>
              @endif
            </td>
            <td>
              @php $colors = \App\Models\AcademicCalendar::typeColors(); @endphp
              <span class="badge {{ $colors[$e->type] ?? 'badge-gray' }}">{{ ucwords(str_replace('_',' ',$e->type)) }}</span>
            </td>
            <td style="white-space:nowrap">
              {{ $e->start_date->format('d M Y') }}
              @if($e->end_date && !$e->start_date->eq($e->end_date))
                <br><span style="color:#718096;font-size:11px">to {{ $e->end_date->format('d M Y') }}</span>
              @endif
            </td>
            <td>{{ $e->course->name ?? '<span style="color:#aaa">Universal</span>' }}</td>
            <td>{{ $e->semester->name ?? '—' }}</td>
            <td>
              @if($e->is_published)
                <span class="badge badge-green">✓ Published</span>
              @else
                <span class="badge badge-gray">Draft</span>
              @endif
            </td>
            <td style="display:flex;gap:6px">
              <a href="{{ route('admin.academic-calendars.edit', $e) }}" class="btn btn-sm btn-outline">Edit</a>
              <form method="POST" action="{{ route('admin.academic-calendars.destroy', $e) }}">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Del</button>
              </form>
            </td>
          </tr>
          @empty
          <tr><td colspan="8" style="text-align:center;padding:30px;color:#9ca3af">কোনো event নেই। নতুন event যোগ করুন।</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
  <div class="pagination">{{ $events->links() }}</div>
</div>
@endsection
