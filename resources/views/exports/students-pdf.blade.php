@extends('exports.pdf-layout')
@section('content')
<table>
  <thead>
    <tr>
      <th>#</th>
      <th>Student ID</th>
      <th>Name</th>
      <th>Email</th>
      <th>Phone</th>
      <th>Batch / Course</th>
      <th>Status</th>
      <th>Joined</th>
    </tr>
  </thead>
  <tbody>
    @forelse($students as $i => $s)
    <tr>
      <td>{{ $i + 1 }}</td>
      <td style="font-family:monospace">{{ $s->student_id }}</td>
      <td>{{ $s->user->name ?? '—' }}</td>
      <td>{{ $s->user->email ?? '—' }}</td>
      <td>{{ $s->phone ?? '—' }}</td>
      <td>
        @php $b = $s->batches->first(); @endphp
        {{ $b?->batch?->name ?? '—' }}<br>
        <span style="color:#555;font-size:10px">{{ $b?->batch?->course?->name ?? '' }}</span>
      </td>
      <td>{{ ucfirst($s->status ?? 'active') }}</td>
      <td>{{ $s->created_at?->format('d M Y') ?? '—' }}</td>
    </tr>
    @empty
    <tr><td colspan="8" style="text-align:center;padding:16px">No students found.</td></tr>
    @endforelse
  </tbody>
</table>
<div style="margin-top:12px;font-size:11px;color:#555">Total: {{ $students->count() }} students</div>
@endsection
