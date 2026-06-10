@props([
    'id'       => 'data-table-'.uniqid(),
    'headers'  => [],
    'exportUrl'=> null,
    'exportPdfUrl' => null,
])

<div class="dt-wrapper">
    {{-- ── Toolbar ─────────────────────────────────────────────── --}}
    <div class="dt-toolbar">
        <div class="dt-select-actions">
            <label class="dt-check-label">
                <input type="checkbox" id="checkAll-{{ $id }}" onclick="dtSelectAll('{{ $id }}')">
                <span>সব Select</span>
            </label>
            <span class="dt-selected-count" id="count-{{ $id }}" style="display:none"></span>
        </div>
        <div class="dt-export-btns">
            @if($exportPdfUrl)
            <a href="{{ $exportPdfUrl }}" class="btn-export btn-pdf" id="exportPdf-{{ $id }}">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                PDF
            </a>
            @endif
            @if($exportUrl)
            <a href="{{ $exportUrl }}" class="btn-export btn-excel" id="exportExcel-{{ $id }}">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Excel
            </a>
            @endif
        </div>
    </div>

    {{-- ── Table ───────────────────────────────────────────────── --}}
    <div class="dt-table-wrap">
        <table class="dt-table" id="{{ $id }}">
            <thead>
                <tr>
                    <th width="36"><input type="checkbox" onclick="dtSelectAll('{{ $id }}')"></th>
                    @foreach($headers as $h)
                        <th>{{ $h }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>

@once
@push('scripts')
<script>
function dtSelectAll(tableId) {
    const table = document.getElementById(tableId);
    const checkAll = document.getElementById('checkAll-' + tableId);
    const boxes = table.querySelectorAll('tbody input[type="checkbox"]');
    boxes.forEach(b => b.checked = checkAll ? checkAll.checked : true);
    dtUpdateCount(tableId);
}

function dtUpdateCount(tableId) {
    const table = document.getElementById(tableId);
    const total = table.querySelectorAll('tbody input[type="checkbox"]:checked').length;
    const el = document.getElementById('count-' + tableId);
    if (el) {
        el.textContent = total + 'টি selected';
        el.style.display = total > 0 ? 'inline' : 'none';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.dt-table tbody input[type="checkbox"]').forEach(function(box) {
        box.addEventListener('change', function() {
            const tableId = box.closest('table').id;
            dtUpdateCount(tableId);
        });
    });
});
</script>
@endpush
@endonce
