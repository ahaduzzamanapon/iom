@extends('layouts.admin')
@section('title', isset($survey) ? 'Edit Survey' : 'Create Survey')
@section('page-title', isset($survey) ? 'Edit Survey' : 'Create Survey')

@push('styles')
<style>
.survey-layout { display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 24px; align-items: start; }
.sticky-diagram { position: sticky; top: 80px; background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 20px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
.field-card { border: 1px solid #e2e8f0; border-radius: 10px; margin-bottom: 16px; transition: 0.2s; }
.field-card:hover { border-color: #4fc3f7; }
.option-row { display: flex; gap: 8px; margin-bottom: 8px; align-items: center; }
.question-grid-main { display: grid; grid-template-columns: 1.5fr 1fr; gap: 16px; margin-bottom: 12px; }
.question-grid-sub { display: grid; grid-template-columns: 1fr 1fr 120px 100px; gap: 12px; align-items: end; margin-bottom: 12px; }
@media (max-width: 1024px) {
  .survey-layout { grid-template-columns: 1fr; }
  .sticky-diagram { position: static; }
}
@media (max-width: 640px) {
  .question-grid-main { grid-template-columns: 1fr; }
  .question-grid-sub { grid-template-columns: 1fr; align-items: stretch; }
}
</style>
@endpush

@section('content')
<div class="page-header">
  <div>
    <div class="page-title">📋 {{ isset($survey) ? 'Edit Survey' : 'Create Survey' }}</div>
    <div class="page-sub">সার্ভে কন্ডিশনাল ব্রাঞ্চিং এবং লাইভ ফ্লোচার্ট ডায়াগ্রাম</div>
  </div>
  <a href="{{ route('admin.surveys.index') }}" class="btn btn-outline">← Back</a>
</div>

<form action="{{ isset($survey) ? route('admin.surveys.update', $survey) : route('admin.surveys.store') }}" method="POST" id="surveyBuilderForm">
  @csrf
  @if(isset($survey)) @method('PUT') @endif

  <div class="survey-layout">
    {{-- Left Side: Form Fields Editor --}}
    <div>
      <div class="card" style="margin-bottom:20px">
        <div class="card-body">
          <div class="form-group">
            <label class="form-label">Survey Title *</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $survey->title ?? '') }}" placeholder="e.g. কোর্স সংক্রান্ত মতামত সার্ভে" required>
          </div>
          <div class="form-group">
            <label class="form-label">Closes At</label>
            <input type="date" name="closes_at" class="form-control" value="{{ old('closes_at', isset($survey) && $survey->closes_at ? $survey->closes_at->format('Y-m-d') : '') }}">
          </div>
          <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3" placeholder="সার্ভের বিবরণ... (ঐচ্ছিক)">{{ old('description', $survey->description ?? '') }}</textarea>
          </div>
        </div>
      </div>

      <div class="page-header" style="margin-bottom:12px">
        <div class="page-title" style="font-size:16px">📌 Questions (Fields)</div>
        <button type="button" class="btn btn-outline" onclick="addNewField()">+ Add Question</button>
      </div>

      <div id="fieldsContainer"></div>

      <div style="margin-top:24px">
        <button type="submit" class="btn btn-primary" style="padding:12px 30px">Save Survey</button>
        <a href="{{ route('admin.surveys.index') }}" class="btn btn-outline" style="margin-left:8px">Cancel</a>
      </div>
    </div>

    {{-- Right Side: Live Visual Flowchart --}}
    <div class="sticky-diagram">
      <div style="font-weight:700;font-size:15px;color:#1a202c;margin-bottom:12px;display:flex;align-items:center;gap:8px">
        <span>📊 Live Branching Flowchart</span>
        <span class="badge badge-blue" style="font-size:10px">Auto Sync</span>
      </div>
      <p style="font-size:12px;color:#718096;margin-bottom:16px">বাম পাশের ব্রাঞ্চিং ও প্রশ্ন পরিবর্তনের সাথে সাথে এই ডায়াগ্রামটি স্বয়ংক্রিয়ভাবে আপডেট হবে।</p>
      <div style="position:relative; overflow:hidden; background:#f8fafc; border-radius:8px; border:1px dashed #cbd5e1;">
        <!-- Zoom controls -->
        <div style="position: absolute; right: 12px; top: 12px; display: flex; flex-direction: column; gap: 6px; z-index: 10;">
          <button type="button" id="zoom-in" class="btn btn-sm btn-outline" style="padding: 6px 10px; background: white; border: 1px solid #cbd5e1; border-radius: 4px; font-weight: bold; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 1px 3px rgba(0,0,0,0.1); width: 32px; height: 32px;" title="Zoom In">+</button>
          <button type="button" id="zoom-out" class="btn btn-sm btn-outline" style="padding: 6px 10px; background: white; border: 1px solid #cbd5e1; border-radius: 4px; font-weight: bold; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 1px 3px rgba(0,0,0,0.1); width: 32px; height: 32px;" title="Zoom Out">-</button>
          <button type="button" id="zoom-reset" class="btn btn-sm btn-outline" style="padding: 4px 8px; background: white; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 11px; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 1px 3px rgba(0,0,0,0.1); height: 32px;" title="Reset Zoom">Reset</button>
        </div>
        <div id="diagramContainer" style="width: 100%; height: 400px; cursor: grab; display: flex; align-items: center; justify-content: center;">
          <span style="color:#94a3b8;font-size:13px">ডায়াগ্রাম লোড হচ্ছে...</span>
        </div>
      </div>
    </div>
  </div>
