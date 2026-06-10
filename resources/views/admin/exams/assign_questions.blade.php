@extends('layouts.admin')
@section('title', 'Assign Questions')
@section('content')
<div class="page-header">
  <div>
    <div class="page-title">Manage Questions for: {{ $exam->title }}</div>
    <div class="page-sub">{{ $exam->subject->name ?? 'No Subject' }} | Passing Marks: {{ $exam->pass_marks }} / {{ $exam->total_marks }}</div>
  </div>
  <a href="{{ route('admin.exams.show', $exam) }}" class="btn btn-outline">← Back to Exam</a>
</div>

<style>
.drag-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    margin-top: 16px;
}
.drag-column {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 20px;
    display: flex;
    flex-direction: column;
    min-height: 500px;
}
.drag-title {
    font-size: 16px;
    font-weight: 700;
    margin-bottom: 12px;
    padding-bottom: 10px;
    border-bottom: 2px solid #f0f4f8;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.drag-list {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 12px;
    overflow-y: auto;
    padding: 10px 4px;
    border: 2px dashed transparent;
    border-radius: 8px;
    transition: all 0.2s;
}
.drag-list.drag-over {
    border-color: #4fc3f7;
    background: rgba(79, 195, 247, 0.05);
}
.question-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 14px;
    cursor: grab;
    user-select: none;
    transition: all 0.2s;
    display: flex;
    flex-direction: column;
    gap: 8px;
    position: relative;
}
.question-card:hover {
    border-color: #cbd5e1;
    box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05);
    background: #fff;
}
.question-card:active {
    cursor: grabbing;
}
.question-text {
    font-size: 13px;
    font-weight: 600;
    color: #1e293b;
    padding-right: 32px;
}
.question-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 11px;
    color: #64748b;
}
.action-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    border: none;
    background: none;
    cursor: pointer;
    font-size: 16px;
    color: #94a3b8;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
}
.action-btn:hover {
    background: #f1f5f9;
}
.assigned-col .action-btn:hover {
    color: #ef4444;
}
.unassigned-col .action-btn:hover {
    color: #10b981;
}
.empty-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: #94a3b8;
    font-size: 13px;
    text-align: center;
    padding: 40px 0;
}
</style>

<div class="drag-container">
    {{-- Left: Question Bank --}}
    <div class="drag-column unassigned-col">
        <div class="drag-title">
            <span>📚 Question Bank</span>
            <span class="badge badge-gray" id="unassigned-count">{{ count($unassignedQuestions) }} unassigned</span>
        </div>
        <div class="drag-list" id="unassigned-list" ondragover="allowDrop(event)" ondrop="drop(event)" ondragenter="dragEnter(event)" ondragleave="dragLeave(event)">
            @forelse($unassignedQuestions as $q)
                <div class="question-card" id="q-{{ $q->id }}" draggable="true" ondragstart="drag(event)" data-id="{{ $q->id }}">
                    <button class="action-btn" onclick="moveToAssigned('{{ $q->id }}')">➕</button>
                    <div class="question-text">{{ $q->question_text }}</div>
                    <div class="question-meta">
                        <span class="badge badge-blue">{{ strtoupper($q->type ?? 'mcq') }}</span>
                        <span>Marks: <strong>{{ $q->marks }}</strong></span>
                    </div>
                </div>
            @empty
                <div class="empty-placeholder" id="unassigned-empty">No unassigned questions found for this subject.</div>
            @endforelse
        </div>
    </div>

    {{-- Right: Exam Paper --}}
    <div class="drag-column assigned-col">
        <div class="drag-title">
            <span>📝 Assigned to Exam</span>
            <span class="badge badge-green" id="assigned-count">{{ count($assignedQuestions) }} assigned</span>
        </div>
        <div class="drag-list" id="assigned-list" ondragover="allowDrop(event)" ondrop="drop(event)" ondragenter="dragEnter(event)" ondragleave="dragLeave(event)">
            @forelse($assignedQuestions as $q)
                <div class="question-card" id="q-{{ $q->id }}" draggable="true" ondragstart="drag(event)" data-id="{{ $q->id }}">
                    <button class="action-btn" onclick="moveToUnassigned('{{ $q->id }}')">❌</button>
                    <div class="question-text">{{ $q->question_text }}</div>
                    <div class="question-meta">
                        <span class="badge badge-blue">{{ strtoupper($q->type ?? 'mcq') }}</span>
                        <span>Marks: <strong>{{ $q->marks }}</strong></span>
                    </div>
                </div>
            @empty
                <div class="empty-placeholder" id="assigned-empty">Drag and drop questions here to assign them to this exam.</div>
            @endforelse
        </div>
        
        <form method="POST" action="{{ route('admin.exams.questions.assign.store', $exam) }}" style="margin-top:20px" onsubmit="prepareSubmit()">
            @csrf
            <div id="hidden-inputs-container"></div>
            <button type="submit" class="btn btn-primary" style="width:100%">Save Question Paper</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
