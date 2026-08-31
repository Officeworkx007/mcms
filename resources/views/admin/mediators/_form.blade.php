{{-- ============================== --}}
{{-- SECTION: Advocate Information  --}}
{{-- ============================== --}}
<div class="flex items-center gap-2 mb-1">
    <span class="w-1 h-4 bg-maroon rounded-sm"></span>
    <h2 class="text-xs font-bold text-maroon uppercase tracking-wide">Advocate Information</h2>
</div>
<div class="border-b border-border pb-6 mb-6">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-x-6 gap-y-5 mt-4">

        <div>
            <label for="advocate_name" class="block text-xs font-semibold text-ink uppercase tracking-wide mb-1.5">
                Advocate Name
            </label>
            <input id="advocate_name" name="advocate_name" type="text" placeholder="e.g. Adv. Rajeev Kumar Singh"
                value="{{ old('advocate_name', $mediator->advocate_name ?? '') }}"
                class="w-full px-3 py-2.5 border border-border rounded-md text-sm text-ink placeholder:text-muted/60 focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon">
            @error('advocate_name')
                <p class="text-xs text-maroon mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="designation" class="block text-xs font-semibold text-ink uppercase tracking-wide mb-1.5">
                Designation
            </label>
            <input id="designation" name="designation" type="text" placeholder="e.g. Senior Advocate"
                value="{{ old('designation', $mediator->designation ?? '') }}"
                class="w-full px-3 py-2.5 border border-border rounded-md text-sm text-ink placeholder:text-muted/60 focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon">
            @error('designation')
                <p class="text-xs text-maroon mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="enrollment_no" class="block text-xs font-semibold text-ink uppercase tracking-wide mb-1.5">
                Enrollment No.
            </label>
            <input id="enrollment_no" name="enrollment_no" type="text" placeholder="e.g. MN/1234/2010"
                value="{{ old('enrollment_no', $mediator->enrollment_no ?? '') }}"
                class="w-full px-3 py-2.5 border border-border rounded-md text-sm text-ink placeholder:text-muted/60 focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon">
            <p class="text-xs text-muted mt-1 italic">Bar Council enrollment number.</p>
            @error('enrollment_no')
                <p class="text-xs text-maroon mt-1">{{ $message }}</p>
            @enderror
        </div>

    </div>
</div>

{{-- ============================== --}}
{{-- SECTION: Contact Details       --}}
{{-- ============================== --}}
<div class="flex items-center gap-2 mb-1">
    <span class="w-1 h-4 bg-maroon rounded-sm"></span>
    <h2 class="text-xs font-bold text-maroon uppercase tracking-wide">Contact Details</h2>
</div>
<div class="border-b border-border pb-6 mb-6">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-x-6 gap-y-5 mt-4">

        <div>
            <label for="contact_no" class="block text-xs font-semibold text-ink uppercase tracking-wide mb-1.5">
                Contact No.
            </label>
            <input id="contact_no" name="contact_no" type="text" placeholder="e.g. 98765 43210"
                value="{{ old('contact_no', $mediator->contact_no ?? '') }}"
                class="w-full px-3 py-2.5 border border-border rounded-md text-sm text-ink placeholder:text-muted/60 focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon">
            @error('contact_no')
                <p class="text-xs text-maroon mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-xs font-semibold text-ink uppercase tracking-wide mb-1.5">
                Email
            </label>
            <input id="email" name="email" type="email" placeholder="advocate@example.com"
                value="{{ old('email', $mediator->email ?? '') }}"
                class="w-full px-3 py-2.5 border border-border rounded-md text-sm text-ink placeholder:text-muted/60 focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon">
            @error('email')
                <p class="text-xs text-maroon mt-1">{{ $message }}</p>
            @enderror
        </div>

    </div>
</div>

{{-- ============================== --}}
{{-- SECTION: Experience & Status   --}}
{{-- ============================== --}}
<div class="flex items-center gap-2 mb-1">
    <span class="w-1 h-4 bg-maroon rounded-sm"></span>
    <h2 class="text-xs font-bold text-maroon uppercase tracking-wide">
        Experience{{ isset($mediator) ? ' & Status' : '' }}</h2>
</div>
<div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-x-6 gap-y-5 mt-4">

        <div>
            <label for="experience" class="block text-xs font-semibold text-ink uppercase tracking-wide mb-1.5">
                Experience
            </label>
            <input id="experience" name="experience" type="text" placeholder="e.g. 8 years"
                value="{{ old('experience', $mediator->experience ?? '') }}"
                class="w-full px-3 py-2.5 border border-border rounded-md text-sm text-ink placeholder:text-muted/60 focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon">
            <p class="text-xs text-muted mt-1 italic">Years of practice or mediation experience.</p>
            @error('experience')
                <p class="text-xs text-maroon mt-1">{{ $message }}</p>
            @enderror
        </div>

        @isset($mediator)
            <div>
                <label class="block text-xs font-semibold text-ink uppercase tracking-wide mb-1.5">
                    Status
                </label>
                <label class="flex items-center gap-2 px-3 py-2.5 border border-border rounded-md cursor-pointer">
                    <input id="is_active" name="is_active" type="checkbox" value="1"
                        {{ old('is_active', $mediator->is_active) ? 'checked' : '' }}
                        class="rounded border-border text-maroon focus:ring-maroon/30">
                    <span class="text-sm text-body">Active — visible in mediator dropdown for new cases</span>
                </label>
            </div>
        @endisset

    </div>
</div>
