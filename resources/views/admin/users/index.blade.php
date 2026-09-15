@extends('admin.layouts.master')

@section('title', 'Users')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-maroon shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M17 20h5v-1a4 4 0 00-4-4h-1M9 20H4v-1a4 4 0 014-4h1m0-6a3 3 0 116 0 3 3 0 01-6 0zm8 3a3 3 0 10-2-5.3" />
            </svg>
            <div>
                <h1 class="text-base font-bold text-ink">Users</h1>
                <p class="text-xs text-muted">Admin panel accounts and their assigned roles.</p>
            </div>
        </div>
        <a href="{{ route('admin.users.create') }}"
            class="bg-maroon hover:bg-maroon/90 text-white text-sm font-semibold px-4 py-2.5 rounded-md transition-colors">
            + Add User
        </a>
    </div>

    @if (session('status'))
        <div class="mb-5">
            <x-alert type="success" title="Success!" :message="session('status')" :dismiss-after="10000" />
        </div>
    @endif

    <div class="bg-white border border-border rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-[13px] table-fixed border-collapse">
                <thead>
                    <tr
                        class="border-b border-border text-left text-[10.5px] font-semibold text-muted uppercase tracking-wider bg-slate-50/80">
                        <th class="px-3 py-2 w-8">#</th>
                        <th class="px-3 py-2 w-40">Name</th>
                        <th class="px-3 py-2 w-32">Username</th>
                        <th class="px-3 py-2 w-52">Email</th>
                        <th class="px-3 py-2 w-32">Role</th>
                        <th class="px-3 py-2 w-20 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border/70">
                    @forelse ($users as $user)
                        @php
                            $role = $user->roles->first();
                        @endphp
                        <tr
                            class="group align-middle text-body odd:bg-white even:bg-slate-50/30 hover:bg-maroon/5 transition-colors">
                            <td class="px-3 py-2 text-muted text-xs">{{ $users->firstItem() + $loop->index }}</td>
                            <td class="px-3 py-2 font-semibold text-ink truncate">{{ $user->name }}</td>
                            <td class="px-3 py-2 truncate text-body/90">{{ $user->username ?? '—' }}</td>
                            <td class="px-3 py-2 truncate text-body/90">{{ $user->email }}</td>
                            <td class="px-3 py-2">
                                @if ($role)
                                    <span
                                        class="inline-block px-2 py-0.5 rounded-full bg-maroon/10 text-maroon text-[11px] font-medium">
                                        {{ ucwords(str_replace(['-', '_'], ' ', $role->name)) }}
                                    </span>
                                @else
                                    <span class="text-muted text-xs">No role</span>
                                @endif
                            </td>
                            <td class="px-3 py-2 text-right whitespace-nowrap">
                                <div
                                    class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 focus-within:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.users.edit', $user) }}" title="Edit"
                                        class="p-1.5 rounded-md text-slate-500 hover:text-maroon hover:bg-maroon/10 transition-colors">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 13.5L19.5 19.5a2.25 2.25 0 01-2.25 2.25L4.5 21.75a2.25 2.25 0 01-2.25-2.25L2.25 6.75A2.25 2.25 0 014.5 4.5l6 0" />
                                        </svg>
                                    </a>
                                    @unless ($user->id === auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                            onsubmit="return confirm('Delete this user account?');">
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
                                    @endunless
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-muted">No users created yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>

@endsection
