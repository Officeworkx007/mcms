@extends('admin.layouts.master')

@section('title', $mediator ? 'Mediator Report' : ($category ?? null ? 'Case-wise Report' : 'Mediation Summary Report'))

@section('content')

    <div class="flex items-center justify-between mb-6 print:hidden">
        <div>
            <a href="{{ route('admin.reports.index') }}" class="text-xs font-medium text-maroon hover:underline">&larr; All
                Reports</a>
            <h1 class="text-base font-bold text-ink mt-0.5">
                @if ($mediator)
                    {{ $mediator->advocate_name }} — Case Report
                @elseif ($category ?? null)
                    {{ $category->name }} — Case-wise Report
                @else
                    Mediation Summary Report
                @endif
            </h1>
            <p class="text-xs text-muted">
                @if ($mediator)
                    Showing all cases ever assigned to {{ $mediator->advocate_name }}.
                @elseif ($category ?? null)
                    Showing every mediator with cases under {{ $category->name }}.
                @elseif (($from ?? null) || ($to ?? null))
                    Showing cases received
                    {{ $from ? \Illuminate\Support\Carbon::parse($from)->format('d-m-Y') : 'the start' }}
                    to {{ $to ? \Illuminate\Support\Carbon::parse($to)->format('d-m-Y') : 'present' }}.
                @else
                    Showing all cases ever recorded.
                @endif
            </p>
        </div>

        <div class="flex items-center gap-2">

            <a href=""
                class="inline-flex items-center gap-1.5 bg-white border border-border hover:bg-slate-50 text-ink text-sm font-semibold px-3.5 py-2 rounded-md transition-colors">
                <svg class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 4.5v11m0 0l-3.5-3.5M12 15.5l3.5-3.5M4.5 17v2a1.5 1.5 0 001.5 1.5h12a1.5 1.5 0 001.5-1.5v-2" />
                </svg>
                PDF
            </a>
            <a href=""
                class="inline-flex items-center gap-1.5 bg-white border border-border hover:bg-slate-50 text-ink text-sm font-semibold px-3.5 py-2 rounded-md transition-colors">
                <svg class="w-4 h-4 text-green-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 4.5v11m0 0l-3.5-3.5M12 15.5l3.5-3.5M4.5 17v2a1.5 1.5 0 001.5 1.5h12a1.5 1.5 0 001.5-1.5v-2" />
                </svg>
                Excel
            </a>
            <a href=""
                class="inline-flex items-center gap-1.5 bg-white border border-border hover:bg-slate-50 text-ink text-sm font-semibold px-3.5 py-2 rounded-md transition-colors">
                <svg class="w-4 h-4 text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 4.5v11m0 0l-3.5-3.5M12 15.5l3.5-3.5M4.5 17v2a1.5 1.5 0 001.5 1.5h12a1.5 1.5 0 001.5-1.5v-2" />
                </svg>
                Word
            </a>
            <button type="button" onclick="window.print()"
                class="bg-maroon hover:bg-maroon/90 text-white text-sm font-semibold px-4 py-2.5 rounded-md transition-colors">
                Print
            </button>
        </div>
    </div>

    <div id="report-printable"
        class="bg-white border border-border rounded-lg shadow-sm overflow-hidden print:border-0 print:shadow-none print:rounded-none">
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead>
                    <tr>
                        <th colspan="5" class="border border-border px-4 py-3 text-center bg-white">
                            <p class="text-base font-bold uppercase tracking-wide text-ink">
                                High Court Mediation Centre, High Court of Manipur
                            </p>
                            <p class="text-sm font-semibold text-ink mt-1">
                                {{ $label }}
                                @if (!$mediator && !($category ?? null) && (($from ?? null) || ($to ?? null)))
                                    &middot;
                                    {{ $from ? \Illuminate\Support\Carbon::parse($from)->format('d.m.Y') : 'Start' }}
                                    to
                                    {{ $to ? \Illuminate\Support\Carbon::parse($to)->format('d.m.Y') : 'Present' }}
                                @endif
                            </p>
                        </th>
                    </tr>
                    <tr class="border-b-2 border-ink text-center text-xs font-bold text-ink uppercase">
                        <th class="border border-border px-3 py-3 text-left align-middle">
                            @if ($category ?? null)
                                Mediator
                            @else
                                Nature / Category of Cases
                            @endif
                        </th>
                        <th class="border border-border px-3 py-3 align-middle">
                            @if ($mediator || ($category ?? null))
                                Cases Referred to Mediator
                            @else
                                Cases Referred to<br>Mediation Centre
                            @endif
                        </th>
                        <th class="border border-border px-3 py-3 align-middle">No. of <br>Settled Cases</th>
                        <th class="border border-border px-3 py-3 align-middle">No. of UnSettled Cases</th>
                        <th class="border border-border px-3 py-3 align-middle">No. of<br>Pending Cases</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $rows = $category ?? null ? $mediators : $categories;
                    @endphp

                    @forelse ($rows as $row)
                        <tr class="text-center font-bold text-ink">
                            <td class="border border-border px-3 py-2.5 text-left">
                                {{ $category ?? null ? $row->advocate_name : $row->name }}
                            </td>
                            <td class="border border-border px-3 py-2.5">
                                {{ $row->cases_count > 0 ? $row->cases_count : '' }}</td>
                            <td class="border border-border px-3 py-2.5">
                                {{ $row->settled_count > 0 ? $row->settled_count : '' }}</td>
                            <td class="border border-border px-3 py-2.5">
                                {{ $row->unsettled_count > 0 ? $row->unsettled_count : '' }}</td>
                            <td class="border border-border px-3 py-2.5">
                                {{ $row->pending_count > 0 ? $row->pending_count : '' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="border border-border px-4 py-8 text-center text-muted">
                                @if ($category ?? null)
                                    No mediators have cases under this category yet.
                                @else
                                    No categories recorded yet.
                                @endif
                            </td>
                        </tr>
                    @endforelse

                    <tr class="text-center font-bold text-ink bg-slate-50">
                        <td class="border border-border px-3 py-3 text-left">Total</td>
                        <td class="border border-border px-3 py-3">{{ $totals['cases_count'] }}</td>
                        <td class="border border-border px-3 py-3">{{ $totals['settled_count'] }}</td>
                        <td class="border border-border px-3 py-3">{{ $totals['unsettled_count'] }}</td>
                        <td class="border border-border px-3 py-3">{{ $totals['pending_count'] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <style>
        @media print {
            @page {
                margin: 1.5cm;
            }

            body * {
                visibility: hidden;
            }

            #report-printable,
            #report-printable * {
                visibility: visible;
            }

            #report-printable {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            /* Force solid, visible grid lines regardless of the border-color
                               CSS variable — some browsers wash out light/variable-based
                               border colors when printing. */
            #report-printable table,
            #report-printable th,
            #report-printable td {
                border: 1px solid #000 !important;
                border-collapse: collapse !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>

    @if ($autoprint)
        <script>
            window.addEventListener('load', () => window.print());
        </script>
    @endif

@endsection
