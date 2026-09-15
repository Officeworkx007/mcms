<aside class="u-sidebar" id="uSidebar">
    <a href="{{ route('user.dashboard') }}" class="u-brand">
        <span class="u-brand-mark">
            <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M12 3v18M5 8l-3 6a4 4 0 008 0l-3-6M19 8l-3 6a4 4 0 008 0l-3-6M5 8h14" />
            </svg>
        </span>
        <span class="u-brand-text">
            <span class="u-brand-word">HCLSC</span>
            <span class="u-brand-sub">Samadhan Staff Portal</span>
        </span>
    </a>

    <nav class="u-nav">
        <a href="{{ route('user.dashboard') }}"
            class="u-nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
            <span class="u-nav-link-left">
                <span class="u-nav-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 12l9-9 9 9M5 10v10h14V10" />
                    </svg>
                </span>
                <span>Dashboard</span>
            </span>
        </a>

        {{-- TODO: replace href="#" with route('user.cases.*') once routes exist --}}
        @can('case.view')
            <div class="u-nav-group">
                <button type="button" class="u-nav-link u-nav-toggle" onclick="uToggleSubmenu('u-cases-submenu', this)">
                    <span class="u-nav-link-left">
                        <span class="u-nav-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M9 12h6m-6 4h6M9 8h1M5 4h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V5a1 1 0 011-1z" />
                            </svg>
                        </span>
                        <span>Cases</span>
                    </span>
                    <svg class="u-nav-chevron" width="15" height="15" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div id="u-cases-submenu" class="u-submenu">
                    <a href="#" class="u-submenu-link">All Cases</a>
                    @can('case.create')
                        <a href="#" class="u-submenu-link">Add Case</a>
                    @endcan
                </div>
            </div>
        @endcan

        {{-- TODO: replace href="#" with route('user.mediators.*') once routes exist --}}
        @can('mediator.view')
            <div class="u-nav-group">
                <button type="button" class="u-nav-link u-nav-toggle"
                    onclick="uToggleSubmenu('u-mediators-submenu', this)">
                    <span class="u-nav-link-left">
                        <span class="u-nav-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M17 20h5v-1a4 4 0 00-4-4h-1M9 20H4v-1a4 4 0 014-4h1m0-6a3 3 0 116 0 3 3 0 01-6 0zm8 3a3 3 0 10-2-5.3" />
                            </svg>
                        </span>
                        <span>Mediators</span>
                    </span>
                    <svg class="u-nav-chevron" width="15" height="15" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div id="u-mediators-submenu" class="u-submenu">
                    <a href="#" class="u-submenu-link">All Mediators</a>
                    @can('mediator.create')
                        <a href="#" class="u-submenu-link">Add Mediator</a>
                    @endcan
                </div>
            </div>
        @endcan

        {{-- TODO: replace href="#" with route('user.categories.*') once routes exist --}}
        @can('case_category.view')
            <div class="u-nav-group">
                <button type="button" class="u-nav-link u-nav-toggle"
                    onclick="uToggleSubmenu('u-categories-submenu', this)">
                    <span class="u-nav-link-left">
                        <span class="u-nav-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                        </span>
                        <span>Case Category</span>
                    </span>
                    <svg class="u-nav-chevron" width="15" height="15" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div id="u-categories-submenu" class="u-submenu">
                    <a href="#" class="u-submenu-link">All Categories</a>
                    @can('case_category.create')
                        <a href="#" class="u-submenu-link">Add Category</a>
                    @endcan
                </div>
            </div>
        @endcan

        {{-- TODO: replace href="#" with route('user.reports.index') once routes exist --}}
        @can('report.view')
            <a href="#" class="u-nav-link">
                <span class="u-nav-link-left">
                    <span class="u-nav-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 17v-6h6v6M4 10l8-7 8 7v9a1 1 0 01-1 1H5a1 1 0 01-1-1V5a1 1 0 011-1z" />
                        </svg>
                    </span>
                    <span>Reports</span>
                </span>
            </a>
        @endcan
    </nav>

    <div class="u-sidebar-foot">
        <div class="u-user-chip">
            <span class="u-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</span>
            <div class="u-user-meta">
                <span class="u-user-name">{{ auth()->user()->name ?? 'Staff' }}</span>
                <span
                    class="u-user-role">{{ ucwords(str_replace(['-', '_'], ' ', auth()->user()->roles->first()?->name ?? '')) }}</span>
            </div>
        </div>
        <form method="POST" action="{{ route('user.logout') }}">
            @csrf
            <button type="submit" class="u-logout-btn" title="Sign out">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9" />
                </svg>
            </button>
        </form>
    </div>
</aside>

<button class="u-sidebar-toggle" id="uSidebarToggle" aria-label="Toggle menu">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
        stroke-linecap="round" stroke-linejoin="round">
        <path d="M3 12h18M3 6h18M3 18h18" />
    </svg>
</button>

