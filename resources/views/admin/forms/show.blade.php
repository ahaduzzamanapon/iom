@extends('layouts.admin')

@section('title', $form->title)

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.forms.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> ফিরে যান
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold">{{ $form->title }}</h5>
                <p class="text-muted small">{{ $form->description }}</p>
                <hr>
                <p><strong>Status:</strong>
                    <span class="badge bg-{{ $form->is_active ? 'success' : 'secondary' }}">
                        {{ $form->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </p>
                <p><strong>Responses:</strong> {{ $form->responses->count() }}</p>
                <p><strong>Closes:</strong> {{ $form->closes_at?->format('d M Y') ?? 'No limit' }}</p>
                <hr>
                <p class="fw-semibold small">📎 Share Link:</p>
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" id="shareLink" value="{{ $shareLink }}" readonly>
                    <button class="btn btn-outline-secondary" onclick="navigator.clipboard.writeText('{{ $shareLink }}')">
                        <i class="bi bi-clipboard"></i>
                    </button>
                </div>
                <div class="mt-3 d-flex gap-2">
                    <a href="{{ route('admin.forms.responses', $form) }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-table me-1"></i> Responses
                    </a>
                    <form action="{{ route('admin.forms.toggle', $form) }}" method="POST">
                        @csrf
                        <button class="btn btn-{{ $form->is_active ? 'warning' : 'success' }} btn-sm">
                            {{ $form->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header fw-semibold">Form Fields ({{ $form->fields->count() }})</div>
            <div class="list-group list-group-flush">
                @forelse($form->fields as $i => $field)
                <div class="list-group-item">
                    <div class="d-flex justify-content-between">
                        <div>
                            <span class="badge bg-secondary me-2">{{ $i + 1 }}</span>
                            <strong>{{ $field->label }}</strong>
                            <span class="badge bg-light text-dark border ms-2">{{ $field->type }}</span>
                            @if($field->required)
                                <span class="badge bg-danger ms-1">Required</span>
                            @endif
                            @if($field->options)
                                <div class="small text-muted mt-1">Options: {{ implode(', ', $field->options) }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="list-group-item text-center text-muted py-3">কোনো field নেই।</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
