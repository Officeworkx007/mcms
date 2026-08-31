@extends('admin.layouts.master')

@section('title', 'Mediators')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-bold text-ink">Mediators</h1>
            <p class="text-muted text-sm mt-1">Manage the list of advocates available for case assignment.</p>
        </div>
        <a href="{{ route('admin.mediators.create') }}"
            class="flex items-center gap-2 bg-maroon hover:bg-maroon/90 text-white text-sm font-semibold px-4 py-2.5 rounded-md transition-colors">
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Add Mediator
        </a>
    </div>

    @if (session('success'))
        <div class="mb-5 rounded-md bg-sage/10 border border-sage/30 text-sage text-sm px-4 py-2.5">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white border border-border rounded-lg shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-panel border-b border-border text-left">
                    <th class="px-4 py-3 font-semibold text-muted uppercase text-xs tracking-wide w-14">S.No</th>
                    <th class="px-4 py-3 font-semibold text-muted uppercase text-xs tracking-wide">Advocate Name</th>
                    <th class="px-4 py-3 font-semibold text-muted uppercase text-xs tracking-wide">Designation</th>
                    <th class="px-4 py-3 font-semibold text-muted uppercase text-xs tracking-wide">Contact No.</th>
                    <th class="px-4 py-3 font-semibold text-muted uppercase text-xs tracking-wide">Email</th>
                    <th class="px-4 py-3 font-semibold text-muted uppercase text-xs tracking-wide">Enrollment No.</th>
                    <th class="px-4 py-3 font-semibold text-muted uppercase text-xs tracking-wide">Experience</th>
                    <th class="px-4 py-3 font-semibold text-muted uppercase text-xs tracking-wide">Status</th>
                    <th class="px-4 py-3 font-semibold text-muted uppercase text-xs tracking-wide text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @forelse ($mediators as $mediator)
                    <tr class="hover:bg-panel/60">
                        <td class="px-4 py-3 text-muted">{{ $mediators->firstItem() + $loop->index }}</td>
                        <td class="px-4 py-3 text-ink font-medium">{{ $mediator->advocate_name ?? '—' }}</td>
                        <td class="px-4 py-3 text-body">{{ $mediator->designation ?? '—' }}</td>
                        <td class="px-4 py-3 text-body">{{ $mediator->contact_no ?? '—' }}</td>
                        <td class="px-4 py-3 text-body">{{ $mediator->email ?? '—' }}</td>
                        <td class="px-4 py-3 text-body">{{ $mediator->enrollment_no ?? '—' }}</td>
                        <td class="px-4 py-3 text-body">{{ $mediator->experience ?? '—' }}</td>
                        <td class="px-4 py-3">
                            @if ($mediator->is_active)
                                <span
                                    class="inline-flex items-center gap-1.5 text-xs font-medium text-sage bg-sage/10 px-2.5 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-sage"></span> Active
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center gap-1.5 text-xs font-medium text-muted bg-black/5 px-2.5 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-muted"></span> Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.mediators.edit', $mediator) }}" class="text-body hover:text-maroon"
                                    title="Edit">
                                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.12 2.12 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('admin.mediators.destroy', $mediator) }}"
                                    onsubmit="return confirm('Remove this mediator? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-body hover:text-maroon" title="Delete">
                                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6 7h12M9 7V5a1 1 0 011-1h4a1 1 0 011 1v2m2 0v13a1 1 0 01-1 1H8a1 1 0 01-1-1V7h10z" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-4 py-10 text-center text-muted text-sm">
                            No mediators added yet. Click "Add Mediator" to create the first one.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $mediators->links() }}
    </div>

@endsection
