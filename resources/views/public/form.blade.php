<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $form->title }} — IOM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5" style="max-width:700px">

    <div class="text-center mb-4">
        <h3 class="fw-bold">🕌 Islamic Online Madrasah</h3>
        <hr>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">{{ $form->title }}</h5>
            @if($form->description)
                <p class="mb-0 small mt-1 opacity-75">{{ $form->description }}</p>
            @endif
        </div>
        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('forms.public.submit', $form->slug) }}" method="POST" enctype="multipart/form-data">
                @csrf

                @if(!auth()->check())
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">আপনার নাম</label>
                        <input type="text" name="respondent_name" class="form-control" placeholder="নাম লিখুন">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">ইমেইল</label>
                        <input type="email" name="respondent_email" class="form-control" placeholder="ইমেইল লিখুন">
                    </div>
                </div>
                <hr>
                @endif

                @foreach($form->fields as $field)
                <div class="mb-4">
                    <label class="form-label fw-semibold">
                        {{ $field->label }}
                        @if($field->required) <span class="text-danger">*</span> @endif
                    </label>

                    @if($field->type === 'text')
                        <input type="text" name="field_{{ $field->id }}" class="form-control"
                               {{ $field->required ? 'required' : '' }}>

                    @elseif($field->type === 'number')
                        <input type="number" name="field_{{ $field->id }}" class="form-control"
                               {{ $field->required ? 'required' : '' }}>

                    @elseif($field->type === 'date')
                        <input type="date" name="field_{{ $field->id }}" class="form-control"
                               {{ $field->required ? 'required' : '' }}>

                    @elseif($field->type === 'textarea')
                        <textarea name="field_{{ $field->id }}" class="form-control" rows="3"
                                  {{ $field->required ? 'required' : '' }}></textarea>

                    @elseif($field->type === 'select')
                        <select name="field_{{ $field->id }}" class="form-select" {{ $field->required ? 'required' : '' }}>
                            <option value="">— বেছে নিন —</option>
                            @foreach($field->options ?? [] as $opt)
                                <option>{{ trim($opt) }}</option>
                            @endforeach
                        </select>

                    @elseif($field->type === 'radio')
                        @foreach($field->options ?? [] as $opt)
                        <div class="form-check">
                            <input type="radio" name="field_{{ $field->id }}" value="{{ trim($opt) }}"
                                   class="form-check-input" {{ $field->required ? 'required' : '' }}>
                            <label class="form-check-label">{{ trim($opt) }}</label>
                        </div>
                        @endforeach

                    @elseif($field->type === 'checkbox')
                        @foreach($field->options ?? [] as $opt)
                        <div class="form-check">
                            <input type="checkbox" name="field_{{ $field->id }}[]" value="{{ trim($opt) }}"
                                   class="form-check-input">
                            <label class="form-check-label">{{ trim($opt) }}</label>
                        </div>
                        @endforeach

                    @elseif($field->type === 'file')
                        <input type="file" name="field_{{ $field->id }}" class="form-control"
                               {{ $field->required ? 'required' : '' }}>
                    @endif

                    @error("field_{$field->id}")
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                @endforeach

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary px-5">
                        <i class="bi bi-send me-1"></i> জমা দিন
                    </button>
                </div>
            </form>
        </div>
    </div>

    <p class="text-center text-muted small mt-4">© Islamic Online Madrasah (IOM)</p>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
