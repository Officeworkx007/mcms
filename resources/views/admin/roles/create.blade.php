@extends('admin.layouts.master')

@section('title', 'Add Role')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-maroon shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
            <div>
                <h1 class="text-base font-bold text-ink">Role Details</h1>
            </div>
        </div>
        <a href="{{ route('admin.roles.index') }}"
            class="text-sm font-medium text-body hover:text-ink flex items-center gap-1.5">
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            Back to list
        </a>
    </div>

    <form method="POST" action="{{ route('admin.roles.store') }}"
        class="bg-white border border-border rounded-lg shadow-sm p-6 w-full">
        @csrf

        @include('admin.roles._form')

        <div class="mt-7 flex items-center gap-3">
            <button type="submit"
                class="bg-maroon hover:bg-maroon/90 text-white text-sm font-semibold px-5 py-2.5 rounded-md transition-colors">
                Save Role
            </button>
            <a href="{{ route('admin.roles.index') }}" class="text-sm font-medium text-muted hover:text-body px-3 py-2.5">
                Cancel
            </a>
        </div>
    </form>

@endsection
