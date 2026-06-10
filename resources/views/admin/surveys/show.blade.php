@extends('layouts.admin')
@section('title', 'Survey Details — ' . $survey->title)
@section('page-title', 'Survey Details')

@push('styles')
<style>
.survey-dashboard { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
@media (max-width: 900px) {
  .survey-dashboard { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')
<div class="page-header">
  <div>
    <div class="page-title">📋 Survey: {{ $survey->title }}</div>
    <div class="page-sub">সার্ভের ফ্লোচার্ট ডায়াগ্রাম এবং রেসপন্সসমূহ</div>
  </div>
  <div style="display:flex;gap:8px">
    <a href="{{ route('admin.surveys.edit', $survey) }}" class="btn btn-outline">Edit</a>
    <a href="{{ route('admin.surveys.index') }}" class="btn btn-outline">Back to List</a>
  </div>
</div>

{{-- Share link alert --}}
@if($survey->is_active)
<div class="alert alert-success" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px">
  <div>
    <strong>🔗 Survey Live Share Link:</strong> 
    <a href="{{ $shareLink }}" target="_blank" style="color:#0f5132;text-decoration:underline">{{ $shareLink }}</a>
  </div>
  <button onclick="navigator.clipboard.writeText('{{ $shareLink }}'); alert('লিঙ্কটি কপি করা হয়েছে।')" class="btn btn-sm btn-success">Copy Link</button>
</div>
@endif

<div class="survey-dashboard" style="margin-bottom:24px">
  {{-- Card 1: Visual Diagram --}}
  <div class="card">
    <div class="card-header">
      <span class="card-title">📊 Tree Branching Flowchart</span>
    </div>
    <div class="card-body" style="background:#f8fafc; padding:0; position:relative; overflow:hidden;">
      <div style="position: absolute; right: 12px; top: 12px; display: flex; flex-direction: column; gap: 6px; z-index: 10;">
        <button type="button" id="zoom-in" class="btn btn-sm btn-outline" style="padding: 6px 10px; background: white; border: 1px solid #cbd5e1; border-radius: 4px; font-weight: bold; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 1px 3px rgba(0,0,0,0.1); width: 32px; height: 32px;" title="Zoom In">+</button>
        <button type="button" id="zoom-out" class="btn btn-sm btn-outline" style="padding: 6px 10px; background: white; border: 1px solid #cbd5e1; border-radius: 4px; font-weight: bold; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 1px 3px rgba(0,0,0,0.1); width: 32px; height: 32px;" title="Zoom Out">-</button>
        <button type="button" id="zoom-reset" class="btn btn-sm btn-outline" style="padding: 4px 8px; background: white; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 11px; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 1px 3px rgba(0,0,0,0.1); height: 32px;" title="Reset Zoom">Reset</button>
      </div>
      <div id="mermaidSVGContainer" style="width: 100%; height: 450px; cursor: grab;">
        <span style="display:block;text-align:center;padding:50px;color:#94a3b8">ডায়াগ্রাম লোড হচ্ছে...</span>
      </div>
    </div>
  </div>

  {{-- Card 2: Stats & Details --}}
  <div>
    <div class="card" style="margin-bottom:20px">
      <div class="card-header">
        <span class="card-title">⚙️ Survey Information</span>
      </div>
      <div class="card-body">
        <table class="dt-table" style="width:100%">
          <tbody>
            <tr>
              <td style="font-weight:600;width:150px">Closes At</td>
              <td>{{ $survey->closes_at ? $survey->closes_at->format('d M Y, h:i A') : 'Never' }}</td>
            </tr>
            <tr>
              <td style="font-weight:600">Total Responses</td>
              <td>
                <a href="{{ route('admin.surveys.responses', $survey) }}" style="font-weight:700;color:#1a3a5c;text-decoration:underline">
                  {{ $survey->responses->count() }} Responses
                </a>
              </td>
            </tr>
            <tr>
              <td style="font-weight:600">Status</td>
              <td>
                <span class="badge {{ $survey->is_active ? 'badge-green' : 'badge-red' }}">
                  {{ $survey->is_active ? 'Active' : 'Inactive' }}
                </span>
              </td>
            </tr>
            <tr>
              <td style="font-weight:600">Description</td>
              <td>{{ $survey->description ?? 'No description provided.' }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <span class="card-title">👥 Recent Responses</span>
        <a href="{{ route('admin.surveys.responses', $survey) }}" class="btn btn-sm btn-outline">View All</a>
      </div>
      <div class="card-body" style="padding:0">
        <div class="dt-table-wrap">
          <table class="dt-table">
          <thead>
            <tr>
              <th>Respondent</th>
              <th>Submitted At</th>
            </tr>
          </thead>
          <tbody>
            @forelse($survey->responses()->take(5)->latest()->get() as $r)
            <tr>
              <td>
                @if($r->user)
                  <strong>{{ $r->user->name }}</strong><br>
                  <small style="color:#718096">ID: {{ $r->user->studentProfile->student_id ?? $r->user->email }}</small>
                @else
                  <strong>{{ $r->respondent_name ?? 'Anonymous' }}</strong><br>
                  <small style="color:#718096">{{ $r->respondent_email ?? '—' }}</small>
                @endif
              </td>
              <td>{{ $r->created_at->format('d M Y, h:i A') }}</td>
            </tr>
            @empty
            <tr>
              <td colspan="2" style="text-align:center;padding:20px;color:#9ca3af">কোনো response জমা হয়নি।</td>
            </tr>
            @endforelse
          </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/mermaid/dist/mermaid.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/svg-pan-zoom@3.6.1/dist/svg-pan-zoom.min.js"></script>
<script>
mermaid.initialize({
    startOnLoad: false,
    securityLevel: 'loose',
    theme: 'neutral',
    flowchart: { useMaxWidth: true, htmlLabels: true }
});

const questions = @json($survey->questions);
const optionTypes = ['select', 'radio'];

function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
}

function renderSurveyDiagram() {
    if (!questions || questions.length === 0) {
        document.getElementById('mermaidSVGContainer').innerHTML = '<span style="display:block;text-align:center;padding:50px;color:#94a3b8">কোনো প্রশ্ন যুক্ত করা হয়নি।</span>';
        return;
    }

    let code = "graph TD\n";
    code += "classDef startNode fill:#ecfdf5,stroke:#059669,stroke-width:2px,color:#065f46;\n";
    code += "classDef normalNode fill:#eff6ff,stroke:#2563eb,stroke-width:1px,color:#1e40af;\n";
    code += "classDef endNode fill:#fef2f2,stroke:#dc2626,stroke-width:2px,color:#991b1b;\n";

    // Nodes
    questions.forEach((f) => {
        const cleanKey = f.question_key.toLowerCase();
        const label = f.label;
        const type = f.type;
        const shape = `["${cleanKey}<br/><small style='opacity:0.75'>(${type})</small><br/><b>${escapeHtml(label.substring(0, 30))}${label.length > 30 ? '...' : ''}</b>"]`;
        code += `  ${cleanKey}${shape}\n`;
        if (f.is_start) {
            code += `  class ${cleanKey} startNode;\n`;
        } else {
            code += `  class ${cleanKey} normalNode;\n`;
        }
    });

    // Edges
    questions.forEach((f) => {
        const cleanKey = f.question_key.toLowerCase();
        const defaultNext = f.default_next_question_key ? f.default_next_question_key.toLowerCase() : '';
        if (optionTypes.includes(f.type) && Array.isArray(f.options) && f.options.length > 0) {
            f.options.forEach((opt) => {
                let target = opt.next_question_key ? opt.next_question_key.toLowerCase() : (defaultNext || 'endNode');
                if (target === 'end') target = 'endNode';
                code += `  ${cleanKey} -- "${escapeHtml(opt.value)}" --> ${target}\n`;
            });
        } else {
            let target = defaultNext || 'endNode';
            if (target === 'end') target = 'endNode';
            code += `  ${cleanKey} --> ${target}\n`;
        }
    });

    code += "  endNode[End of Survey]\n";
    code += "  class endNode endNode;\n";

    try {
        mermaid.render('mermaid-svg-detail', code).then(({ svg }) => {
            const container = document.getElementById('mermaidSVGContainer');
            container.innerHTML = svg;
            
            const svgElement = container.querySelector('svg');
            if (svgElement) {
                svgElement.style.width = '100%';
                svgElement.style.height = '100%';
                svgElement.style.maxWidth = 'none';
                
                const panZoom = svgPanZoom(svgElement, {
                    zoomEnabled: true,
                    controlIconsEnabled: false,
                    fit: true,
                    center: true,
                    minZoom: 0.1,
                    maxZoom: 10
                });
                
                document.getElementById('zoom-in').addEventListener('click', function() {
                    panZoom.zoomIn();
                });
                
                document.getElementById('zoom-out').addEventListener('click', function() {
                    panZoom.zoomOut();
                });
                
                document.getElementById('zoom-reset').addEventListener('click', function() {
                    panZoom.reset();
                    panZoom.fit();
                    panZoom.center();
                });
                
                // Allow mouse drag cursor change
                container.addEventListener('mousedown', function() {
                    container.style.cursor = 'grabbing';
                });
                container.addEventListener('mouseup', function() {
                    container.style.cursor = 'grab';
                });
            }
        }).catch(err => {
            console.error(err);
            document.getElementById('mermaidSVGContainer').innerHTML = '<span style="display:block;text-align:center;padding:50px;color:#ef4444">ডায়াগ্রাম রেন্ডার করতে ত্রুটি হয়েছে।</span>';
        });
    } catch (e) {
        console.error(e);
        document.getElementById('mermaidSVGContainer').innerHTML = '<span style="display:block;text-align:center;padding:50px;color:#ef4444">ডায়াগ্রাম রেন্ডার করতে ত্রুটি হয়েছে।</span>';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    renderSurveyDiagram();
});
</script>
@endpush
@endsection
