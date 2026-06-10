@extends('layouts.student')
@section('title','My Results')
@section('page-title','My Results')
@section('content')
<div class="page-header"><div class="page-title">Results</div></div>
<div class="card">
  <div class="card-body" style="padding:0">
    <table class="dt-table">
      <thead><tr><th>Semester</th><th>Batch</th><th>CGPA</th><th>Grade</th><th>Status</th></tr></thead>
      <tbody>
        @forelse(auth()->user()->hasMany(\App\Models\Result::class,'student_id')->where('is_published',true)->with(['semester','batch'])->get() as $r)
        <tr>
          <td>{{ $r->semester->name ?? '—' }}</td>
          <td>{{ $r->batch->name ?? '—' }}</td>
          <td><strong>{{ $r->cgpa ?? '—' }}</strong></td>
          <td><span class="badge badge-blue">{{ $r->overall_grade ?? '—' }}</span></td>
          <td><span class="badge badge-green">Published</span></td>
        </tr>
        @empty
        <tr><td colspan="5" style="text-align:center;padding:30px;color:#9ca3af">কোনো result publish হয়নি।</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
