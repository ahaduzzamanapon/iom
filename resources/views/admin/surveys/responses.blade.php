@extends('layouts.admin')
@section('title', 'Survey Responses — ' . $survey->title)
@section('page-title', 'Survey Responses')

@section('content')
<div class="page-header">
  <div>
    <div class="page-title">📋 Survey Responses: {{ $survey->title }}</div>
    <div class="page-sub">সংগৃহীত সকল উত্তরের তালিকা</div>
  </div>
  <div style="display:flex;gap:8px">
    <a href="{{ route('admin.surveys.show', $survey) }}" class="btn btn-outline">← Back Details</a>
    <a href="{{ route('admin.surveys.index') }}" class="btn btn-outline">Surveys List</a>
  </div>
</div>

<div class="card">
  <div class="card-body" style="padding:0">
    <div class="dt-table-wrap">
      <table class="dt-table">
      <thead>
        <tr>
          <th style="min-width:180px">Respondent</th>
          @foreach($survey->questions as $q)
            <th style="min-width:140px">{{ $q->label }}</th>
          @endforeach
          <th style="min-width:150px">Submitted At</th>
        </tr>
      </thead>
      <tbody>
        @forelse($responses as $r)
        <tr>
          <td>
            @if($r->user)
              <strong>{{ $r->user->name }}</strong><br>
              <small style="color:#718096">ID: {{ $r->user->studentProfile->student_id ?? $r->user->email }}</small>
            @else
              <strong>{{ $r->respondent_name ?? 'Anonymous' }}</strong><br>
              <small style="color:#718096">{{ $r->respondent_email ?? '—' }}</small>
            @endif
          </td>
          @foreach($survey->questions as $q)
            @php
              $ans = $r->answers[$q->question_key] ?? null;
            @endphp
            <td>
              @if(is_array($ans))
                {{ implode(', ', $ans) }}
              @elseif(!empty($ans))
                {{ $ans }}
              @else
                <span style="color:#cbd5e1">—</span>
              @endif
            </td>
          @endforeach
          <td>{{ $r->created_at->format('d M Y, h:i A') }}</td>
        </tr>
        @empty
        <tr>
          <td colspan="{{ $survey->questions->count() + 2 }}" style="text-align:center;padding:30px;color:#9ca3af">
            কোনো response খুঁজে পাওয়া যায়নি।
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
    </div>
  </div>
</div>

<div class="pagination">{{ $responses->links() }}</div>
@endsection
