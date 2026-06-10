@extends('layouts.admin')
@section('title', 'Survey Management')
@section('page-title', 'Survey Management')

@section('content')
<div class="page-header">
  <div>
    <div class="page-title">📋 Surveys</div>
    <div class="page-sub">গাছ-ভিত্তিক ডায়াগ্রাম সহ কন্ডিশনাল সার্ভে পরিচালনা করুন</div>
  </div>
  <a href="{{ route('admin.surveys.create') }}" class="btn btn-primary">+ Create Survey</a>
</div>

<div class="card">
  <div class="card-body" style="padding:0">
    <div class="dt-table-wrap">
      <table class="dt-table">
      <thead>
        <tr>
          <th>Survey Title</th>
          <th>Created By</th>
          <th>Closes At</th>
          <th>Responses</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($surveys as $s)
        <tr>
          <td>
            <strong>{{ $s->title }}</strong><br>
            <small style="color:#718096">Slug: {{ $s->slug }}</small>
          </td>
          <td>{{ $s->creator->name ?? '—' }}</td>
          <td>{{ $s->closes_at ? $s->closes_at->format('d M Y') : 'Never' }}</td>
          <td>
            <a href="{{ route('admin.surveys.responses', $s) }}" style="font-weight:700;color:#1a3a5c;text-decoration:underline">
              {{ $s->responses_count }}
            </a>
          </td>
          <td>
            <span class="badge {{ $s->is_active ? 'badge-green' : 'badge-red' }}">
              {{ $s->is_active ? 'Active' : 'Inactive' }}
            </span>
          </td>
          <td style="white-space:nowrap">
            <a href="{{ route('admin.surveys.show', $s) }}" class="btn btn-sm btn-outline">View</a>
            <a href="{{ route('admin.surveys.edit', $s) }}" class="btn btn-sm btn-outline">Edit</a>
            <form method="POST" action="{{ route('admin.surveys.toggle', $s) }}" style="display:inline">
              @csrf
              <button class="btn btn-sm {{ $s->is_active ? 'btn-outline' : 'btn-success' }}">
                {{ $s->is_active ? 'Disable' : 'Enable' }}
              </button>
            </form>
            <form method="POST" action="{{ route('admin.surveys.destroy', $s) }}" style="display:inline" onsubmit="return confirm('Survey মুছে ফেলতে চান?')">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-danger">Delete</button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" style="text-align:center;padding:30px;color:#9ca3af">কোনো Survey তৈরি করা হয়নি।</td>
        </tr>
        @endforelse
      </tbody>
    </table>
    </div>
  </div>
</div>
<div class="pagination">{{ $surveys->links() }}</div>
@endsection