<style>
    .u-sidebar {
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        width: var(--sidebar-w);
        background: var(--paper-raised);
        border-right: 1px solid var(--line);
        display: flex;
        flex-direction: column;
        z-index: 50;
        transition: transform 0.28s cubic-bezier(.2, .9, .25, 1);
    }

    .u-brand {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 22px 20px;
        border-bottom: 1px solid var(--line);
    }

    .u-brand-mark {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--indigo), var(--indigo-deep));
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .u-brand-mark svg {
        width: 19px;
        height: 19px;
    }

    .u-brand-text {
        display: flex;
        flex-direction: column;
        line-height: 1.1;
    }

    .u-brand-word {
        font-size: 15px;
        font-weight: 700;
    }

    .u-brand-sub {
        font-size: 10px;
        color: var(--ink-soft);
        font-weight: 500;
        margin-top: 2px;
    }

    .u-nav {
        flex: 1;
        padding: 16px 14px;
        display: flex;
        flex-direction: column;
        gap: 4px;
        overflow-y: auto;
    }

    .u-nav-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 11px 14px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 500;
        color: var(--ink-soft);
        position: relative;
        width: 100%;
        border: none;
        background: transparent;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
        text-align: left;
        transition: background 0.15s, color 0.15s;
    }

    .u-nav-link:hover {
        background: rgba(76, 63, 224, 0.07);
        color: var(--ink);
    }

    .u-nav-link.active {
        background: linear-gradient(135deg, rgba(76, 63, 224, 0.12), rgba(34, 211, 178, 0.10));
        color: var(--indigo-deep);
        font-weight: 600;
    }

    .u-nav-link.active::before {
        content: '';
        position: absolute;
        left: -14px;
        top: 8px;
        bottom: 8px;
        width: 3px;
        border-radius: 3px;
        background: linear-gradient(180deg, var(--indigo), var(--mint));
    }

    .u-nav-link-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .u-nav-icon {
        width: 19px;
        height: 19px;
        flex-shrink: 0;
        display: flex;
    }

    .u-nav-icon svg {
        width: 100%;
        height: 100%;
    }

    .u-nav-group.open .u-nav-toggle {
        color: var(--indigo-deep);
        background: rgba(76, 63, 224, 0.07);
    }

    .u-nav-chevron {
        flex-shrink: 0;
        transition: transform 0.2s ease;
    }

    .u-nav-group.open .u-nav-chevron {
        transform: rotate(180deg);
    }

    .u-submenu {
        max-height: 0;
        overflow: hidden;
        margin-left: 16px;
        padding-left: 15px;
        border-left: 1px solid var(--line);
        transition: max-height 0.25s ease, margin-top 0.25s ease;
    }

    .u-nav-group.open .u-submenu {
        max-height: 200px;
        margin-top: 4px;
    }

    .u-submenu-link {
        display: block;
        padding: 9px 12px;
        border-radius: 8px;
        font-size: 13.5px;
        color: var(--ink-soft);
        transition: background 0.15s, color 0.15s;
    }

    .u-submenu-link:hover {
        background: rgba(76, 63, 224, 0.07);
        color: var(--ink);
    }

    .u-sidebar-foot {
        padding: 14px;
        border-top: 1px solid var(--line);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .u-user-chip {
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 1;
        min-width: 0;
    }

    .u-avatar {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--coral), var(--coral-deep));
        color: #fff;
        font-family: 'Sora', sans-serif;
        font-weight: 700;
        font-size: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .u-user-meta {
        display: flex;
        flex-direction: column;
        min-width: 0;
        line-height: 1.25;
    }

    .u-user-name {
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .u-user-role {
        font-size: 11px;
        color: var(--ink-soft);
    }

    .u-logout-btn {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        border: none;
        background: rgba(255, 107, 74, 0.1);
        color: var(--coral-deep);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        flex-shrink: 0;
        transition: background 0.15s;
    }

    .u-logout-btn:hover {
        background: rgba(255, 107, 74, 0.2);
    }

    .u-sidebar-toggle {
        display: none;
        position: fixed;
        top: 16px;
        left: 16px;
        z-index: 60;
        width: 40px;
        height: 40px;
        border-radius: 10px;
        border: 1px solid var(--line);
        background: var(--paper-raised);
        color: var(--ink);
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: var(--shadow);
    }

    @media (max-width: 900px) {
        .u-sidebar {
            transform: translateX(-100%);
        }

        .u-sidebar.open {
            transform: translateX(0);
        }

        .u-sidebar-toggle {
            display: flex;
        }
    }
</style>

<script>
    function uToggleSubmenu(id, btn) {
        const group = btn.closest('.u-nav-group');
        if (!group) return;
        group.classList.toggle('open');
    }

    (function() {
        const sidebar = document.getElementById('uSidebar');
        const toggle = document.getElementById('uSidebarToggle');
        if (!sidebar || !toggle) return;

        toggle.addEventListener('click', () => sidebar.classList.toggle('open'));
        document.addEventListener('click', (e) => {
            if (window.innerWidth > 900) return;
            if (!sidebar.contains(e.target) && !toggle.contains(e.target)) {
                sidebar.classList.remove('open');
            }
        });
    })();
</script>
