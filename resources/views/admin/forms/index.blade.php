@extends('layouts.admin')

@section('title', 'Form Builder')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0">📋 Custom Forms</h2>
    <a href="{{ route('admin.forms.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> নতুন Form
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Form Title</th>
                    <th>Created By</th>
                    <th>Responses</th>
                    <th>Status</th>
                    <th>Closes At</th>
                    <th>Share Link</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($forms as $form)
                <tr>
                    <td><strong>{{ $form->title }}</strong></td>
                    <td>{{ $form->creator?->name ?? 'Admin' }}</td>
                    <td><span class="badge bg-primary">{{ $form->responses_count }}</span></td>
                    <td>
                        <span class="badge bg-{{ $form->is_active ? 'success' : 'secondary' }}">
                            {{ $form->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>{{ $form->closes_at?->format('d M Y') ?? 'কোনো সীমা নেই' }}</td>
                    <td>
                        <a href="{{ url('/forms/' . $form->slug) }}" target="_blank" class="btn btn-sm btn-outline-info">
                            <i class="bi bi-link-45deg"></i> Link
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('admin.forms.show', $form) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i>
                        </a>
                        <form action="{{ route('admin.forms.toggle', $form) }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-sm btn-outline-{{ $form->is_active ? 'warning' : 'success' }}">
                                <i class="bi bi-toggle-{{ $form->is_active ? 'on' : 'off' }}"></i>
                            </button>
                        </form>
                        <form action="{{ route('admin.forms.destroy', $form) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Form মুছে ফেলবেন?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">কোনো form নেই।</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $forms->links() }}</div>
@endsection
