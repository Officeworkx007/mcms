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
        <div class="mb-5 rounded-md bg-emerald-50 border border-emerald-200 px-4 py-2.5 text-sm text-emerald-700">
            {{ session('status') }}
        </div>
    @endif

    <div class="bg-white border border-border rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr
                        class="border-b border-border text-left text-[11px] font-semibold text-muted uppercase tracking-wider">
                        <th class="px-4 py-3 w-12">S.No</th>
                        <th class="px-4 py-3">Case No.</th>
                        <th class="px-4 py-3">Parties</th>
                        <th class="px-4 py-3">Category</th>
                        <th class="px-4 py-3">Mediator</th>
                        <th class="px-4 py-3">Received</th>
                        <th class="px-4 py-3">Assigned</th>
                        <th class="px-4 py-3">First Sitting</th>
                        <th class="px-4 py-3">Final Result</th>
                        <th class="px-4 py-3">Sitting Dates</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Amount</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cases as $case)
                        <tr class="border-b border-border last:border-0 text-body">
                            <td class="px-4 py-3 text-muted">{{ $cases->firstItem() + $loop->index }}</td>
                            <td class="px-4 py-3 font-medium text-ink whitespace-nowrap">{{ $case->case_no ?? '—' }}</td>
                            <td class="px-4 py-3 max-w-xs">
                                {{ \Illuminate\Support\Str::limit($case->parties_name, 60) ?? '—' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $case->category->name ?? '—' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $case->mediator->advocate_name ?? '—' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                {{ optional($case->received_date)->format('d-m-Y') ?? '—' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                {{ optional($case->assigned_date)->format('d-m-Y') ?? '—' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                {{ optional($case->first_mediation_date)->format('d-m-Y') ?? '—' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                {{ optional($case->final_result_date)->format('d-m-Y') ?? '—' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if (!empty($case->sitting_dates))
                                    {{ collect($case->sitting_dates)->map(fn($d) => \Illuminate\Support\Carbon::parse($d)->format('d-m-Y'))->join(', ') }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $badge = match ($case->status) {
                                        'settled' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'unsettled' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        default => 'bg-amber-50 text-amber-700 border-amber-200',
                                    };
                                @endphp
                                <span
                                    class="inline-block px-2 py-0.5 rounded-full text-xs font-medium border {{ $badge }}">
                                    {{ ucfirst($case->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                {{ $case->amount !== null ? number_format($case->amount, 2) : '—' }}
                            </td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <a href="{{ route('admin.cases.edit', $case) }}"
                                    class="text-maroon hover:underline font-medium">Edit</a>
                                <form action="{{ route('admin.cases.destroy', $case) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Delete this case?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-rose-600 hover:underline font-medium ml-3">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="px-4 py-10 text-center text-muted">No cases recorded yet.</td>
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
