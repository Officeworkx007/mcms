<aside class="w-60 bg-panel border-r border-border min-h-[calc(100vh-64px)] py-4 hidden md:block">
    <p class="px-5 text-[11px] font-semibold text-muted uppercase tracking-wider mb-2">Main Menu</p>
    <nav class="px-3 space-y-1">

        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-md text-sm font-medium transition-colors
           {{ request()->routeIs('admin.dashboard') ? 'bg-maroon/10 text-maroon border-l-2 border-maroon' : 'text-body hover:bg-black/5 border-l-2 border-transparent' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M5 10v10h14V10" />
            </svg>
            <span>Dashboard</span>
        </a>

        <div>
            <button
                onclick="document.getElementById('cases-submenu').classList.toggle('hidden'); this.querySelector('.chevron').classList.toggle('rotate-180');"
                type="button"
                class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-md text-sm font-medium transition-colors
        {{ request()->routeIs('admin.cases.*') ? 'bg-maroon/10 text-maroon' : 'text-body hover:bg-black/5' }}">
                <span class="flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h6m-6 4h6M9 8h1M5 4h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V5a1 1 0 011-1z" />
                    </svg>
                    <span>Cases</span>
                </span>
                <svg class="chevron w-4 h-4 shrink-0 transition-transform {{ request()->routeIs('admin.cases.*') ? 'rotate-180' : '' }}"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div id="cases-submenu"
                class="{{ request()->routeIs('admin.cases.*') ? '' : 'hidden' }} mt-1 ml-4 pl-4 border-l border-border space-y-1">
                <a href="{{ route('admin.cases.index') }}"
                    class="block px-3 py-2 rounded-md text-sm transition-colors
           {{ request()->routeIs('admin.cases.index') ? 'text-maroon font-medium' : 'text-body hover:bg-black/5' }}">
                    All Cases
                </a>
                <a href="{{ route('admin.cases.create') }}"
                    class="block px-3 py-2 rounded-md text-sm transition-colors
           {{ request()->routeIs('admin.cases.create') ? 'text-maroon font-medium' : 'text-body hover:bg-black/5' }}">
                    Add Case
                </a>
            </div>
        </div>

        <div>
            <button
                onclick="document.getElementById('mediators-submenu').classList.toggle('hidden'); this.querySelector('.chevron').classList.toggle('rotate-180');"
                type="button"
                class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-md text-sm font-medium transition-colors
        {{ request()->routeIs('admin.mediators.*') ? 'bg-maroon/10 text-maroon' : 'text-body hover:bg-black/5' }}">
                <span class="flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 20h5v-1a4 4 0 00-4-4h-1M9 20H4v-1a4 4 0 014-4h1m0-6a3 3 0 116 0 3 3 0 01-6 0zm8 3a3 3 0 10-2-5.3" />
                    </svg>
                    <span>Mediators</span>
                </span>
                <svg class="chevron w-4 h-4 shrink-0 transition-transform {{ request()->routeIs('admin.mediators.*') ? 'rotate-180' : '' }}"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div id="mediators-submenu"
                class="{{ request()->routeIs('admin.mediators.*') ? '' : 'hidden' }} mt-1 ml-4 pl-4 border-l border-border space-y-1">
                <a href="{{ route('admin.mediators.index') }}"
                    class="block px-3 py-2 rounded-md text-sm transition-colors
           {{ request()->routeIs('admin.mediators.index') ? 'text-maroon font-medium' : 'text-body hover:bg-black/5' }}">
                    All Mediators
                </a>
                <a href="{{ route('admin.mediators.create') }}"
                    class="block px-3 py-2 rounded-md text-sm transition-colors
           {{ request()->routeIs('admin.mediators.create') ? 'text-maroon font-medium' : 'text-body hover:bg-black/5' }}">
                    Add Mediator
                </a>
            </div>
        </div>

        <div>
            <button
                onclick="document.getElementById('categories-submenu').classList.toggle('hidden'); this.querySelector('.chevron').classList.toggle('rotate-180');"
                type="button"
                class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-md text-sm font-medium transition-colors
        {{ request()->routeIs('admin.categories.*') ? 'bg-maroon/10 text-maroon' : 'text-body hover:bg-black/5' }}">
                <span class="flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                    <span>Categories</span>
                </span>
                <svg class="chevron w-4 h-4 shrink-0 transition-transform {{ request()->routeIs('admin.categories.*') ? 'rotate-180' : '' }}"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div id="categories-submenu"
                class="{{ request()->routeIs('admin.categories.*') ? '' : 'hidden' }} mt-1 ml-4 pl-4 border-l border-border space-y-1">
                <a href="{{ route('admin.categories.index') }}"
                    class="block px-3 py-2 rounded-md text-sm transition-colors
           {{ request()->routeIs('admin.categories.index') ? 'text-maroon font-medium' : 'text-body hover:bg-black/5' }}">
                    All Categories
                </a>
                <a href="{{ route('admin.categories.create') }}"
                    class="block px-3 py-2 rounded-md text-sm transition-colors
           {{ request()->routeIs('admin.categories.create') ? 'text-maroon font-medium' : 'text-body hover:bg-black/5' }}">
                    Add Category
                </a>
            </div>
        </div>

        <a href="{{ route('admin.reports.index') }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-md text-sm font-medium transition-colors border-l-2
           {{ request()->routeIs('admin.reports.*') ? 'bg-maroon/10 text-maroon border-maroon' : 'text-body hover:bg-black/5 border-transparent' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 17v-6h6v6M4 10l8-7 8 7v9a1 1 0 01-1 1H5a1 1 0 01-1-1V5a1 1 0 011-1z" />
            </svg>
            <span>Reports</span>
        </a>

    </nav>
</aside>
