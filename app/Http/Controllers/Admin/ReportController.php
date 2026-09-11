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

    public function export(Request $request, string $type, string $format)
    {
        abort_unless(in_array($type, ['mediation-summary', 'mediator-summary', 'case-summary'], true), 404);
        abort_unless(in_array($format, ['excel', 'word'], true), 404);

        $data = match ($type) {
            'mediation-summary' => $this->mediationSummaryExportData($request),
            'mediator-summary' => $this->mediatorSummaryExportData($request),
            'case-summary' => $this->caseSummaryExportData($request),
        };

        $filename = \Illuminate\Support\Str::slug($data['label'] ?? $type) . '-' . now()->format('Ymd');

        return $format === 'excel'
            ? $this->downloadXlsx($data, $filename)
            : $this->downloadDocx($data, $filename);
    }

    private function mediationSummaryExportData(Request $request): array
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $label = $request->input('label', 'Mediation Summary');

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
        ])->get();

        $totals = [
            'cases_count' => $categories->sum('cases_count'),
            'settled_count' => $categories->sum('settled_count'),
            'unsettled_count' => $categories->sum('unsettled_count'),
            'pending_count' => $categories->sum('pending_count'),
        ];

        $subtitle = $label;
        if ($from || $to) {
            $subtitle .= ' · '
                . ($from ? \Illuminate\Support\Carbon::parse($from)->format('d.m.Y') : 'Start')
                . ' to '
                . ($to ? \Illuminate\Support\Carbon::parse($to)->format('d.m.Y') : 'Present');
        }

        return [
            'pageTitle' => 'High Court Mediation Centre, High Court of Manipur',
            'subtitle' => $subtitle,
            'firstColHeader' => 'Nature / Category of Cases',
            'secondColHeader' => 'Cases Referred to Mediation Centre',
            'rows' => $categories->map(fn($c) => [
                $c->name,
                $c->cases_count,
                $c->settled_count,
                $c->unsettled_count,
                $c->pending_count,
            ])->all(),
            'totals' => $totals,
            'label' => $label,
        ];
    }

    private function mediatorSummaryExportData(Request $request): array
    {
        $mediatorId = $request->input('mediator_id');
        $mediator = Mediator::findOrFail($mediatorId);
        $label = $request->input('label', $mediator->advocate_name);

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

        return [
            'pageTitle' => 'High Court Mediation Centre, High Court of Manipur',
            'subtitle' => $label,
            'firstColHeader' => 'Nature / Category of Cases',
            'secondColHeader' => 'Cases Referred to Mediator',
            'rows' => $categories->map(fn($c) => [
                $c->name,
                $c->cases_count,
                $c->settled_count,
                $c->unsettled_count,
                $c->pending_count,
            ])->all(),
            'totals' => $totals,
            'label' => $label,
        ];
    }

    private function caseSummaryExportData(Request $request): array
    {
        $categoryId = $request->input('case_category_id');
        $category = CaseCategory::findOrFail($categoryId);
        $label = $request->input('label', $category->name);

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

        return [
            'pageTitle' => 'High Court Mediation Centre, High Court of Manipur',
            'subtitle' => $label,
            'firstColHeader' => 'Mediator',
            'secondColHeader' => 'Cases Referred to Mediator',
            'rows' => $mediators->map(fn($m) => [
                $m->advocate_name,
                $m->cases_count,
                $m->settled_count,
                $m->unsettled_count,
                $m->pending_count,
            ])->all(),
            'totals' => $totals,
            'label' => $label,
        ];
    }

    private function downloadXlsx(array $data, string $filename)
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = [
            $data['firstColHeader'],
            $data['secondColHeader'],
            'No. of Settled Cases',
            'No. of UnSettled Cases',
            'No. of Pending Cases',
        ];

        $sheet->mergeCells('A1:E1');
        $sheet->setCellValue('A1', $data['pageTitle']);
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(13);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

        $sheet->mergeCells('A2:E2');
        $sheet->setCellValue('A2', $data['subtitle']);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');

        $sheet->fromArray($headers, null, 'A3');
        $sheet->getStyle('A3:E3')->getFont()->setBold(true);
        $sheet->getStyle('A3:E3')->getAlignment()->setHorizontal('center')->setWrapText(true);

        $row = 4;
        foreach ($data['rows'] as $r) {
            $sheet->setCellValue("A{$row}", $r[0]);
            $sheet->setCellValue("B{$row}", $r[1] ?: '');
            $sheet->setCellValue("C{$row}", $r[2] ?: '');
            $sheet->setCellValue("D{$row}", $r[3] ?: '');
            $sheet->setCellValue("E{$row}", $r[4] ?: '');
            $sheet->getStyle("B{$row}:E{$row}")->getAlignment()->setHorizontal('center');
            $row++;
        }

        $sheet->setCellValue("A{$row}", 'Total');
        $sheet->setCellValue("B{$row}", $data['totals']['cases_count']);
        $sheet->setCellValue("C{$row}", $data['totals']['settled_count']);
        $sheet->setCellValue("D{$row}", $data['totals']['unsettled_count']);
        $sheet->setCellValue("E{$row}", $data['totals']['pending_count']);
        $sheet->getStyle("A{$row}:E{$row}")->getFont()->setBold(true);
        $sheet->getStyle("B{$row}:E{$row}")->getAlignment()->setHorizontal('center');

        $lastRow = $row;
        $sheet->getStyle("A1:E{$lastRow}")
            ->getBorders()->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename . '.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    private function downloadDocx(array $data, string $filename)
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection(['orientation' => 'portrait']);

        $section->addText($data['pageTitle'], ['bold' => true, 'size' => 14], ['alignment' => 'center']);
        $section->addText($data['subtitle'], ['bold' => true, 'size' => 11], ['alignment' => 'center', 'spaceAfter' => 200]);

        $phpWord->addTableStyle('ReportTable', [
            'borderSize' => 6,
            'borderColor' => '000000',
            'cellMargin' => 80,
        ]);
        $table = $section->addTable('ReportTable');

        $headers = [
            $data['firstColHeader'],
            $data['secondColHeader'],
            'No. of Settled Cases',
            'No. of UnSettled Cases',
            'No. of Pending Cases',
        ];

        $table->addRow();
        foreach ($headers as $i => $h) {
            $table->addCell($i === 0 ? 3000 : 1600)->addText($h, ['bold' => true], ['alignment' => 'center']);
        }

        foreach ($data['rows'] as $r) {
            $table->addRow();
            $table->addCell(3000)->addText($r[0], ['bold' => true], ['alignment' => 'left']);
            foreach (array_slice($r, 1) as $val) {
                $table->addCell(1600)->addText((string) ($val ?: ''), ['bold' => true], ['alignment' => 'center']);
            }
        }

        $table->addRow();
        $table->addCell(3000)->addText('Total', ['bold' => true], ['alignment' => 'left']);
        foreach (['cases_count', 'settled_count', 'unsettled_count', 'pending_count'] as $key) {
            $table->addCell(1600)->addText((string) $data['totals'][$key], ['bold' => true], ['alignment' => 'center']);
        }

        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename . '.docx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ]);
    }
}
