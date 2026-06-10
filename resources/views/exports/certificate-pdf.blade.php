<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family: 'DejaVu Sans', sans-serif; color:#1a1a1a; background:#fff; }

    .cert-border {
      border: 8px solid #1a5276;
      padding: 40px;
      min-height: 550px;
      position: relative;
    }
    .cert-inner {
      border: 2px solid #d4af37;
      padding: 32px;
      text-align: center;
    }

    .institute-name { font-size:20px; font-weight:bold; color:#1a5276; letter-spacing:1px; }
    .institute-sub { font-size:11px; color:#555; margin-top:4px; }

    .cert-title { font-size:28px; font-weight:bold; color:#d4af37; margin:24px 0 8px; letter-spacing:2px; text-transform:uppercase; }
    .cert-sub { font-size:12px; color:#555; margin-bottom:24px; }

    .student-name { font-size:22px; font-weight:bold; color:#1a1a1a; border-bottom:2px solid #d4af37; display:inline-block; padding:0 40px 4px; margin:8px 0; }
    .course-name { font-size:15px; color:#1a5276; font-weight:bold; margin:12px 0 4px; }
    .body-text { font-size:12px; color:#444; line-height:1.8; margin:8px 0; }

    .cert-number { font-size:11px; color:#888; font-family:monospace; margin-top:20px; }

    .signatures { display:table; width:100%; margin-top:40px; }
    .sig-cell { display:table-cell; text-align:center; width:50%; }
    .sig-line { border-top:1px solid #333; width:140px; margin:0 auto 6px; }
    .sig-label { font-size:11px; color:#555; }

    .qr-wrap { position:absolute; bottom:50px; right:60px; text-align:center; }
    .qr-wrap img { width:70px; height:70px; }
    .qr-label { font-size:9px; color:#888; margin-top:4px; }

    .seal { position:absolute; bottom:50px; left:60px; width:70px; height:70px; border:3px solid #1a5276; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:8px; color:#1a5276; text-align:center; font-weight:bold; }
  </style>
</head>
<body>
<div class="cert-border">
  <div class="cert-inner">
    {{-- Institute Header --}}
    <div class="institute-name">{{ $settings['institute_name'] ?? 'Islamic Online Madrasah' }}</div>
    <div class="institute-sub">{{ $settings['address'] ?? '' }}</div>

    {{-- Title --}}
    <div class="cert-title">Certificate of Completion</div>
    <div class="cert-sub">This is to certify that</div>

    {{-- Student --}}
    <div class="student-name">{{ $certificate->student->name }}</div>
    <div class="body-text">has successfully completed the course</div>
    <div class="course-name">{{ $certificate->course->name }}</div>
    <div class="body-text">
      with distinction and is awarded this certificate<br>
      on <strong>{{ \Carbon\Carbon::parse($certificate->issued_date)->format('d F Y') }}</strong>
    </div>

    {{-- Certificate Number --}}
    <div class="cert-number">Certificate No: {{ $certificate->certificate_number }}</div>

    {{-- Signatures --}}
    <div class="signatures" style="margin-top:50px">
      <div class="sig-cell">
        <div class="sig-line"></div>
        <div class="sig-label">Principal / Director</div>
      </div>
      <div class="sig-cell">
        <div class="sig-line"></div>
        <div class="sig-label">Academic Coordinator</div>
      </div>
    </div>
  </div>

  {{-- QR Code --}}
  @if($certificate->qr_code)
  <div class="qr-wrap">
    <img src="{{ public_path('uploads/'.$certificate->qr_code) }}" alt="QR">
    <div class="qr-label">Scan to verify</div>
  </div>
  @endif

  <div class="seal">OFFICIAL<br>SEAL</div>
</div>
</body>
</html>
