<div id="alert-stack" class="mx-auto max-w-4xl space-y-4 p-6">

    <x-alert type="info" title="Heads up!" message="Beware – you should be careful with this" action-label="Dismiss"
        action-href="#" />

    <x-alert type="success" title="Success!" message="Your changes have been saved successfully!"
        action-label="Close" action-href="#" />

    <x-alert type="error" title="Error!" message="There was a problem processing your request!" action-label="Retry"
        action-href="#" />

    <x-alert type="warning" title="Warning!" message="Your account is about to expire. Please renew your subscription."
        action-label="Renew Now" action-href="#" />

    <div class="flex justify-end">
        <button type="button" onclick="document.getElementById('alert-stack').remove()"
            class="flex items-center gap-1.5 text-sm font-semibold text-ink hover:text-maroon transition-colors">
            Close all
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>
