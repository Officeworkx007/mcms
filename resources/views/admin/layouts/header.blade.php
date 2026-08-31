<header
    class="bg-ink text-white h-16 flex items-center justify-between px-5 shadow-md sticky top-0 z-20 border-b-2 border-maroon">
    <div class="flex items-center gap-3">
        <div class="w-9 h-9 rounded bg-white/10 flex items-center justify-center shrink-0">
            <img src="{{ asset('images/logo.png') }}" alt="" class="w-5 h-5 object-contain"
                onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden')">
            <svg class="w-5 h-5 text-gold hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 2l7 3v6c0 5-3.5 8.5-7 11-3.5-2.5-7-6-7-11V5l7-3z" />
            </svg>
        </div>
        <div class="leading-tight">
            <p class="font-semibold text-sm tracking-wide">HCLSC</p>
            <p class="text-[11px] text-white/50">Mediation Case Management System</p>
        </div>
    </div>

    <div class="flex items-center gap-4">
        <span class="text-sm text-white/80 hidden sm:inline">{{ auth()->user()->name }}</span>
        <div class="w-8 h-8 rounded-full bg-maroon flex items-center justify-center text-xs font-semibold shrink-0">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit"
                class="flex items-center gap-1.5 text-sm font-medium bg-white/5 hover:bg-white/10 border border-white/10 px-3 py-1.5 rounded transition-colors">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Logout
            </button>
        </form>
    </div>
</header>
