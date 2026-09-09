<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CaseCategory;
use App\Models\ReportLog;
use App\Models\Mediator;
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
                'fields' => 'range', // date-range modal
            ],
            'mediator-summary' => [
                'title' => 'Mediator-wise Report',
                'description' => 'Case counts by category for a single mediator — all cases ever assigned to them, no date range.',
                'route_name' => 'admin.reports.mediator-summary',
                'fields' => 'mediator', // mediator-dropdown modal
            ],
            'case-summary' => [
                'title' => 'Case-wise Report',
                'description' => 'Each mediator\'s case counts within a single category — settled, unsettled, and pending, for all mediators assigned under that category.',
                'route_name' => 'admin.reports.case-summary',
                'fields' => 'category', // category-dropdown modal
            ],
        ];
    }

    public function index(): View
    {
        $reportTypes = $this->reportTypes();

        $recentReports = ReportLog::latest()->take(20)->get();

        $mediators = Mediator::where('is_active', true)
            ->orderBy('advocate_name')
            ->get();

        $categories = CaseCategory::orderBy('sort_order')->get();

        return view('admin.reports.index', compact('reportTypes', 'recentReports', 'mediators', 'categories'));
    }

    /**
     * Logs a report generation (type, label, date range, and the direct
     * link to view it) then redirects straight to the report itself.
     */
    public function generate(Request $request): RedirectResponse
    {
        $reportTypes = $this->reportTypes();
        $reportKey = $request->input('report_key');

        abort_unless(isset($reportTypes[$reportKey]), 404);

        $meta = $reportTypes[$reportKey];

        $validated = $request->validate([
            'report_key' => ['required', 'string'],
            'label' => ['nullable', 'string', 'max:100'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
            'mediator_id' => [
                $meta['fields'] === 'mediator' ? 'required' : 'nullable',
                'integer',
                'exists:mediators,id',
            ],
            'case_category_id' => [
                $meta['fields'] === 'category' ? 'required' : 'nullable',
                'integer',
                'exists:case_categories,id',
            ],
        ]);

        $label = $validated['label'] ?? null;

        if ($meta['fields'] === 'mediator') {
            $mediator = Mediator::findOrFail($validated['mediator_id']);
            $label = $mediator->advocate_name;
        }

        if ($meta['fields'] === 'category') {
            $category = CaseCategory::findOrFail($validated['case_category_id']);
            $label = $category->name;
        }

        $params = array_filter([
            'label' => $label,
            'from' => $validated['from'] ?? null,
            'to' => $validated['to'] ?? null,
            'mediator_id' => $validated['mediator_id'] ?? null,
            'case_category_id' => $validated['case_category_id'] ?? null,
        ]);

        $url = route($meta['route_name'], $params);

        ReportLog::create([
            'report_key' => $reportKey,
            'title' => $meta['title'],
            'label' => $label,
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
        $mediator = null;
        $category = null;

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

        return view('admin.reports.mediation-summary', compact('categories', 'totals', 'from', 'to', 'label', 'autoprint', 'mediator'));
    }

    public function mediatorSummary(Request $request): View
    {
        $mediatorId = $request->input('mediator_id');
        $mediator = Mediator::findOrFail($mediatorId);

        $label = $request->input('label', $mediator->advocate_name);
        $autoprint = $request->boolean('autoprint');

        $scopedToMediator = function ($query) use ($mediatorId) {
            $query->where('mediator_id', $mediatorId);
        };

        $categories = CaseCategory::withCount([
            'cases as cases_count' => $scopedToMediator,
            'cases as pending_count' => function ($query) use ($scopedToMediator) {
                $scopedToMediator($query);
                $query->where('status', 'pending');
            },
            'cases as settled_count' => function ($query) use ($scopedToMediator) {
                $scopedToMediator($query);
                $query->where('status', 'settled');
            },
            'cases as unsettled_count' => function ($query) use ($scopedToMediator) {
                $scopedToMediator($query);
                $query->where('status', 'unsettled');
            },
        ])->get();

        $totals = [
            'cases_count' => $categories->sum('cases_count'),
            'settled_count' => $categories->sum('settled_count'),
            'unsettled_count' => $categories->sum('unsettled_count'),
            'pending_count' => $categories->sum('pending_count'),
        ];

        $category = null;

        return view('admin.reports.mediation-summary', compact('categories', 'totals', 'mediator', 'label', 'autoprint'));
    }

    public function caseSummary(Request $request): View
    {
        $categoryId = $request->input('case_category_id');
        $category = CaseCategory::findOrFail($categoryId);
        $mediator = null;

        $label = $request->input('label', $category->name);
        $autoprint = $request->boolean('autoprint');

        $scopedToCategory = function ($query) use ($categoryId) {
            $query->where('case_category_id', $categoryId);
        };

        $mediators = Mediator::withCount([
            'cases as cases_count' => $scopedToCategory,
            'cases as pending_count' => function ($query) use ($scopedToCategory) {
                $scopedToCategory($query);
                $query->where('status', 'pending');
            },
            'cases as settled_count' => function ($query) use ($scopedToCategory) {
                $scopedToCategory($query);
                $query->where('status', 'settled');
            },
            'cases as unsettled_count' => function ($query) use ($scopedToCategory) {
                $scopedToCategory($query);
                $query->where('status', 'unsettled');
            },
        ])->get();

        $totals = [
            'cases_count' => $mediators->sum('cases_count'),
            'settled_count' => $mediators->sum('settled_count'),
            'unsettled_count' => $mediators->sum('unsettled_count'),
            'pending_count' => $mediators->sum('pending_count'),
        ];

        return view(
            'admin.reports.mediation-summary',
            compact('mediators', 'totals', 'category', 'mediator', 'label', 'autoprint')
        );
    }

    public function destroy(ReportLog $report): RedirectResponse
    {
        $report->delete();

        return redirect()->route('admin.reports.index')->with('success', 'Report removed.');
    }
}