function allowDrop(ev) {
    ev.preventDefault();
}

function drag(ev) {
    ev.dataTransfer.setData("text/plain", ev.currentTarget.id);
}

function dragEnter(ev) {
    ev.currentTarget.classList.add('drag-over');
}

function dragLeave(ev) {
    ev.currentTarget.classList.remove('drag-over');
}

function drop(ev) {
    ev.preventDefault();
    var list = ev.currentTarget;
    list.classList.remove('drag-over');
    
    var data = ev.dataTransfer.getData("text/plain");
    var card = document.getElementById(data);
    if (!card) return;
    
    // Add to new list
    list.appendChild(card);
    updatePlaceholders();
    updateCounts();
    updateButtonActions(card, list.id === 'assigned-list');
}

function moveToAssigned(id) {
    var card = document.getElementById('q-' + id);
    var targetList = document.getElementById('assigned-list');
    if (card && targetList) {
        targetList.appendChild(card);
        updatePlaceholders();
        updateCounts();
        updateButtonActions(card, true);
    }
}

function moveToUnassigned(id) {
    var card = document.getElementById('q-' + id);
    var targetList = document.getElementById('unassigned-list');
    if (card && targetList) {
        targetList.appendChild(card);
        updatePlaceholders();
        updateCounts();
        updateButtonActions(card, false);
    }
}

function updateButtonActions(card, isAssigned) {
    var btn = card.querySelector('.action-btn');
    var qId = card.getAttribute('data-id');
    if (isAssigned) {
        btn.innerText = '❌';
        btn.setAttribute('onclick', "moveToUnassigned('" + qId + "')");
    } else {
        btn.innerText = '➕';
        btn.setAttribute('onclick', "moveToAssigned('" + qId + "')");
    }
}

function updatePlaceholders() {
    var unassignedList = document.getElementById('unassigned-list');
    var assignedList = document.getElementById('assigned-list');
    
    // Check unassigned
    var unassignedCards = unassignedList.querySelectorAll('.question-card');
    var unassignedEmpty = document.getElementById('unassigned-empty');
    if (unassignedCards.length === 0) {
        if (!unassignedEmpty) {
            unassignedList.innerHTML += '<div class="empty-placeholder" id="unassigned-empty">No unassigned questions found for this subject.</div>';
        }
    } else if (unassignedEmpty) {
        unassignedEmpty.remove();
    }
    
    // Check assigned
    var assignedCards = assignedList.querySelectorAll('.question-card');
    var assignedEmpty = document.getElementById('assigned-empty');
    if (assignedCards.length === 0) {
        if (!assignedEmpty) {
            assignedList.innerHTML += '<div class="empty-placeholder" id="assigned-empty">Drag and drop questions here to assign them to this exam.</div>';
        }
    } else if (assignedEmpty) {
        assignedEmpty.remove();
    }
}

function updateCounts() {
    var unassignedCards = document.getElementById('unassigned-list').querySelectorAll('.question-card');
    var assignedCards = document.getElementById('assigned-list').querySelectorAll('.question-card');
    
    document.getElementById('unassigned-count').innerText = unassignedCards.length + ' unassigned';
    document.getElementById('assigned-count').innerText = assignedCards.length + ' assigned';
}

function prepareSubmit() {
    var container = document.getElementById('hidden-inputs-container');
    container.innerHTML = '';
    
    var assignedCards = document.getElementById('assigned-list').querySelectorAll('.question-card');
    assignedCards.forEach(function(card) {
        var qId = card.getAttribute('data-id');
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'question_ids[]';
        input.value = qId;
        container.appendChild(input);
    });
}
</script>
@endpush
@endsection
