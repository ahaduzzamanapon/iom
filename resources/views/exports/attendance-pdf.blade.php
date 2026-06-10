@extends('exports.pdf-layout')
@section('content')
<table>
  <thead>
    <tr>
      <th>#</th>
      <th>Student</th>
      <th>Batch</th>
      <th>Class / Lesson</th>
      <th>Date</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
    @forelse($records as $i => $r)
    <tr>
      <td>{{ $i + 1 }}</td>
      <td>{{ $r->student->name ?? '—' }}</td>
      <td>{{ $r->batch->name ?? '—' }}</td>
      <td>{{ $r->classLesson->title ?? '—' }}</td>
      <td>{{ $r->date ? \Carbon\Carbon::parse($r->date)->format('d M Y') : '—' }}</td>
      <td style="text-transform:capitalize;font-weight:bold">{{ $r->status }}</td>
    </tr>
    @empty
    <tr><td colspan="6" style="text-align:center;padding:16px">No records found.</td></tr>
    @endforelse
  </tbody>
</table>
<div style="margin-top:12px;font-size:11px;color:#555">Total: {{ $records->count() }} records</div>
@endsection
