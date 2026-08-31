@extends('admin.layouts.master')

@section('title', 'Dashboard')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-bold text-ink">Welcome, {{ auth()->user()->name }}</h1>
            <p class="text-muted text-sm mt-1">Here's an overview of your mediation case system.</p>
        </div>
        <span class="text-xs font-medium text-maroon bg-maroon/10 px-3 py-1.5 rounded-full">
            {{ now()->format('d M Y') }}
        </span>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg border border-border p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold text-muted uppercase tracking-wide">Total Cases</p>
                <div class="w-8 h-8 rounded-md bg-ink/5 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-ink" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h6m-6 4h6M9 8h1M5 4h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V5a1 1 0 011-1z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-ink mt-2">—</p>
        </div>

        <div class="bg-white rounded-lg border border-border p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold text-muted uppercase tracking-wide">Pending</p>
                <div class="w-8 h-8 rounded-md bg-amber/10 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-amber" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.8">
                        <circle cx="12" cy="12" r="9" />
                        <path stroke-linecap="round" d="M12 7v5l3 3" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-amber mt-2">—</p>
        </div>

        <div class="bg-white rounded-lg border border-border p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold text-muted uppercase tracking-wide">Settled</p>
                <div class="w-8 h-8 rounded-md bg-sage/10 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-sage" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-sage mt-2">—</p>
        </div>

        <div class="bg-white rounded-lg border border-border p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold text-muted uppercase tracking-wide">Unsettled</p>
                <div class="w-8 h-8 rounded-md bg-maroon/10 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-maroon" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-maroon mt-2">—</p>
        </div>
    </div>
@endsection
