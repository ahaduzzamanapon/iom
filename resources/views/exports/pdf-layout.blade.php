<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #333; }

        .header { display: table; width: 100%; border-bottom: 2px solid #1a5276; padding-bottom: 10px; margin-bottom: 12px; }
        .logo-cell { display: table-cell; width: 80px; vertical-align: middle; }
        .logo-cell img { width: 65px; height: 65px; object-fit: contain; }
        .info-cell { display: table-cell; vertical-align: middle; padding-left: 12px; }
        .info-cell h1 { font-size: 18px; color: #1a5276; margin-bottom: 3px; }
        .info-cell p { font-size: 11px; color: #555; line-height: 1.5; }
        .date-cell { display: table-cell; vertical-align: middle; text-align: right; font-size: 11px; color: #555; white-space: nowrap; }

        .report-title { background: #1a5276; color: #fff; padding: 6px 12px; font-size: 13px; font-weight: bold; margin-bottom: 4px; }
        .report-subtitle { font-size: 11px; color: #555; margin-bottom: 14px; padding: 4px 12px; background: #eaf0fb; }

        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        thead tr { background: #1a5276; color: #fff; }
        thead th { padding: 7px 8px; text-align: left; font-size: 11px; }
        tbody tr:nth-child(even) { background: #f2f6fc; }
        tbody td { padding: 6px 8px; font-size: 11px; border-bottom: 1px solid #dce6f1; }

        .footer { margin-top: 20px; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #ddd; padding-top: 6px; }
    </style>
</head>
<body>

{{-- ===== HEADER ===== --}}
<div class="header">
    <div class="logo-cell">
        @if(!empty($settings['logo']))
            <img src="{{ public_path('uploads/logos/' . $settings['logo']) }}" alt="Logo">
        @endif
    </div>
    <div class="info-cell">
        <h1>{{ $settings['institute_name'] ?? 'Islamic Online Madrasah' }}</h1>
        <p>{{ $settings['address'] ?? '' }}</p>
        <p>{{ $settings['phone'] ?? '' }}
            @if(!empty($settings['email'])) &nbsp;|&nbsp; {{ $settings['email'] }} @endif
        </p>
    </div>
    <div class="date-cell">
        <strong>Date:</strong> {{ $export_date ?? now()->format('d M Y') }}
    </div>
</div>

{{-- ===== REPORT TITLE ===== --}}
<div class="report-title">{{ $title ?? 'Report' }}</div>
@isset($subtitle)
<div class="report-subtitle">{{ $subtitle }}</div>
@endisset

{{-- ===== CONTENT (injected by each report blade) ===== --}}
@yield('content')

{{-- ===== FOOTER ===== --}}
<div class="footer">
    {{ $settings['institute_name'] ?? 'IOM' }} &mdash; Generated on {{ now()->format('d M Y, h:i A') }}
</div>

</body>
</html>