</form>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/mermaid/dist/mermaid.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/svg-pan-zoom@3.6.1/dist/svg-pan-zoom.min.js"></script>
<script>
let fieldCount = 0;
const optionTypes = ['select', 'radio'];
let panZoomInstance = null;

// Initial data for editing
const initialQuestions = @json(isset($survey) ? $survey->questions : []);

mermaid.initialize({
    startOnLoad: false,
    securityLevel: 'loose',
    theme: 'neutral',
    flowchart: { useMaxWidth: true, htmlLabels: true }
});

function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
}

let updateDiagramTimeout = null;

function updateDiagram() {
    if (updateDiagramTimeout) {
        clearTimeout(updateDiagramTimeout);
    }
    updateDiagramTimeout = setTimeout(executeUpdateDiagram, 50);
}

function executeUpdateDiagram() {
    if (panZoomInstance) {
        try {
            panZoomInstance.destroy();
        } catch (e) {
            console.error("Error destroying svgPanZoom", e);
        }
        panZoomInstance = null;
    }

    let code = "graph TD\n";
    code += "classDef startNode fill:#ecfdf5,stroke:#059669,stroke-width:2px,color:#065f46;\n";
    code += "classDef normalNode fill:#eff6ff,stroke:#2563eb,stroke-width:1px,color:#1e40af;\n";
    code += "classDef endNode fill:#fef2f2,stroke:#dc2626,stroke-width:2px,color:#991b1b;\n";

    const fields = [];
    document.querySelectorAll('.field-card').forEach((card) => {
        const id = card.dataset.id;
        const keyEl = document.getElementById(`key_${id}`);
        const labelEl = document.getElementById(`label_${id}`);
        const typeEl = document.getElementById(`type_${id}`);
        const nextEl = document.getElementById(`next_${id}`);
        const startEl = document.getElementById(`start_${id}`);

        if (!keyEl || !labelEl || !typeEl || !nextEl || !startEl) return;

        const key = keyEl.value.trim().replace(/\s+/g, '_').toLowerCase();
        const label = labelEl.value.trim();
        const type = typeEl.value;
        const defaultNext = nextEl.value.trim().replace(/\s+/g, '_').toLowerCase();
        const isStart = startEl.checked;
        
        // Options
        const options = [];
        card.querySelectorAll('.option-row').forEach((optRow) => {
            const valInput = optRow.querySelector('.opt-val');
            const targetInput = optRow.querySelector('.opt-target');
            if (valInput && targetInput) {
                const val = valInput.value.trim();
                const target = targetInput.value.trim().replace(/\s+/g, '_').toLowerCase();
                options.push({ value: val, next_question_key: target });
            }
        });

        if (key) {
            fields.push({ key, label, type, defaultNext, isStart, options });
        }
    });

    if (fields.length === 0) {
        document.getElementById('diagramContainer').innerHTML = '<span style="color:#94a3b8">কোনো প্রশ্ন যুক্ত করা হয়নি।</span>';
        return;
    }

    // Nodes
    fields.forEach((f) => {
        const shape = `["${f.key}<br/><small style='opacity:0.75'>(${f.type})</small><br/><b>${escapeHtml(f.label.substring(0, 30))}${f.label.length > 30 ? '...' : ''}</b>"]`;
        code += `  ${f.key}${shape}\n`;
        if (f.isStart) {
            code += `  class ${f.key} startNode;\n`;
        } else {
            code += `  class ${f.key} normalNode;\n`;
        }
    });

    // Edges
    fields.forEach((f) => {
        if (optionTypes.includes(f.type) && f.options.length > 0) {
            f.options.forEach((opt) => {
                let target = opt.next_question_key || f.defaultNext || 'endNode';
                if (target === 'end') target = 'endNode';
                code += `  ${f.key} -- "${escapeHtml(opt.value)}" --> ${target}\n`;
            });
        } else {
            let target = f.defaultNext || 'endNode';
            if (target === 'end') target = 'endNode';
            code += `  ${f.key} --> ${target}\n`;
        }
    });

    code += "  endNode[End of Survey]\n";
    code += "  class endNode endNode;\n";

    // Unique render element ID to prevent concurrent promise resolution race conditions
    const renderId = 'mermaidTemp_' + Math.floor(Math.random() * 1000000);
    document.getElementById('diagramContainer').innerHTML = `<div id="${renderId}"></div>`;
    
    try {
        mermaid.render(renderId, code).then(({ svg }) => {
            const container = document.getElementById('diagramContainer');
            container.innerHTML = svg;
            
            const svgElement = container.querySelector('svg');
            if (svgElement) {
                svgElement.style.width = '100%';
                svgElement.style.height = '100%';
                svgElement.style.maxWidth = 'none';
                
                panZoomInstance = svgPanZoom(svgElement, {
                    zoomEnabled: true,
                    controlIconsEnabled: false,
                    fit: true,
                    center: true,
                    minZoom: 0.1,
                    maxZoom: 10
                });
            }
        }).catch(err => {
            console.error(err);
            document.getElementById('diagramContainer').innerHTML = '<span style="color:#ef4444;font-size:12px">ডায়াগ্রাম সিনট্যাক্স ভুল (একই কি দুইবার ব্যবহার বা সাইক্লিক রেফারেন্স হতে পারে)</span>';
        });
    } catch (e) {
        console.error(e);
        document.getElementById('diagramContainer').innerHTML = '<span style="color:#94a3b8">ডায়াগ্রাম রেন্ডার হচ্ছে...</span>';
    }
}

