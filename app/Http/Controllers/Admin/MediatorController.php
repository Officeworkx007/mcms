<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mediator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MediatorController extends Controller
{
    public function index(): View
    {
        $mediators = Mediator::query()
            ->selectRaw("mediators.*,
            CASE
                WHEN designation LIKE '%senior%' THEN 1
                WHEN designation LIKE '%advocate%' THEN 2
                ELSE 3
            END AS designation_rank
        ")
            ->orderBy('designation_rank')
            ->orderByRaw('CAST(experience AS UNSIGNED) DESC')
            ->paginate(10);

        return view('admin.mediators.index', compact('mediators'));
    }

    public function create(): View
    {
        return view('admin.mediators.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'advocate_name'  => ['nullable', 'string', 'max:255'],
            'designation'    => ['nullable', 'string', 'max:255'],
            'contact_no'     => ['nullable', 'string', 'max:50'],
            'email'          => ['nullable', 'email', 'max:255'],
            'enrollment_no'  => ['nullable', 'string', 'max:255'],
            'experience'     => ['nullable', 'string', 'max:100'],
        ]);

        Mediator::create($validated);

        return redirect()->route('admin.mediators.index')
            ->with('success', 'Mediator added successfully.');
    }

    public function edit(Mediator $mediator): View
    {
        return view('admin.mediators.edit', compact('mediator'));
    }

    public function update(Request $request, Mediator $mediator): RedirectResponse
    {
        $validated = $request->validate([
            'advocate_name'  => ['nullable', 'string', 'max:255'],
            'designation'    => ['nullable', 'string', 'max:255'],
            'contact_no'     => ['nullable', 'string', 'max:20'],
            'email'          => ['nullable', 'email', 'max:255'],
            'enrollment_no'  => ['nullable', 'string', 'max:255'],
            'experience'     => ['nullable', 'string', 'max:100'],
            'is_active'      => ['nullable', 'boolean'],
        ]);

        // Checkbox won't be present in request if unchecked — default to false explicitly
        $validated['is_active'] = $request->boolean('is_active');

        $mediator->update($validated);

        return redirect()->route('admin.mediators.index')
            ->with('success', 'Mediator updated successfully.');
    }

    public function destroy(Mediator $mediator): RedirectResponse
    {
        $mediator->delete();

        return redirect()->route('admin.mediators.index')
            ->with('success', 'Mediator removed.');
    }
}
