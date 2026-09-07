<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CaseCategory;
use App\Models\ReportLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Central registry of available report types. Add a new entry here
     * whenever a new report is built — the hub cards and the generate
     * action both read from this list, so nothing else needs to change.
     */
    private function reportTypes(): array
    {
        return [
            'mediation-summary' => [
                'title' => 'Mediation Summary Report',
                'description' => 'Case counts by category — referred, settled, unsettled, and pending — for a chosen date range or mediation phase.',
                'route_name' => 'admin.reports.mediation-summary',
            ],
        ];
    }

    public function index(): View
    {
        $reportTypes = $this->reportTypes();

        $recentReports = ReportLog::latest()->take(20)->get();

        return view('admin.reports.index', compact('reportTypes', 'recentReports'));
    }

    /**
     * Logs a report generation (type, label, date range, and the direct
     * link to view it) then redirects straight to the report itself.
     */
    public function generate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'report_key' => ['required', 'string'],
            'label' => ['nullable', 'string', 'max:100'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        $reportTypes = $this->reportTypes();
        $reportKey = $validated['report_key'];

        abort_unless(isset($reportTypes[$reportKey]), 404);

        $meta = $reportTypes[$reportKey];

        $params = array_filter([
            'label' => $validated['label'] ?? null,
            'from' => $validated['from'] ?? null,
            'to' => $validated['to'] ?? null,
        ]);

        $url = route($meta['route_name'], $params);

        ReportLog::create([
            'report_key' => $reportKey,
            'title' => $meta['title'],
            'label' => $validated['label'] ?? null,
            'from_date' => $validated['from'] ?? null,
            'to_date' => $validated['to'] ?? null,
            'url' => $url,
        ]);

        return redirect($url);
    }

    public function mediationSummary(Request $request): View
    {
        // Each mediation phase (2.0, 3.0, ...) covers a date range rather
        // than a single point in time, so filter by a From/To window on
        // received_date. Either end can be left blank to leave that side
        // open (e.g. From only = "everything since this date").
        $from = $request->input('from');
        $to = $request->input('to');
        $label = $request->input('label', 'Mediation Summary');
        $autoprint = $request->boolean('autoprint');

        $withinRange = function ($query) use ($from, $to) {
            if ($from) {
                $query->whereDate('received_date', '>=', $from);
            }
            if ($to) {
                $query->whereDate('received_date', '<=', $to);
            }
        };

        $categories = CaseCategory::withCount([
            'cases as cases_count' => $withinRange,
            'cases as pending_count' => function ($query) use ($withinRange) {
                $withinRange($query);
                $query->where('status', 'pending');
            },
            'cases as settled_count' => function ($query) use ($withinRange) {
                $withinRange($query);
                $query->where('status', 'settled');
            },
            'cases as unsettled_count' => function ($query) use ($withinRange) {
                $withinRange($query);
                $query->where('status', 'unsettled');
            },
        ])
            ->get();

        $totals = [
            'cases_count' => $categories->sum('cases_count'),
            'settled_count' => $categories->sum('settled_count'),
            'unsettled_count' => $categories->sum('unsettled_count'),
            'pending_count' => $categories->sum('pending_count'),
        ];

        return view('admin.reports.mediation-summary', compact('categories', 'totals', 'from', 'to', 'label', 'autoprint'));
    }

    public function destroy(ReportLog $report): RedirectResponse
    {
        $report->delete();

        return redirect()->route('admin.reports.index')->with('success', 'Report removed.');
    }
}