function handleStartCheckbox(checkbox, currentId) {
    if (checkbox.checked) {
        document.querySelectorAll('.is-start-check').forEach((ch) => {
            if (ch.id !== checkbox.id) ch.checked = false;
        });
    }
    updateDiagram();
}

function addNewField(data = null) {
    fieldCount++;
    const id = fieldCount;
    const idx = id - 1;

    const html = `
    <div class="card field-card" id="field_card_${id}" data-id="${id}">
      <div class="card-body">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px;border-bottom:1px solid #f1f5f9;padding-bottom:8px">
          <span style="font-weight:700;color:#0f172a">Question #${id}</span>
          <button type="button" class="btn btn-sm btn-danger" onclick="removeField(${id})" style="padding:4px 8px;font-size:11px">Remove</button>
        </div>

        <div class="question-grid-main">
          <div class="form-group" style="margin:0">
            <label class="form-label">Question Text *</label>
            <input type="text" name="fields[${idx}][label]" id="label_${id}" class="form-control" value="${data ? escapeHtml(data.label) : ''}" required oninput="updateDiagram()">
          </div>
          <div class="form-group" style="margin:0">
            <label class="form-label">Question Key (Unique code) *</label>
            <input type="text" name="fields[${idx}][question_key]" id="key_${id}" class="form-control" value="${data ? data.question_key : 'q' + id}" required oninput="updateDiagram()" style="font-family:monospace">
          </div>
        </div>

        <div class="question-grid-sub">
          <div class="form-group" style="margin:0">
            <label class="form-label">Input Type *</label>
            <select name="fields[${idx}][type]" id="type_${id}" class="form-control" onchange="onTypeChange(${id})" required>
              <option value="text" ${data && data.type === 'text' ? 'selected' : ''}>Text</option>
              <option value="textarea" ${data && data.type === 'textarea' ? 'selected' : ''}>Textarea</option>
              <option value="select" ${data && data.type === 'select' ? 'selected' : ''}>Dropdown (Select)</option>
              <option value="radio" ${data && data.type === 'radio' ? 'selected' : ''}>Radio Select</option>
              <option value="number" ${data && data.type === 'number' ? 'selected' : ''}>Number</option>
              <option value="date" ${data && data.type === 'date' ? 'selected' : ''}>Date</option>
            </select>
          </div>

          <div class="form-group" style="margin:0">
            <label class="form-label">Default Next Question Key</label>
            <input type="text" name="fields[${idx}][default_next_question_key]" id="next_${id}" class="form-control" value="${data ? (data.default_next_question_key || '') : ''}" placeholder="e.g. q3 or end" oninput="updateDiagram()" style="font-family:monospace">
          </div>

          <div style="margin-bottom:8px">
            <label class="form-label" style="display:inline-flex;align-items:center;gap:6px;cursor:pointer">
              <input type="checkbox" name="fields[${idx}][is_start]" id="start_${id}" class="is-start-check" value="1" ${data && data.is_start ? 'checked' : (id===1?'checked':'')} onchange="handleStartCheckbox(this, ${id})">
              <span>Is Start?</span>
            </label>
          </div>

          <div style="margin-bottom:8px">
            <label class="form-label" style="display:inline-flex;align-items:center;gap:6px;cursor:pointer">
              <input type="checkbox" name="fields[${idx}][required]" value="1" ${data && data.required ? 'checked' : ''}>
              <span>Required</span>
            </label>
          </div>
        </div>

        {{-- Dynamic Branch Options --}}
        <div id="options_section_${id}" style="display:${data && optionTypes.includes(data.type) ? 'block' : 'none'};background:#f8fafc;border-radius:8px;padding:12px;border:1px solid #e2e8f0;margin-top:12px">
          <div style="font-weight:600;font-size:12px;margin-bottom:8px;color:#475569">Conditional Branching Options:</div>
          <div id="options_container_${id}"></div>
          <button type="button" class="btn btn-sm btn-outline" onclick="addOptionRow(${id})" style="font-size:11px;padding:4px 8px;margin-top:4px">+ Add Option Branch</button>
        </div>
      </div>
    </div>`;

    document.getElementById('fieldsContainer').insertAdjacentHTML('beforeend', html);
    
    // Add existing options if editing
    if (data && optionTypes.includes(data.type) && Array.isArray(data.options)) {
        data.options.forEach(opt => addOptionRow(id, opt));
    }
    
    updateDiagram();
}

