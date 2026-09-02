<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CaseCategory;
use App\Models\Mediator;
use App\Models\MediationCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CaseController extends Controller
{
    public function index(): View
    {
        $cases = MediationCase::with(['mediator', 'category'])
            ->oldest()
            ->paginate(40);

        return view('admin.cases.index', compact('cases'));
    }

    public function create(): View
    {
        return view('admin.cases.create', $this->dropdownData());
    }

    public function store(Request $request): RedirectResponse
    {
        MediationCase::create($this->validated($request));

        return redirect()
            ->route('admin.cases.index')
            ->with('status', 'Case created.');
    }

    public function edit(MediationCase $case): View
    {
        return view('admin.cases.edit', array_merge(
            ['case' => $case],
            $this->dropdownData()
        ));
    }

    public function update(Request $request, MediationCase $case): RedirectResponse
    {
        $case->update($this->validated($request));

        return redirect()
            ->route('admin.cases.index')
            ->with('status', 'Case updated.');
    }

    public function destroy(MediationCase $case): RedirectResponse
    {
        $case->delete();

        return back()->with('status', 'Case deleted.');
    }

    /**
     * Mediator/category options shared by the create and edit forms.
     */
    private function dropdownData(): array
    {
        return [
            'mediators' => Mediator::where('is_active', true)
                ->orderBy('advocate_name')
                ->get(),
            'categories' => CaseCategory::where('is_active', true)
                ->orderBy('name')
                ->get(),
        ];
    }

    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'case_no' => ['nullable', 'string', 'max:255'],
            'parties_name' => ['nullable', 'string'],
            'case_category_id' => ['nullable', 'exists:case_categories,id'],
            'mediator_id' => ['nullable', 'exists:mediators,id'],
            'first_mediation_date' => ['nullable', 'date'],
            'final_result_date' => ['nullable', 'date'],
            'received_date' => ['nullable', 'date'],
            'assigned_date' => ['nullable', 'date'],
            'sitting_dates' => ['nullable', 'array'],
            'sitting_dates.*' => ['nullable', 'date'],
            'status' => ['required', 'in:pending,settled,unsettled'],
            // Only mandatory once the case is settled/unsettled — matches
            // the form's conditional "amount given to mediator" field.
            'amount' => ['nullable', 'numeric', 'required_if:status,settled,unsettled'],
        ]);

        // Drop blank rows left over from the add/remove sitting-date repeater.
        $validated['sitting_dates'] = array_values(array_filter(
            $validated['sitting_dates'] ?? []
        ));

        return $validated;
    }
}
