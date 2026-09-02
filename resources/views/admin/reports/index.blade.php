@extends('admin.layouts.master')

@section('title', 'Reports')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-maroon shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" />
            </svg>
            <div>
                <h1 class="text-base font-bold text-ink">Reports</h1>
                <p class="text-xs text-muted">Generate reports for a date range, then view or print them anytime.</p>
            </div>
        </div>
    </div>

    {{-- Report type cards — click to open the generate modal --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
        @foreach ($reportTypes as $key => $report)
            <button type="button"
                onclick="openReportModal('{{ $key }}', @js($report['title']), @js($report['description']))"
                class="text-left bg-white border border-border rounded-lg shadow-sm overflow-hidden flex flex-col hover:border-maroon/40 hover:shadow-md transition-all">
                <div class="px-5 pt-5 pb-4 border-b border-border flex-1">
                    <div class="flex items-start gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-maroon/10 text-maroon">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" />
                            </svg>
                        </span>
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-ink">{{ $report['title'] }}</p>
                            <p class="mt-1 text-xs text-muted leading-relaxed">{{ $report['description'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="px-5 py-3 flex items-center justify-between">
                    <span class="text-xs font-semibold text-maroon">Generate report →</span>
                </div>
            </button>
        @endforeach
    </div>

    {{-- Recently generated reports --}}
    <div class="bg-white border border-border rounded-lg shadow-sm overflow-hidden">
        <div class="px-5 py-3 border-b border-border bg-slate-50/60">
            <p class="text-sm font-bold text-ink">Recently Generated Reports</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr
                        class="border-b border-border text-left text-[11px] font-semibold text-muted uppercase tracking-wider bg-slate-50/40">
                        <th class="px-4 py-3 w-10">#</th>
                        <th class="px-4 py-3">Report</th>
                        <th class="px-4 py-3">Period</th>
                        <th class="px-4 py-3">Generated On</th>
                        <th class="px-4 py-3 w-44 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse ($recentReports as $index => $report)
                        <tr class="text-body odd:bg-white even:bg-slate-50/40 hover:bg-maroon/5 transition-colors">
                            <td class="px-4 py-3 text-muted">{{ $index + 1 }}</td>
                            <td class="px-4 py-3">
                                <p class="font-semibold text-ink">{{ $report->title }}</p>
                                @if ($report->label)
                                    <p class="text-xs text-muted">{{ $report->label }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-body/90">
                                @if ($report->from_date || $report->to_date)
                                    {{ optional($report->from_date)->format('d-m-Y') ?? 'Start' }}
                                    &rarr;
                                    {{ optional($report->to_date)->format('d-m-Y') ?? 'Present' }}
                                @else
                                    All time
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-body/90">
                                {{ $report->created_at->format('d-m-Y h:i A') }}
                            </td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ $report->url }}"
                                        class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold text-maroon border border-maroon/20 bg-maroon/5 hover:bg-maroon/10 transition-colors">
                                        View
                                    </a>
                                    <a href="{{ $report->url }}{{ str_contains($report->url, '?') ? '&' : '?' }}autoprint=1"
                                        class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold text-slate-600 border border-slate-200 bg-slate-50 hover:bg-slate-100 transition-colors">
                                        Print
                                    </a>
                                    <form action="{{ route('admin.reports.destroy', $report->id) }}" method="POST"
                                        onsubmit="return confirm('Delete this report? This cannot be undone.');"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold text-red-600 border border-red-200 bg-red-50 hover:bg-red-100 transition-colors">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-muted">
                                No reports generated yet. Click a card above to generate your first one.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Shared "Generate Report" modal --}}
    <div id="reportModal" class="hidden fixed inset-0 z-50 items-start justify-center pt-16 px-4">
        <div class="absolute inset-0 bg-black/40" onclick="closeReportModal()"></div>

        <div class="relative bg-white w-full max-w-md rounded-lg shadow-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-border flex items-start justify-between">
                <div class="min-w-0 pr-3">
                    <p id="modal-report-title" class="text-sm font-bold text-ink">Report Title</p>
                    <p id="modal-report-desc" class="mt-1 text-xs text-muted leading-relaxed">Report description</p>
                </div>
                <button type="button" onclick="closeReportModal()"
                    class="shrink-0 w-7 h-7 rounded-full flex items-center justify-center text-muted hover:bg-slate-100">
                    &times;
                </button>
            </div>

            <form action="{{ route('admin.reports.generate') }}" method="POST" class="px-5 py-4 space-y-3">
                @csrf
                <input type="hidden" id="modal-report-key" name="report_key" value="">

                <div>
                    <label class="block text-[10.5px] font-semibold text-muted uppercase tracking-wide mb-1">
                        Phase Label <span class="normal-case font-normal text-muted/70">(optional)</span>
                    </label>
                    <input type="text" name="label" placeholder="e.g. Mediation 2.0"
                        class="w-full px-3 py-2 border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-maroon/20">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label
                            class="block text-[10.5px] font-semibold text-muted uppercase tracking-wide mb-1">From</label>
                        <input type="date" name="from"
                            class="w-full px-3 py-2 border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-maroon/20">
                    </div>
                    <div>
                        <label class="block text-[10.5px] font-semibold text-muted uppercase tracking-wide mb-1">To</label>
                        <input type="date" name="to"
                            class="w-full px-3 py-2 border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-maroon/20">
                    </div>
                </div>
                <p class="text-[11px] text-muted -mt-1">Leave both blank to include all cases ever recorded.</p>

                <div class="flex items-center gap-2 pt-2">
                    <button type="button" onclick="closeReportModal()"
                        class="flex-1 border border-border text-ink text-sm font-semibold py-2.5 rounded-md hover:bg-slate-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="flex-1 bg-maroon hover:bg-maroon/90 text-white text-sm font-semibold py-2.5 rounded-md transition-colors">
                        Generate Report
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function openReportModal(key, title, desc) {
            document.getElementById('modal-report-key').value = key;
            document.getElementById('modal-report-title').textContent = title;
            document.getElementById('modal-report-desc').textContent = desc;

            const modal = document.getElementById('reportModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeReportModal() {
            const modal = document.getElementById('reportModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeReportModal();
        });
    </script>
@endpush
