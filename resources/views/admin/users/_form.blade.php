@php
    $isEdit = isset($user);
    $currentRole = old('role', $isEdit ? $user->roles->first()?->name : null);
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
    <div>
        <label for="name" class="block text-xs font-semibold text-muted uppercase tracking-wide mb-1.5">Full
            Name</label>
        <input id="name" name="name" type="text" value="{{ old('name', $isEdit ? $user->name : '') }}"
            class="w-full px-3 py-2.5 border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-maroon/20">
        @error('name')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="username"
            class="block text-xs font-semibold text-muted uppercase tracking-wide mb-1.5">Username</label>
        <input id="username" name="username" type="text"
            value="{{ old('username', $isEdit ? $user->username : '') }}"
            class="w-full px-3 py-2.5 border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-maroon/20"
            placeholder="optional">
        <p class="text-xs text-muted mt-1">Letters, numbers, dashes, underscores only. Can be used to log in instead of
            email.</p>
        @error('username')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="mb-4">
    <label for="email" class="block text-xs font-semibold text-muted uppercase tracking-wide mb-1.5">Email</label>
    <input id="email" name="email" type="email" value="{{ old('email', $isEdit ? $user->email : '') }}"
        class="w-full px-3 py-2.5 border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-maroon/20">
    @error('email')
        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
    <div>
        <label for="password" class="block text-xs font-semibold text-muted uppercase tracking-wide mb-1.5">
            Password @if ($isEdit)
                <span class="text-muted normal-case font-normal">(leave blank to keep current)</span>
            @endif
        </label>
        <input id="password" name="password" type="password"
            class="w-full px-3 py-2.5 border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-maroon/20">
        @error('password')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password_confirmation"
            class="block text-xs font-semibold text-muted uppercase tracking-wide mb-1.5">Confirm Password</label>
        <input id="password_confirmation" name="password_confirmation" type="password"
            class="w-full px-3 py-2.5 border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-maroon/20">
    </div>
</div>

<div class="mb-2">
    <label for="role" class="block text-xs font-semibold text-muted uppercase tracking-wide mb-1.5">Role</label>
    <select id="role" name="role"
        class="w-full px-3 py-2.5 border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-maroon/20">
        <option value="">-- Select --</option>
        @foreach ($roles as $roleOption)
            <option value="{{ $roleOption->name }}" @selected($currentRole === $roleOption->name)>
                {{ ucwords(str_replace(['-', '_'], ' ', $roleOption->name)) }}
            </option>
        @endforeach
    </select>
    <p class="text-xs text-muted mt-1">Determines which sections of the admin panel this user can access.</p>
    @error('role')
        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>
