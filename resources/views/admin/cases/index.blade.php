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
                <p class="text-xs text-muted">All mediation cases on record.</p>
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
            <table class="w-full text-sm table-fixed border-collapse">
                <thead>
                    <tr
                        class="border-b border-border text-left text-[11px] font-semibold text-muted uppercase tracking-wider bg-slate-50/60">
                        <th class="px-4 py-3 w-10">S.No</th>
                        <th class="px-4 py-3 w-52">Case No.</th>
                        <th class="px-4 py-3 w-48">Parties</th>
                        <th class="px-4 py-3 w-28">Category</th>
                        <th class="px-4 py-3 w-28">Mediator</th>
                        <th class="px-4 py-3 w-20">Received</th>
                        <th class="px-4 py-3 w-20">Assigned</th>
                        <th class="px-4 py-3 w-20">First Sitting</th>
                        <th class="px-4 py-3 w-20">Final Result</th>
                        <th class="px-4 py-3 w-24">Sitting Dates</th>
                        <th class="px-4 py-3 w-20">Status</th>
                        <th class="px-4 py-3 w-20">Amount</th>
                        <th class="px-4 py-3 w-32 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse ($cases as $case)
                        <tr
                            class="align-top text-body odd:bg-white even:bg-slate-50/40 hover:bg-maroon/5 transition-colors">
                            <td class="px-4 py-4 text-muted">{{ $cases->firstItem() + $loop->index }}</td>
                            <td class="px-4 py-4 font-semibold text-ink leading-snug whitespace-pre-line break-words">
                                {{ $case->case_no ?? '—' }}</td>
                            <td class="px-4 py-4 leading-snug whitespace-pre-line break-words text-body/90">
                                {{ \Illuminate\Support\Str::limit($case->parties_name, 150) }}</td>
                            <td class="px-4 py-4 leading-snug break-words text-body/90">{{ $case->category->name ?? '—' }}
                            </td>
                            <td class="px-4 py-4 leading-snug break-words text-body/90">
                                {{ $case->mediator->advocate_name ?? '—' }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-body/90">
                                {{ optional($case->received_date)->format('d-m-Y') ?? '—' }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-body/90">
                                {{ optional($case->assigned_date)->format('d-m-Y') ?? '—' }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-body/90">
                                {{ optional($case->first_mediation_date)->format('d-m-Y') ?? '—' }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-body/90">
                                {{ optional($case->final_result_date)->format('d-m-Y') ?? '—' }}</td>
                            <td class="px-4 py-4">
                                @if (!empty($case->sitting_dates))
                                    <ol class="list-decimal list-inside leading-relaxed text-body/90">
                                        @foreach ($case->sitting_dates as $sittingDate)
                                            <li class="whitespace-nowrap">
                                                {{ \Illuminate\Support\Carbon::parse($sittingDate)->format('d-m-Y') }}
                                            </li>
                                        @endforeach
                                    </ol>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                @php
                                    $badge = match ($case->status) {
                                        'settled' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'unsettled' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        default => 'bg-amber-50 text-amber-700 border-amber-200',
                                    };
                                @endphp
                                <span
                                    class="inline-block px-2 py-0.5 rounded-full text-xs font-medium border whitespace-nowrap {{ $badge }}">
                                    {{ ucfirst($case->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-body/90">
                                {{ $case->amount !== null ? number_format($case->amount, 2) : '—' }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.cases.edit', $case) }}"
                                        class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold text-maroon border border-maroon/20 bg-maroon/5 hover:bg-maroon/10 transition-colors">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.cases.destroy', $case) }}" method="POST"
                                        onsubmit="return confirm('Delete this case?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold text-rose-600 border border-rose-200 bg-rose-50 hover:bg-rose-100 transition-colors">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13" class="px-4 py-10 text-center text-muted">No cases recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $cases->links() }}
    </div>

@endsection
