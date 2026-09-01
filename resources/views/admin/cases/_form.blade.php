@php
    $isEdit = isset($case);

    $sittingDates = old('sitting_dates', $isEdit ? $case->sitting_dates ?? [] : []);
    if (empty($sittingDates)) {
        $sittingDates = [null]; // always show at least one row
    }

    $currentStatus = old('status', $isEdit ? $case->status : 'pending');
@endphp

<div class="mb-4">
    <label for="case_no" class="block text-xs font-semibold text-muted uppercase tracking-wide mb-1.5">Case No.</label>
    <textarea id="case_no" name="case_no" rows="1"
        class="w-full px-3 py-2.5 border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-maroon/20 resize-none overflow-hidden"
        oninput="autoResize(this)">{{ old('case_no', $isEdit ? $case->case_no : '') }}</textarea>
    @error('case_no')
        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label for="parties_name"
        class="block text-xs font-semibold text-muted uppercase tracking-wide mb-1.5">Parties</label>
    <textarea id="parties_name" name="parties_name" rows="2"
        class="w-full px-3 py-2.5 border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-maroon/20 resize-none overflow-hidden"
        oninput="autoResize(this)">{{ old('parties_name', $isEdit ? $case->parties_name : '') }}</textarea>
    @error('parties_name')
        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="grid grid-cols-2 gap-4 mb-4">
    <div>
        <label for="case_category_id"
            class="block text-xs font-semibold text-muted uppercase tracking-wide mb-1.5">Category</label>
        <select id="case_category_id" name="case_category_id"
            class="w-full px-3 py-2.5 border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-maroon/20">
            <option value="">-- Select --</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('case_category_id', $isEdit ? $case->case_category_id : null) == $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @error('case_category_id')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="mediator_id"
            class="block text-xs font-semibold text-muted uppercase tracking-wide mb-1.5">Mediator</label>
        <select id="mediator_id" name="mediator_id"
            class="w-full px-3 py-2.5 border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-maroon/20">
            <option value="">-- Select --</option>
            @foreach ($mediators as $mediator)
                <option value="{{ $mediator->id }}" @selected(old('mediator_id', $isEdit ? $case->mediator_id : null) == $mediator->id)>
                    {{ $mediator->advocate_name }}
                </option>
            @endforeach
        </select>
        @error('mediator_id')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="grid grid-cols-2 gap-4 mb-4">
    <div>
        <label for="received_date"
            class="block text-xs font-semibold text-muted uppercase tracking-wide mb-1.5">Received Date</label>
        <input id="received_date" name="received_date" type="date"
            value="{{ old('received_date', $isEdit ? optional($case->received_date)->format('Y-m-d') : '') }}"
            class="w-full px-3 py-2.5 border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-maroon/20">
        @error('received_date')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="assigned_date"
            class="block text-xs font-semibold text-muted uppercase tracking-wide mb-1.5">Assigned Date</label>
        <input id="assigned_date" name="assigned_date" type="date"
            value="{{ old('assigned_date', $isEdit ? optional($case->assigned_date)->format('Y-m-d') : '') }}"
            class="w-full px-3 py-2.5 border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-maroon/20">
        @error('assigned_date')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="grid grid-cols-2 gap-4 mb-4">
    <div>
        <label for="first_mediation_date"
            class="block text-xs font-semibold text-muted uppercase tracking-wide mb-1.5">First Mediation Date</label>
        <input id="first_mediation_date" name="first_mediation_date" type="date"
            value="{{ old('first_mediation_date', $isEdit ? optional($case->first_mediation_date)->format('Y-m-d') : '') }}"
            class="w-full px-3 py-2.5 border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-maroon/20">
        @error('first_mediation_date')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="final_result_date"
            class="block text-xs font-semibold text-muted uppercase tracking-wide mb-1.5">Final Result Date</label>
        <input id="final_result_date" name="final_result_date" type="date"
            value="{{ old('final_result_date', $isEdit ? optional($case->final_result_date)->format('Y-m-d') : '') }}"
            class="w-full px-3 py-2.5 border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-maroon/20">
        @error('final_result_date')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

