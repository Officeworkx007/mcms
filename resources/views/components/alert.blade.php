@props([
    'type' => 'info', // info | success | error | warning
    'title' => '',
    'message' => '',
    'actionLabel' => null, // e.g. "Retry", "Renew Now" — omit to hide the button
    'actionHref' => null, // link for the action button
    'dismissAfter' => 6000, // ms; set to 0 to disable auto-dismiss
])

@php
    $palette =
        [
            'info' => [
                'ring' => 'ring-blue-100',
                'bg' => 'bg-white',
                'accent' => 'bg-blue-600',
                'icon_bg' => 'bg-blue-600',
                'title' => 'text-slate-900',
                'text' => 'text-slate-500',
                'btn' => 'text-blue-700 border-blue-200 bg-blue-50 hover:bg-blue-100',
                'progress' => 'bg-blue-500',
            ],
            'success' => [
                'ring' => 'ring-emerald-100',
                'bg' => 'bg-white',
                'accent' => 'bg-emerald-600',
                'icon_bg' => 'bg-emerald-600',
                'title' => 'text-slate-900',
                'text' => 'text-slate-500',
                'btn' => 'text-emerald-700 border-emerald-200 bg-emerald-50 hover:bg-emerald-100',
                'progress' => 'bg-emerald-500',
            ],
            'error' => [
                'ring' => 'ring-rose-100',
                'bg' => 'bg-white',
                'accent' => 'bg-rose-600',
                'icon_bg' => 'bg-rose-600',
                'title' => 'text-slate-900',
                'text' => 'text-slate-500',
                'btn' => 'text-rose-700 border-rose-200 bg-rose-50 hover:bg-rose-100',
                'progress' => 'bg-rose-500',
            ],
            'warning' => [
                'ring' => 'ring-amber-100',
                'bg' => 'bg-white',
                'accent' => 'bg-amber-500',
                'icon_bg' => 'bg-amber-500',
                'title' => 'text-slate-900',
                'text' => 'text-slate-500',
                'btn' => 'text-amber-700 border-amber-200 bg-amber-50 hover:bg-amber-100',
                'progress' => 'bg-amber-500',
            ],
        ][$type] ?? $palette['info'];

    // Clean Heroicons v2 solid paths, correctly proportioned on a 24x24 viewBox.
    $icons =
        [
            'info' =>
                '<path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 01.67 1.34l-.042.02c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 11-.671-1.34l.043-.02zM12 9a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" />',
            'success' =>
                '<path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />',
            'error' =>
                '<path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM9.53 8.47a.75.75 0 00-1.06 1.06L10.94 12l-2.47 2.47a.75.75 0 101.06 1.06L12 13.06l2.47 2.47a.75.75 0 101.06-1.06L13.06 12l2.47-2.47a.75.75 0 10-1.06-1.06L12 10.94 9.53 8.47z" clip-rule="evenodd" />',
            'warning' =>
                '<path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" />',
        ][$type] ?? $icons['info'];

    $id = 'alert-' . uniqid();
@endphp

<div id="{{ $id }}"
    class="js-alert relative overflow-hidden rounded-xl {{ $palette['bg'] }} ring-1 {{ $palette['ring'] }} shadow-[0_2px_10px_-3px_rgba(0,0,0,0.08)] transition-all duration-200 ease-in"
    data-dismiss-after="{{ (int) $dismissAfter }}">
    <div class="flex items-start gap-3.5 px-5 py-4">
        <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full {{ $palette['icon_bg'] }}">
            <svg class="h-4.5 w-4.5 text-white" viewBox="0 0 24 24" fill="currentColor" style="width:18px;height:18px">
                {!! $icons !!}
            </svg>
        </span>

        <div class="min-w-0 flex-1 pt-0.5">
            <p class="text-sm font-semibold {{ $palette['title'] }}">{{ $title }}</p>
            <p class="mt-0.5 text-sm leading-snug {{ $palette['text'] }}">{{ $message }}</p>
        </div>

        @if ($actionLabel)
            <a href="{{ $actionHref ?? '#' }}"
                class="shrink-0 self-center rounded-lg border px-3.5 py-1.5 text-xs font-semibold transition-colors {{ $palette['btn'] }}">
                {{ $actionLabel }}
            </a>
        @endif

        <button type="button" onclick="dismissAlert('{{ $id }}')"
            class="shrink-0 self-start rounded-md p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors"
            aria-label="Dismiss">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.25">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    @if ((int) $dismissAfter > 0)
        <div class="h-0.5 w-full bg-black/5">
            <div class="js-alert-progress h-full {{ $palette['progress'] }}"
                style="width: 100%; transition: width {{ (int) $dismissAfter }}ms linear;"></div>
        </div>
    @endif
</div>

@once
    <script>
        function dismissAlert(id) {
            const el = document.getElementById(id);
            if (!el) return;
            clearTimeout(el._dismissTimer);
            el.style.opacity = '0';
            el.style.transform = 'translateY(-4px) scale(0.98)';
            setTimeout(() => el.remove(), 200);
        }

        function initAlertTimer(el) {
            const duration = parseInt(el.dataset.dismissAfter, 10);
            if (!duration) return;

            const bar = el.querySelector('.js-alert-progress');

            function startTimer(fromWidth) {
                if (bar) {
                    bar.style.transition = 'none';
                    bar.style.width = fromWidth + '%';
                    // Force reflow so the transition below actually animates.
                    void bar.offsetWidth;
                    bar.style.transition = `width ${el._remaining}ms linear`;
                    bar.style.width = '0%';
                }
                el._startedAt = Date.now();
                el._dismissTimer = setTimeout(() => dismissAlert(el.id), el._remaining);
            }

            function pauseTimer() {
                clearTimeout(el._dismissTimer);
                const elapsed = Date.now() - el._startedAt;
                el._remaining = Math.max(el._remaining - elapsed, 0);
                if (bar) {
                    const computed = getComputedStyle(bar).width;
                    bar.style.transition = 'none';
                    bar.style.width = computed;
                }
            }

            el._remaining = duration;
            el.addEventListener('mouseenter', pauseTimer);
            el.addEventListener('mouseleave', () => startTimer(
                bar ? parseFloat(getComputedStyle(bar).width) / el.offsetWidth * 100 : 100
            ));

            startTimer(100);
        }

        document.querySelectorAll('.js-alert').forEach(initAlertTimer);
    </script>
@endonce
