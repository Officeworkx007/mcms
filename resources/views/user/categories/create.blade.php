@extends('user.layouts.master')

@section('title', 'Add Category')

@section('content')

    <div class="u-page-head">
        <div class="u-page-head-left">
            <span class="u-page-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M4 6h16M4 12h16M4 18h7" />
                </svg>
            </span>
            <h1>Category Details</h1>
        </div>
        <a href="{{ route('user.categories.index') }}" class="u-back-link">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 19l-7-7 7-7" />
            </svg>
            Back to list
        </a>
    </div>

    <form method="POST" action="{{ route('user.categories.store') }}" class="u-form-card">
        @csrf

        @include('user.categories._form')

        <div class="u-form-actions">
            <button type="submit" class="u-btn-primary">Save Category</button>
            <a href="{{ route('user.categories.index') }}" class="u-btn-cancel">Cancel</a>
        </div>
    </form>

@endsection

@push('styles')
    <style>
        .u-page-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 22px;
            animation: uFadeUp 0.5s cubic-bezier(.2, .8, .2, 1);
        }

        .u-page-head-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .u-page-icon {
            width: 22px;
            height: 22px;
            color: var(--indigo);
            display: flex;
            flex-shrink: 0;
        }

        .u-page-icon svg {
            width: 100%;
            height: 100%;
        }

        .u-page-head h1 {
            font-size: 17px;
            font-weight: 700;
            letter-spacing: -0.01em;
        }

        .u-back-link {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13.5px;
            font-weight: 500;
            color: var(--ink-soft);
            transition: color 0.15s;
        }

        .u-back-link:hover {
            color: var(--ink);
        }

        .u-form-card {
            background: var(--paper-raised);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 26px;
            width: 100%;
            animation: uFadeUp 0.5s cubic-bezier(.2, .8, .2, 1) 0.08s backwards;
        }

        .u-form-actions {
            margin-top: 26px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .u-btn-primary {
            background: var(--coral);
            color: #fff;
            border: none;
            font-size: 14px;
            font-weight: 600;
            padding: 12px 22px;
            border-radius: 10px;
            cursor: pointer;
            box-shadow: 0 10px 24px -8px rgba(255, 107, 74, 0.45);
            transition: background 0.15s, transform 0.15s;
        }

        .u-btn-primary:hover {
            background: var(--coral-deep);
        }

        .u-btn-primary:active {
            transform: translateY(1px);
        }

        .u-btn-cancel {
            font-size: 13.5px;
            font-weight: 500;
            color: var(--ink-soft);
            padding: 12px 14px;
            transition: color 0.15s;
        }

        .u-btn-cancel:hover {
            color: var(--ink);
        }
    </style>
@endpush
