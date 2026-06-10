@extends('layouts.admin')
@section('title','Settings')
@section('page-title','System Settings')

@section('content')
<div class="page-header">
  <div>
    <div class="page-title">System Settings</div>
    <div class="page-sub">Institute তথ্য ও সিস্টেম কনফিগারেশন</div>
  </div>
</div>

<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
  @csrf

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">

    {{-- Institute Info --}}
    <div class="card">
      <div class="card-header"><span class="card-title">🏫 Institute Information</span></div>
      <div class="card-body">
        <div class="form-group">
          <label class="form-label">Institute Name *</label>
          <input type="text" name="institute_name" class="form-control" value="{{ $settings['institute_name'] ?? '' }}" required>
        </div>
        <div class="form-group">
          <label class="form-label">Address</label>
          <textarea name="address" class="form-control" rows="2">{{ $settings['address'] ?? '' }}</textarea>
        </div>
        <div class="form-group">
          <label class="form-label">Phone</label>
          <input type="text" name="phone" class="form-control" value="{{ $settings['phone'] ?? '' }}">
        </div>
        <div class="form-group">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" value="{{ $settings['email'] ?? '' }}">
        </div>
        <div class="form-group">
          <label class="form-label">Website</label>
          <input type="url" name="website" class="form-control" value="{{ $settings['website'] ?? '' }}">
        </div>
        <div class="form-group">
          <label class="form-label">Logo (PNG/JPG, max 2MB)</label>
          @if(!empty($settings['logo']))
            <div style="margin-bottom:8px">
              <img src="{{ asset('uploads/logos/'.$settings['logo']) }}" alt="Logo" style="height:60px;border-radius:8px;border:1px solid #e2e8f0">
            </div>
          @endif
          <input type="file" name="logo" class="form-control" accept="image/*">
        </div>
      </div>
    </div>

    {{-- Academic Config --}}
    <div class="card">
      <div class="card-header"><span class="card-title">⚙️ Academic Configuration</span></div>
      <div class="card-body">
        <div class="form-group">
          <label class="form-label">Minimum Attendance % *</label>
          <input type="number" name="min_attendance" class="form-control" min="0" max="100" value="{{ $settings['min_attendance'] ?? 75 }}" required>
        </div>

        <div style="border-top:1px solid #f0f4f8;margin:16px 0;padding-top:16px">
          <div style="font-size:13px;font-weight:700;color:#1e293b;margin-bottom:4px">📊 Grading Scale — Minimum Marks Threshold (%)</div>
          <div style="font-size:11px;color:#64748b;margin-bottom:14px">Bangladesh UGC Standard | GPA Scale: 0.00 – 4.00</div>

          {{-- Grade reference table --}}
          <table style="width:100%;font-size:12px;border-collapse:collapse;margin-bottom:16px;background:#f8fafc;border-radius:8px;overflow:hidden">
            <thead>
              <tr style="background:#1e293b;color:#fff">
                <th style="padding:7px 10px;text-align:left">Grade</th>
                <th style="padding:7px 10px;text-align:center">GP</th>
                <th style="padding:7px 10px;text-align:center">Default %</th>
                <th style="padding:7px 10px;text-align:left">Custom Threshold</th>
              </tr>
            </thead>
            <tbody>
              @foreach([
                ['key'=>'gpa_a_plus',  'grade'=>'A+', 'gp'=>'4.00', 'default'=>80,  'color'=>'#16a34a'],
                ['key'=>'gpa_a',       'grade'=>'A',  'gp'=>'3.75', 'default'=>75,  'color'=>'#22c55e'],
                ['key'=>'gpa_a_minus', 'grade'=>'A-', 'gp'=>'3.50', 'default'=>70,  'color'=>'#4ade80'],
                ['key'=>'gpa_b_plus',  'grade'=>'B+', 'gp'=>'3.25', 'default'=>65,  'color'=>'#0ea5e9'],
                ['key'=>'gpa_b',       'grade'=>'B',  'gp'=>'3.00', 'default'=>60,  'color'=>'#38bdf8'],
                ['key'=>'gpa_b_minus', 'grade'=>'B-', 'gp'=>'2.75', 'default'=>55,  'color'=>'#7dd3fc'],
                ['key'=>'gpa_c_plus',  'grade'=>'C+', 'gp'=>'2.50', 'default'=>50,  'color'=>'#f59e0b'],
                ['key'=>'gpa_c',       'grade'=>'C',  'gp'=>'2.25', 'default'=>45,  'color'=>'#fbbf24'],
                ['key'=>'gpa_d',       'grade'=>'D',  'gp'=>'2.00', 'default'=>40,  'color'=>'#f97316'],
              ] as $row)
              <tr style="border-bottom:1px solid #e2e8f0">
                <td style="padding:6px 10px">
                  <span style="font-weight:700;color:{{ $row['color'] }}">{{ $row['grade'] }}</span>
                </td>
                <td style="padding:6px 10px;text-align:center;color:#374151">{{ $row['gp'] }}</td>
                <td style="padding:6px 10px;text-align:center;color:#64748b">≥ {{ $row['default'] }}%</td>
                <td style="padding:6px 10px">
                  <input type="number" name="{{ $row['key'] }}" class="form-control"
                    style="height:30px;font-size:12px;padding:4px 8px"
                    min="0" max="100" placeholder="{{ $row['default'] }}"
                    value="{{ $settings[$row['key']] ?? '' }}">
                </td>
              </tr>
              @endforeach
              <tr style="background:#fee2e2">
                <td style="padding:6px 10px"><span style="font-weight:700;color:#dc2626">F</span></td>
                <td style="padding:6px 10px;text-align:center;color:#374151">0.00</td>
                <td style="padding:6px 10px;text-align:center;color:#64748b">< D threshold</td>
                <td style="padding:6px 10px;color:#9ca3af;font-size:11px;font-style:italic">Automatic (below D)</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>

  <div style="margin-top:20px">
    <button type="submit" class="btn btn-primary">💾 Save Settings</button>
  </div>

</form>
@endsection
