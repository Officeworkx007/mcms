@extends('user.layouts.master')

@section('title', 'Dashboard')

@section('content')

    <div class="u-welcome">
        <div>
            <h2>Welcome back, {{ explode(' ', auth()->user()->name ?? 'there')[0] }} 👋</h2>
            <p>Here's a quick look at what's happening today.</p>
        </div>
    </div>

    <div class="u-stats-grid">
        <div class="u-stat-card" style="--delay:0.05s; --accent:var(--indigo);">
            <div class="u-stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M9 12h6m-6 4h6M9 8h1M5 4h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V5a1 1 0 011-1z" />
                </svg>
            </div>
            <p class="u-stat-label">Assigned Cases</p>
            <p class="u-stat-value">{{ $assignedCases ?? '—' }}</p>
        </div>

        <div class="u-stat-card" style="--delay:0.12s; --accent:var(--coral);">
            <div class="u-stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <p class="u-stat-label">Upcoming Sittings</p>
            <p class="u-stat-value">{{ $upcomingSittings ?? '—' }}</p>
        </div>

        <div class="u-stat-card" style="--delay:0.19s; --accent:var(--mint);">
            <div class="u-stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <p class="u-stat-label">Cases Settled</p>
            <p class="u-stat-value">{{ $settledCases ?? '—' }}</p>
        </div>

        <div class="u-stat-card" style="--delay:0.26s; --accent:var(--amber);">
            <div class="u-stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="u-stat-label">Pending Action</p>
            <p class="u-stat-value">{{ $pendingCases ?? '—' }}</p>
        </div>
    </div>

    <div class="u-panel">
        <h3>Getting started</h3>
        <p class="u-panel-note">This panel is under construction. As modules are added for staff, they'll appear here and in
            the sidebar.</p>
    </div>

@endsection

@push('styles')
    <style>
        .u-welcome {
            margin-bottom: 24px;
            animation: uFadeUp 0.5s cubic-bezier(.2, .8, .2, 1);
        }

        .u-welcome h2 {
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -0.01em;
            margin-bottom: 4px;
        }

        .u-welcome p {
            font-size: 14px;
            color: var(--ink-soft);
        }

        .u-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 28px;
        }

        .u-stat-card {
            background: var(--paper-raised);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: 20px;
            box-shadow: var(--shadow);
            opacity: 0;
            transform: translateY(14px);
            animation: uCardIn 0.55s cubic-bezier(.2, .8, .2, 1) forwards;
            animation-delay: var(--delay, 0s);
            transition: transform 0.2s ease;
        }

        .u-stat-card:hover {
            transform: translateY(-3px);
        }

        .u-stat-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: color-mix(in srgb, var(--accent) 14%, transparent);
            color: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 14px;
        }

        .u-stat-icon svg {
            width: 19px;
            height: 19px;
        }

        .u-stat-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--ink-soft);
            text-transform: uppercase;
            letter-spacing: 0.02em;
            margin-bottom: 6px;
        }

        .u-stat-value {
            font-family: 'Sora', sans-serif;
            font-size: 26px;
            font-weight: 700;
            color: var(--ink);
        }

        @keyframes uCardIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes uFadeUp {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .u-panel {
            background: var(--paper-raised);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: 24px;
            box-shadow: var(--shadow);
            animation: uFadeUp 0.5s cubic-bezier(.2, .8, .2, 1) 0.3s backwards;
        }

        .u-panel h3 {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .u-panel-note {
            font-size: 13.5px;
            color: var(--ink-soft);
            line-height: 1.6;
        }
    </style>
@endpush
