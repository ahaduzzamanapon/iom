@extends('exports.pdf-layout')
@section('content')
<table>
  <thead>
    <tr>
      <th>#</th>
      <th>Student</th>
      <th>Batch</th>
      <th>Semester</th>
      <th>Total Marks</th>
      <th>Obtained</th>
      <th>Grade</th>
      <th>Status</th>
      <th>Published</th>
    </tr>
  </thead>
  <tbody>
    @forelse($results as $i => $r)
    <tr>
      <td>{{ $i + 1 }}</td>
      <td>{{ $r->student->name ?? '—' }}</td>
      <td>{{ $r->batch->name ?? '—' }}</td>
      <td>{{ $r->semester->name ?? '—' }}</td>
      <td>{{ $r->total_marks ?? '—' }}</td>
      <td>{{ $r->obtained_marks ?? '—' }}</td>
      <td style="font-weight:bold">{{ $r->grade ?? '—' }}</td>
      <td>{{ ucfirst($r->status ?? '—') }}</td>
      <td>{{ $r->is_published ? 'Yes' : 'No' }}</td>
    </tr>
    @empty
    <tr><td colspan="9" style="text-align:center;padding:16px">No results found.</td></tr>
    @endforelse
  </tbody>
</table>
<div style="margin-top:12px;font-size:11px;color:#555">Total: {{ $results->count() }} records</div>
@endsection
