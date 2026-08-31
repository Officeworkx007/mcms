@extends('admin.layouts.master')

@section('title', 'Add Mediator')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-maroon shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="1.8">
                <circle cx="12" cy="8" r="4" />
                <path stroke-linecap="round" d="M4 20c0-4 3.5-6 8-6s8 2 8 6" />
            </svg>
            <div>
                <h1 class="text-base font-bold text-ink">Advocate Details</h1>
            </div>
        </div>
        <a href="{{ route('admin.mediators.index') }}"
            class="text-sm font-medium text-body hover:text-ink flex items-center gap-1.5">
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            Back to list
        </a>
    </div>

    <form method="POST" action="{{ route('admin.mediators.store') }}"
        class="bg-white border border-border rounded-lg shadow-sm p-6 w-full">
        @csrf

        @include('admin.mediators._form')

        <div class="mt-7 flex items-center gap-3">
            <button type="submit"
                class="bg-maroon hover:bg-maroon/90 text-white text-sm font-semibold px-5 py-2.5 rounded-md transition-colors">
                Save Mediator
            </button>
            <a href="{{ route('admin.mediators.index') }}"
                class="text-sm font-medium text-muted hover:text-body px-3 py-2.5">
                Cancel
            </a>
        </div>
    </form>

@endsection
