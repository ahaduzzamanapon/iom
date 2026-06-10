@extends('layouts.admin')

@section('title', 'নতুন Form তৈরি')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.forms.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> ফিরে যান
    </a>
    <h2 class="fw-bold d-inline ms-3">📋 নতুন Form Builder</h2>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.forms.store') }}" method="POST" id="formBuilder">
            @csrf

            <div class="row g-3 mb-4">
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Form Title *</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. ভর্তি সম্পর্কিত মতামত ফর্ম" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Closes At</label>
                    <input type="date" name="closes_at" class="form-control">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description" class="form-control" rows="2" placeholder="Form-এর বিবরণ..."></textarea>
                </div>
            </div>

            <hr>
            <h5 class="fw-bold mb-3">📌 Form Fields</h5>

            <div id="fieldsContainer"></div>

            <button type="button" class="btn btn-outline-primary mb-4" onclick="addField()">
                <i class="bi bi-plus-lg me-1"></i> Field যোগ করুন
            </button>

            <hr>
            <button type="submit" class="btn btn-primary px-5">
                <i class="bi bi-save me-1"></i> Form Save করুন
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
let fieldCount = 0;

function addField() {
    fieldCount++;
    const idx = fieldCount - 1;
    const html = `
    <div class="card mb-3 border" id="field_${fieldCount}">
        <div class="card-body">
            <div class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small fw-semibold">Label *</label>
                    <input type="text" name="fields[${idx}][label]" class="form-control form-control-sm" placeholder="Field Label" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Type *</label>
                    <select name="fields[${idx}][type]" class="form-select form-select-sm" onchange="toggleOptions(this, ${fieldCount})">
                        <option value="text">Text</option>
                        <option value="textarea">Textarea</option>
                        <option value="select">Dropdown (Select)</option>
                        <option value="radio">Radio</option>
                        <option value="checkbox">Checkbox</option>
                        <option value="file">File Upload</option>
                        <option value="date">Date</option>
                        <option value="number">Number</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="form-check mt-3">
                        <input type="checkbox" name="fields[${idx}][required]" class="form-check-input" id="req_${fieldCount}" value="1">
                        <label class="form-check-label small" for="req_${fieldCount}">Required</label>
                    </div>
                </div>
                <div class="col-md-2 text-end">
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeField(${fieldCount})">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="col-12 options-field" id="options_${fieldCount}" style="display:none">
                    <label class="form-label small fw-semibold">Options (comma separated)</label>
                    <input type="text" name="fields[${idx}][options]" class="form-control form-control-sm" placeholder="Option 1, Option 2, Option 3">
                </div>
            </div>
        </div>
    </div>`;
    document.getElementById('fieldsContainer').insertAdjacentHTML('beforeend', html);
}

function removeField(id) {
    document.getElementById('field_' + id)?.remove();
}

function toggleOptions(select, id) {
    const optionTypes = ['select', 'radio', 'checkbox'];
    const optDiv = document.getElementById('options_' + id);
    if (optDiv) {
        optDiv.style.display = optionTypes.includes(select.value) ? 'block' : 'none';
    }
}

// Add one field by default
addField();
</script>
@endpush
@endsection
