<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CaseCategory;
use App\Models\Mediator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CaseCategoryController extends Controller
{
    public function index(Request $request): View
    {
        $mediatorId = $request->input('mediator_id');

        // Each count below respects the mediator filter when one is
        // selected, so all five totals (cases/pending/settled/unsettled)
        // stay consistent with each other.
        $filterByMediator = function ($query) use ($mediatorId) {
            if ($mediatorId) {
                $query->where('mediator_id', $mediatorId);
            }
        };

        $categories = CaseCategory::withCount([
            'cases as cases_count' => $filterByMediator,
            'cases as pending_count' => function ($query) use ($filterByMediator) {
                $filterByMediator($query);
                $query->where('status', 'pending');
            },
            'cases as settled_count' => function ($query) use ($filterByMediator) {
                $filterByMediator($query);
                $query->where('status', 'settled');
            },
            'cases as unsettled_count' => function ($query) use ($filterByMediator) {
                $filterByMediator($query);
                $query->where('status', 'unsettled');
            },
        ])
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        $mediators = Mediator::where('is_active', true)
            ->orderBy('advocate_name')
            ->get();

        return view('admin.categories.index', compact('categories', 'mediators', 'mediatorId'));
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        CaseCategory::create($this->validated($request));

        return redirect()
            ->route('admin.categories.index')
            ->with('status', 'Category created.');
    }

    public function edit(CaseCategory $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, CaseCategory $category): RedirectResponse
    {
        $category->update($this->validated($request));

        return redirect()
            ->route('admin.categories.index')
            ->with('status', 'Category updated.');
    }

    public function destroy(CaseCategory $category): RedirectResponse
    {
        // Cases referencing this category have case_category_id set to
        // null automatically (nullOnDelete on the FK), they aren't deleted.
        $category->delete();

        return back()->with('status', 'Category deleted.');
    }

    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        // Checkbox: unchecked simply isn't sent, so read it explicitly
        // rather than validating its presence.
        $validated['is_active'] = $request->boolean('is_active');

        return $validated;
    }
}
