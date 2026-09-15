<header class="u-topbar">
    <div class="u-topbar-left">
        <h1 class="u-topbar-title">@yield('title', 'Dashboard')</h1>
    </div>
    <div class="u-topbar-right">
        <span class="u-date-chip">{{ now()->format('l, d M Y') }}</span>
    </div>
</header>

<style>
    .u-topbar {
        height: 64px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 clamp(20px, 3vw, 40px);
        background: rgba(245, 246, 252, 0.85);
        backdrop-filter: blur(8px);
        border-bottom: 1px solid var(--line);
        position: sticky;
        top: 0;
        z-index: 30;
    }

    .u-topbar-title {
        font-size: 17px;
        font-weight: 700;
        letter-spacing: -0.01em;
        padding-left: 44px;
        /* clears the mobile hamburger */
    }

    @media (min-width: 901px) {
        .u-topbar-title {
            padding-left: 0;
        }
    }

    .u-date-chip {
        font-size: 12.5px;
        font-weight: 600;
        color: var(--ink-soft);
        background: var(--paper-raised);
        border: 1px solid var(--line);
        padding: 7px 13px;
        border-radius: 100px;
    }
</style>
