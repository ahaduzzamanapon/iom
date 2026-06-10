@extends('layouts.admin')

@section('title', 'Responses — ' . $form->title)

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.forms.show', $form) }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> ফিরে যান
    </a>
    <h2 class="fw-bold d-inline ms-3">📊 Responses — {{ $form->title }}</h2>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Respondent</th>
                        <th>Email</th>
                        @foreach($form->fields as $field)
                            <th>{{ $field->label }}</th>
                        @endforeach
                        <th>Submitted At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($responses as $i => $response)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $response->respondent_name ?? $response->user?->name ?? 'Anonymous' }}</td>
                        <td>{{ $response->respondent_email ?? $response->user?->email ?? '—' }}</td>
                        @foreach($form->fields as $field)
                            <td>{{ $response->answers[$field->id] ?? '—' }}</td>
                        @endforeach
                        <td>{{ $response->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $form->fields->count() + 4 }}" class="text-center text-muted py-4">
                            কোনো response জমা হয়নি।
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">{{ $responses->links() }}</div>
@endsection