function removeField(id) {
    document.getElementById('field_card_' + id)?.remove();
    updateDiagram();
}

function onTypeChange(id) {
    const type = document.getElementById('type_' + id).value;
    const optSec = document.getElementById('options_section_' + id);
    if (optionTypes.includes(type)) {
        optSec.style.display = 'block';
        // add one default empty option if none exists
        const container = document.getElementById('options_container_' + id);
        if (container.children.length === 0) {
            addOptionRow(id);
        }
    } else {
        optSec.style.display = 'none';
    }
    updateDiagram();
}

function addOptionRow(fieldId, optData = null) {
    const container = document.getElementById('options_container_' + fieldId);
    const optIdx = container.children.length;
    const fIdx = fieldId - 1;

    const rowHtml = `
    <div class="option-row" id="opt_row_${fieldId}_${optIdx}">
      <input type="text" name="fields[${fIdx}][options][${optIdx}][value]" class="form-control form-control-sm opt-val" value="${optData ? escapeHtml(optData.value) : ''}" placeholder="Option text (e.g. Yes)" required oninput="updateDiagram()" style="flex:1">
      <span style="font-size:11px;color:#64748b">→ branches to →</span>
      <input type="text" name="fields[${fIdx}][options][${optIdx}][next_question_key]" class="form-control form-control-sm opt-target" value="${optData ? (optData.next_question_key || '') : ''}" placeholder="Target key (e.g. q2 or end)" oninput="updateDiagram()" style="flex:1;font-family:monospace">
      <button type="button" class="btn btn-sm btn-outline" onclick="removeOptionRow(${fieldId}, ${optIdx})" style="padding:2px 6px;color:#ef4444">✕</button>
    </div>`;

    container.insertAdjacentHTML('beforeend', rowHtml);
    updateDiagram();
}

function removeOptionRow(fieldId, optIdx) {
    document.getElementById(`opt_row_${fieldId}_${optIdx}`)?.remove();
    updateDiagram();
}

document.addEventListener('DOMContentLoaded', function() {
    if (initialQuestions.length > 0) {
        initialQuestions.forEach(q => addNewField(q));
    } else {
        addNewField();
    }

    // Bind zoom buttons
    document.getElementById('zoom-in').addEventListener('click', function() {
        if (panZoomInstance) panZoomInstance.zoomIn();
    });
    document.getElementById('zoom-out').addEventListener('click', function() {
        if (panZoomInstance) panZoomInstance.zoomOut();
    });
    document.getElementById('zoom-reset').addEventListener('click', function() {
        if (panZoomInstance) {
            panZoomInstance.reset();
            panZoomInstance.fit();
            panZoomInstance.center();
        }
    });

    const container = document.getElementById('diagramContainer');
    container.addEventListener('mousedown', function() {
        container.style.cursor = 'grabbing';
    });
    container.addEventListener('mouseup', function() {
        container.style.cursor = 'grab';
    });
});
</script>
@endpush
@endsection
