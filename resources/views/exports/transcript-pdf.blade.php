@extends('exports.pdf-layout')

@section('content')
<table>
    <thead>
        <tr>
            <th>Semester</th>
            <th>Subject</th>
            <th>Exam Type</th>
            <th>Marks Obtained</th>
            <th>Total Marks</th>
            <th>%</th>
            <th>Grade</th>
            <th>GPA</th>
        </tr>
    </thead>
    <tbody>
        @forelse($attempts as $att)
        <tr>
            <td>{{ $att->exam->semester->name ?? '—' }}</td>
            <td>{{ $att->exam->subject->name ?? '—' }}</td>
            <td>{{ ucfirst(str_replace('_',' ',$att->exam->type)) }}</td>
            <td>{{ $att->obtained_marks ?? '—' }}</td>
            <td>{{ $att->exam->total_marks }}</td>
            <td>{{ $att->percentage ? number_format($att->percentage,1).'%' : '—' }}</td>
            <td><strong>{{ $att->grade ?? '—' }}</strong></td>
            <td>{{ $att->gpa ?? '—' }}</td>
        </tr>
        @empty
        <tr><td colspan="8" style="text-align:center;padding:20px;color:#999">কোনো পরীক্ষার তথ্য নেই।</td></tr>
        @endforelse
    </tbody>
</table>

<div style="margin-top:30px;border-top:2px solid #1a5276;padding-top:16px">
    @foreach($semesterResults as $sr)
    <div style="display:table;width:100%;margin-bottom:6px;font-size:11px">
        <span style="display:table-cell;font-weight:bold">{{ $sr->semester->name ?? '—' }}</span>
        <span style="display:table-cell;text-align:center">CGPA: <strong>{{ $sr->cgpa ?? '—' }}</strong></span>
        <span style="display:table-cell;text-align:right">Grade: <strong>{{ $sr->overall_grade ?? '—' }}</strong></span>
        <span style="display:table-cell;text-align:right">
            <span style="padding:2px 8px;border-radius:4px;{{ $sr->is_published ? 'background:#f0fdf4;color:#16a34a' : 'background:#fef9c3;color:#ca8a04' }}">
                {{ $sr->is_published ? 'Published' : 'Pending' }}
            </span>
        </span>
    </div>
    @endforeach
</div>
@endsection
