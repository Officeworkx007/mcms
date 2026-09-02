@extends('admin.layouts.master')

@section('title', 'Cases')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-maroon shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12h6m-6 4h6M9 8h1M5 4h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V5a1 1 0 011-1z" />
            </svg>
            <div>
                <h1 class="text-base font-bold text-ink">Cases</h1>
                <p class="text-xs text-muted">All mediation cases on record. Click a row to see full details.</p>
            </div>
        </div>
        <a href="{{ route('admin.cases.create') }}"
            class="bg-maroon hover:bg-maroon/90 text-white text-sm font-semibold px-4 py-2.5 rounded-md transition-colors">
            + Add Case
        </a>
    </div>

    @if (session('status'))
        <div class="mb-5">
            <x-alert type="success" title="Success!" :message="session('status')" :dismiss-after="10000" />
        </div>
    @endif

    @if (session('error'))
        <div class="mb-5">
            <x-alert type="error" title="Error!" :message="session('error')" :dismiss-after="10000" />
        </div>
    @endif

    <div class="bg-white border border-border rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-[13px] table-fixed border-collapse">
                <thead>
                    <tr
                        class="border-b border-border text-left text-[10.5px] font-semibold text-muted uppercase tracking-wider bg-slate-50/80">
                        <th class="px-3 py-2 w-6"></th>
                        <th class="px-3 py-2 w-8">#</th>
                        <th class="px-3 py-2 w-36">Case No.</th>
                        <th class="px-3 py-2 w-44">Parties</th>
                        <th class="px-3 py-2 w-24">Category</th>
                        <th class="px-3 py-2 w-24">Mediator</th>
                        <th class="px-3 py-2 w-[74px]">Received</th>
                        <th class="px-3 py-2 w-[74px]">Assigned</th>
                        <th class="px-3 py-2 w-[74px]">1st Sitting</th>
                        <th class="px-3 py-2 w-[74px]">Result</th>
                        <th class="px-3 py-2 w-32">Sittings</th>
                        <th class="px-3 py-2 w-20">Status</th>
                        <th class="px-3 py-2 w-16 text-right">Amount</th>
                        <th class="px-3 py-2 w-16 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border/70">
                    @forelse ($cases as $case)
                        @php
                            $caseNoFlat = $case->case_no ? str_replace(["\r\n", "\r", "\n"], ' ', $case->case_no) : '—';
                            $sittingList = collect($case->sitting_dates ?? [])->map(
                                fn($d) => \Illuminate\Support\Carbon::parse($d)->format('d-m-Y'),
                            );
                            $rowId = 'case-row-' . $case->id;
                            $detailId = 'case-detail-' . $case->id;
                        @endphp

                        {{-- Summary row: click anywhere except Actions to expand/collapse --}}
                        <tr id="{{ $rowId }}"
                            class="js-case-row group cursor-pointer align-middle text-body odd:bg-white even:bg-slate-50/30 hover:bg-maroon/5 transition-colors"
                            data-target="{{ $detailId }}">
                            <td class="px-2 py-1.5 text-slate-400">
                                <svg class="js-case-chevron h-3.5 w-3.5 transition-transform duration-150" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </td>
                            <td class="px-3 py-1.5 text-muted text-xs">{{ $cases->firstItem() + $loop->index }}</td>
                            <td class="px-3 py-1.5 font-semibold text-ink truncate">{{ $caseNoFlat }}</td>
                            <td class="px-3 py-1.5 truncate text-body/90">{{ $case->parties_name ?? '—' }}</td>
                            <td class="px-3 py-1.5 truncate text-body/90">{{ $case->category->name ?? '—' }}</td>
                            <td class="px-3 py-1.5 truncate text-body/90">{{ $case->mediator->advocate_name ?? '—' }}</td>
                            <td class="px-3 py-1.5 whitespace-nowrap text-body/90">
                                {{ optional($case->received_date)->format('d-m-y') ?? '—' }}</td>
                            <td class="px-3 py-1.5 whitespace-nowrap text-body/90">
                                {{ optional($case->assigned_date)->format('d-m-y') ?? '—' }}</td>
                            <td class="px-3 py-1.5 whitespace-nowrap text-body/90">
                                {{ optional($case->first_mediation_date)->format('d-m-y') ?? '—' }}</td>
                            <td class="px-3 py-1.5 whitespace-nowrap text-body/90">
                                {{ optional($case->final_result_date)->format('d-m-y') ?? '—' }}</td>
                            <td class="px-3 py-1.5 whitespace-nowrap text-body/90">
                                @if ($sittingList->isNotEmpty())
                                    {{ $sittingList->first() }}
                                    @if ($sittingList->count() > 1)
                                        <span
                                            class="ml-1 inline-block px-1.5 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[10px] font-semibold align-middle">
                                            +{{ $sittingList->count() - 1 }}
                                        </span>
                                    @endif
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="px-3 py-1.5">
                                @php
                                    $badge = match ($case->status) {
                                        'settled' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'unsettled' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        default => 'bg-amber-50 text-amber-700 border-amber-200',
                                    };
                                @endphp
                                <span
                                    class="inline-block px-2 py-0.5 rounded-full text-[11px] font-medium border whitespace-nowrap {{ $badge }}">
                                    {{ ucfirst($case->status) }}
                                </span>
                            </td>
                            <td class="px-3 py-1.5 text-right whitespace-nowrap text-body/90">
                                {{ $case->amount !== null ? number_format($case->amount, 2) : '—' }}
                            </td>
                            <td class="px-3 py-1.5 text-right whitespace-nowrap">
                                <div
                                    class="js-no-toggle flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 focus-within:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.cases.edit', $case) }}" title="Edit"
                                        class="p-1.5 rounded-md text-slate-500 hover:text-maroon hover:bg-maroon/10 transition-colors">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 13.5L19.5 19.5a2.25 2.25 0 01-2.25 2.25L4.5 21.75a2.25 2.25 0 01-2.25-2.25L2.25 6.75A2.25 2.25 0 014.5 4.5l6 0" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.cases.destroy', $case) }}" method="POST"
                                        onsubmit="return confirm('Delete this case?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Delete"
                                            class="p-1.5 rounded-md text-slate-500 hover:text-rose-600 hover:bg-rose-50 transition-colors">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        {{-- Detail row: hidden by default, toggled open by JS. Shows full, untruncated info. --}}
                        <tr id="{{ $detailId }}" class="js-case-detail hidden">
                            <td colspan="14" class="p-0">
                                <div class="bg-slate-50/70 border-t border-b border-border px-8 py-5">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-4">
                                        <div>
                                            <p class="text-[10.5px] font-semibold text-muted uppercase tracking-wide mb-1">
                                                Case No.</p>
                                            <p class="text-sm text-ink whitespace-pre-line break-words">
                                                {{ $case->case_no ?? '—' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[10.5px] font-semibold text-muted uppercase tracking-wide mb-1">
                                                Parties</p>
                                            <p class="text-sm text-ink whitespace-pre-line break-words">
                                                {{ $case->parties_name ?? '—' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[10.5px] font-semibold text-muted uppercase tracking-wide mb-1">
                                                Category</p>
                                            <p class="text-sm text-ink">{{ $case->category->name ?? '—' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[10.5px] font-semibold text-muted uppercase tracking-wide mb-1">
                                                Mediator</p>
                                            <p class="text-sm text-ink">{{ $case->mediator->advocate_name ?? '—' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[10.5px] font-semibold text-muted uppercase tracking-wide mb-1">
                                                Received Date</p>
                                            <p class="text-sm text-ink">
                                                {{ optional($case->received_date)->format('d-m-Y') ?? '—' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[10.5px] font-semibold text-muted uppercase tracking-wide mb-1">
                                                Assigned Date</p>
                                            <p class="text-sm text-ink">
                                                {{ optional($case->assigned_date)->format('d-m-Y') ?? '—' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[10.5px] font-semibold text-muted uppercase tracking-wide mb-1">
                                                First Mediation Date</p>
                                            <p class="text-sm text-ink">
                                                {{ optional($case->first_mediation_date)->format('d-m-Y') ?? '—' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[10.5px] font-semibold text-muted uppercase tracking-wide mb-1">
                                                Final Result Date</p>
                                            <p class="text-sm text-ink">
                                                {{ optional($case->final_result_date)->format('d-m-Y') ?? '—' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[10.5px] font-semibold text-muted uppercase tracking-wide mb-1">
                                                Amount</p>
                                            <p class="text-sm text-ink">
                                                {{ $case->amount !== null ? number_format($case->amount, 2) : '—' }}</p>
                                        </div>
                                        <div class="sm:col-span-2 lg:col-span-3">
                                            <p class="text-[10.5px] font-semibold text-muted uppercase tracking-wide mb-1">
                                                Sitting Dates</p>
                                            @if ($sittingList->isNotEmpty())
                                                <ol class="list-decimal list-inside text-sm text-ink space-y-0.5">
                                                    @foreach ($sittingList as $sittingDate)
                                                        <li>{{ $sittingDate }}</li>
                                                    @endforeach
                                                </ol>
                                            @else
                                                <p class="text-sm text-ink">—</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="14" class="px-4 py-10 text-center text-muted">No cases recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $cases->links() }}
    </div>

    <script>
        document.querySelectorAll('.js-case-row').forEach(function(row) {
            row.addEventListener('click', function(e) {
                // Don't toggle when clicking Edit/Delete inside the row.
                if (e.target.closest('.js-no-toggle')) return;

                const detail = document.getElementById(this.dataset.target);
                const chevron = this.querySelector('.js-case-chevron');
                if (!detail) return;

                detail.classList.toggle('hidden');
                if (chevron) chevron.classList.toggle('rotate-90');
            });
        });
    </script>

@endsection
