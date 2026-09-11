@extends('admin.layouts.master')

@section('title', 'Roles')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-maroon shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
            <div>
                <h1 class="text-base font-bold text-ink">Roles</h1>
                <p class="text-xs text-muted">Manage roles and the permissions assigned to each.</p>
            </div>
        </div>
        <a href="{{ route('admin.roles.create') }}"
            class="bg-maroon hover:bg-maroon/90 text-white text-sm font-semibold px-4 py-2.5 rounded-md transition-colors">
            + Add Role
        </a>
    </div>

    @if (session('success'))
        <div class="mb-5">
            <x-alert type="success" title="Success!" :message="session('success')" :dismiss-after="10000" />
        </div>
    @endif

    @if (session('error'))
        <div class="mb-5">
            <x-alert type="error" title="Error!" :message="session('error')" :dismiss-after="10000" />
        </div>
    @endif

    <div class="bg-white border border-border rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-[13px] table-fixed border-collapse">
                <thead>
                    <tr
                        class="border-b border-border text-left text-[10.5px] font-semibold text-muted uppercase tracking-wider bg-slate-50/80">
                        <th class="px-3 py-2 w-6"></th>
                        <th class="px-3 py-2 w-8">#</th>
                        <th class="px-3 py-2 w-48">Role</th>
                        <th class="px-3 py-2">Permissions</th>
                        <th class="px-3 py-2 w-24 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border/70">
                    @forelse ($roles as $role)
                        @php
                            $isProtected = $role->name === 'admin';
                            $rowId = 'role-row-' . $role->id;
                            $detailId = 'role-detail-' . $role->id;
                            $grouped = $role->permissions->groupBy(fn($p) => explode('.', $p->name)[0]);
                        @endphp

                        <tr id="{{ $rowId }}"
                            class="js-role-row group cursor-pointer align-middle text-body odd:bg-white even:bg-slate-50/30 hover:bg-maroon/5 transition-colors"
                            data-target="{{ $detailId }}">
                            <td class="px-2 py-2 text-slate-400">
                                <svg class="js-role-chevron h-3.5 w-3.5 transition-transform duration-150" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </td>
                            <td class="px-3 py-2 text-muted text-xs">{{ $loop->iteration }}</td>
                            <td class="px-3 py-2 font-semibold text-ink">
                                {{ ucwords(str_replace('-', ' ', $role->name)) }}
                                @if ($isProtected)
                                    <span
                                        class="ml-1.5 inline-block px-1.5 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[10px] font-semibold align-middle">
                                        Protected
                                    </span>
                                @endif
                            </td>
                            <td class="px-3 py-2 text-body/90">
                                @if ($grouped->isEmpty())
                                    <span class="text-muted">No permissions assigned</span>
                                @else
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($grouped as $module => $perms)
                                            <span
                                                class="inline-block px-2 py-0.5 rounded-full bg-maroon/10 text-maroon text-[11px] font-medium">
                                                {{ ucwords(str_replace('_', ' ', $module)) }} ({{ $perms->count() }})
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            <td class="px-3 py-2 text-right whitespace-nowrap">
                                @unless ($isProtected)
                                    <div
                                        class="js-no-toggle flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 focus-within:opacity-100 transition-opacity">
                                        <a href="{{ route('admin.roles.edit', $role) }}" title="Edit"
                                            class="p-1.5 rounded-md text-slate-500 hover:text-maroon hover:bg-maroon/10 transition-colors">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19.5 13.5L19.5 19.5a2.25 2.25 0 01-2.25 2.25L4.5 21.75a2.25 2.25 0 01-2.25-2.25L2.25 6.75A2.25 2.25 0 014.5 4.5l6 0" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.roles.destroy', $role) }}" method="POST"
                                            onsubmit="return confirm('Delete this role? Users with this role will lose these permissions.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Delete"
                                                class="p-1.5 rounded-md text-slate-500 hover:text-rose-600 hover:bg-rose-50 transition-colors">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="1.8">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                @endunless
                            </td>
                        </tr>

                        <tr id="{{ $detailId }}" class="js-role-detail hidden">
                            <td colspan="5" class="p-0">
                                <div class="bg-slate-50/70 border-t border-b border-border px-8 py-5">
                                    @if ($grouped->isEmpty())
                                        <p class="text-sm text-muted">No permissions assigned to this role.</p>
                                    @else
                                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-4">
                                            @foreach ($grouped as $module => $perms)
                                                <div>
                                                    <p
                                                        class="text-[10.5px] font-semibold text-muted uppercase tracking-wide mb-1">
                                                        {{ ucwords(str_replace('_', ' ', $module)) }}
                                                    </p>
                                                    <p class="text-sm text-ink">
                                                        {{ $perms->map(fn($p) => ucfirst(str_replace('_', ' ', explode('.', $p->name)[1] ?? $p->name)))->join(', ') }}
                                                    </p>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-muted">No roles created yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.querySelectorAll('.js-role-row').forEach(function(row) {
            row.addEventListener('click', function(e) {
                if (e.target.closest('.js-no-toggle')) return;

                const detail = document.getElementById(this.dataset.target);
                const chevron = this.querySelector('.js-role-chevron');
                if (!detail) return;

                detail.classList.toggle('hidden');
                if (chevron) chevron.classList.toggle('rotate-90');
            });
        });
    </script>

@endsection