{{-- Repeatable sitting dates: add/remove rows, all posted as sitting_dates[] --}}
<div class="mb-4">
    <label class="block text-xs font-semibold text-muted uppercase tracking-wide mb-1.5">Sitting Dates</label>

    <div id="sitting-dates-wrapper" class="space-y-2">
        @foreach ($sittingDates as $date)
            <div class="sitting-date-row flex gap-2">
                <input type="date" name="sitting_dates[]" value="{{ $date }}"
                    class="flex-1 px-3 py-2.5 border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-maroon/20">
                <button type="button" onclick="removeSittingDateRow(this)"
                    class="px-3 rounded-lg border border-border text-slate-500 hover:bg-slate-50">&minus;</button>
            </div>
        @endforeach
    </div>

    <button type="button" onclick="addSittingDateRow()"
        class="mt-2 text-sm font-medium text-slate-700 hover:text-slate-900">+ Add sitting date</button>

    @error('sitting_dates')
        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
    @enderror
    @error('sitting_dates.*')
        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label for="status" class="block text-xs font-semibold text-muted uppercase tracking-wide mb-1.5">Status</label>
    <select id="status" name="status" onchange="toggleAmountField()"
        class="w-full px-3 py-2.5 border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-maroon/20">
        <option value="pending" @selected($currentStatus === 'pending')>Pending</option>
        <option value="settled" @selected($currentStatus === 'settled')>Settled</option>
        <option value="unsettled" @selected($currentStatus === 'unsettled')>Unsettled</option>
    </select>
    @error('status')
        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

{{-- Only relevant once settled/unsettled — shown/hidden by toggleAmountField() --}}
<div id="amount-field" class="mb-6">
    <label for="amount" class="block text-xs font-semibold text-muted uppercase tracking-wide mb-1.5">Amount (given
        to
        mediator)</label>
    <input id="amount" name="amount" type="number" step="0.01" min="0"
        value="{{ old('amount', $isEdit ? $case->amount : '') }}"
        class="w-full px-3 py-2.5 border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-maroon/20">
    @error('amount')
        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<script>
    function addSittingDateRow() {
        const wrapper = document.getElementById('sitting-dates-wrapper');
        const row = document.createElement('div');
        row.className = 'sitting-date-row flex gap-2';
        row.innerHTML = `
            <input type="date" name="sitting_dates[]"
                class="flex-1 px-3 py-2.5 border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-maroon/20">
            <button type="button" onclick="removeSittingDateRow(this)"
                class="px-3 rounded-lg border border-border text-slate-500 hover:bg-slate-50">&minus;</button>
        `;
        wrapper.appendChild(row);
    }

    function removeSittingDateRow(button) {
        const wrapper = document.getElementById('sitting-dates-wrapper');
        // Always keep at least one row so the field never disappears entirely.
        if (wrapper.querySelectorAll('.sitting-date-row').length > 1) {
            button.closest('.sitting-date-row').remove();
        } else {
            button.closest('.sitting-date-row').querySelector('input').value = '';
        }
    }

    function toggleAmountField() {
        const status = document.getElementById('status').value;
        const field = document.getElementById('amount-field');
        field.style.display = (status === 'settled' || status === 'unsettled') ? 'block' : 'none';
    }

    // Auto-grow a textarea to fit its content.
    function autoResize(el) {
        el.style.height = 'auto';
        el.style.height = el.scrollHeight + 'px';
    }

    // Plain Enter does nothing (no newline, no accidental form submit).
    // Shift+Enter or Alt+Enter inserts a line break at the cursor.
    function handleControlledEnter(e) {
        if (e.key !== 'Enter') return;

        if (e.shiftKey || e.altKey) {
            e.preventDefault();
            const el = e.target;
            const start = el.selectionStart;
            const end = el.selectionEnd;
            el.value = el.value.slice(0, start) + '\n' + el.value.slice(end);
            el.selectionStart = el.selectionEnd = start + 1;
            autoResize(el);
        } else {
            e.preventDefault();
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        toggleAmountField();

        ['case_no', 'parties_name'].forEach(function(id) {
            const el = document.getElementById(id);
            if (!el) return;
            el.addEventListener('keydown', handleControlledEnter);
            autoResize(el); // correct initial height for pre-filled edit values
        });
    });
</script>
