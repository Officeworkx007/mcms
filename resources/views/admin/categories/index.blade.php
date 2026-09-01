@extends('admin.layouts.master')

@section('title', 'Categories')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-maroon shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7" />
            </svg>
            <div>
                <h1 class="text-base font-bold text-ink">Categories</h1>
                <p class="text-xs text-muted">Nature of case, used for filtering and reports.</p>
            </div>
        </div>
        <a href="{{ route('admin.categories.create') }}"
            class="bg-maroon hover:bg-maroon/90 text-white text-sm font-semibold px-4 py-2.5 rounded-md transition-colors">
            + Add Category
        </a>
    </div>

    @if (session('status'))
        <div class="mb-5 rounded-md bg-emerald-50 border border-emerald-200 px-4 py-2.5 text-sm text-emerald-700">
            {{ session('status') }}
        </div>
    @endif

    <form method="GET" action="{{ route('admin.categories.index') }}" class="mb-4 flex items-center gap-3">
        <label for="mediator_id" class="text-xs font-semibold text-muted uppercase tracking-wide">Filter by mediator</label>
        <select id="mediator_id" name="mediator_id" onchange="this.form.submit()"
            class="px-3 py-2 border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-maroon/20">
            <option value="">All mediators (overall)</option>
            @foreach ($mediators as $mediator)
                <option value="{{ $mediator->id }}" @selected($mediatorId == $mediator->id)>
                    {{ $mediator->advocate_name }}
                </option>
            @endforeach
        </select>

        @if ($mediatorId)
            <a href="{{ route('admin.categories.index') }}" class="text-sm font-medium text-maroon hover:underline">Clear
                filter</a>
        @endif
    </form>

    <div class="bg-white border border-border rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr
                        class="border-b border-border text-left text-[11px] font-semibold text-muted uppercase tracking-wider">
                        <th class="px-4 py-3 w-12">S.No</th>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Description</th>
                        <th class="px-4 py-3">Cases</th>
                        <th class="px-4 py-3">Pending</th>
                        <th class="px-4 py-3">Settled</th>
                        <th class="px-4 py-3">Unsettled</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr class="border-b border-border last:border-0 text-body">
                            <td class="px-4 py-3 text-muted">{{ $categories->firstItem() + $loop->index }}</td>
                            <td class="px-4 py-3 font-medium text-ink whitespace-nowrap">{{ $category->name }}</td>
                            <td class="px-4 py-3 max-w-md">
                                {{ \Illuminate\Support\Str::limit($category->description, 80) ?? '—' }}</td>
                            <td class="px-4 py-3 font-medium">{{ $category->cases_count }}</td>
                            <td class="px-4 py-3 text-amber-700">{{ $category->pending_count }}</td>
                            <td class="px-4 py-3 text-emerald-700">{{ $category->settled_count }}</td>
                            <td class="px-4 py-3 text-rose-700">{{ $category->unsettled_count }}</td>
                            <td class="px-4 py-3">
                                @if ($category->is_active)
                                    <span
                                        class="inline-block px-2 py-0.5 rounded-full text-xs font-medium border bg-emerald-50 text-emerald-700 border-emerald-200">Active</span>
                                @else
                                    <span
                                        class="inline-block px-2 py-0.5 rounded-full text-xs font-medium border bg-slate-50 text-slate-500 border-slate-200">Inactive</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <a href="{{ route('admin.categories.edit', $category) }}"
                                    class="text-maroon hover:underline font-medium">Edit</a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                    class="inline"
                                    onsubmit="return confirm('Delete this category? Cases using it will keep their other details, but lose this category tag.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-rose-600 hover:underline font-medium ml-3">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-10 text-center text-muted">No categories yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $categories->links() }}
    </div>

@endsection
