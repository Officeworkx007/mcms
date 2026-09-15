@extends('admin.layouts.master')

@section('title', 'Add User')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-maroon shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M17 20h5v-1a4 4 0 00-4-4h-1M9 20H4v-1a4 4 0 014-4h1m0-6a3 3 0 116 0 3 3 0 01-6 0zm8 3a3 3 0 10-2-5.3" />
            </svg>
            <div>
                <h1 class="text-base font-bold text-ink">User Details</h1>
            </div>
        </div>
        <a href="{{ route('admin.users.index') }}"
            class="text-sm font-medium text-body hover:text-ink flex items-center gap-1.5">
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            Back to list
        </a>
    </div>

    <form method="POST" action="{{ route('admin.users.store') }}"
        class="bg-white border border-border rounded-lg shadow-sm p-6 w-full">
        @csrf

        @include('admin.users._form')

        <div class="mt-7 flex items-center gap-3">
            <button type="submit"
                class="bg-maroon hover:bg-maroon/90 text-white text-sm font-semibold px-5 py-2.5 rounded-md transition-colors">
                Save User
            </button>
            <a href="{{ route('admin.users.index') }}" class="text-sm font-medium text-muted hover:text-body px-3 py-2.5">
                Cancel
            </a>
        </div>
    </form>

@endsection
