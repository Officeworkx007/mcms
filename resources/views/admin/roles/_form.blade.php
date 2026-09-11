@php
    $isEdit = isset($role);
    $checkedPermissions = old('permissions', $isEdit ? $rolePermissions ?? [] : []);
@endphp

<div class="mb-6">
    <label for="name" class="block text-xs font-semibold text-muted uppercase tracking-wide mb-1.5">Role Name</label>
    <input id="name" name="name" type="text" value="{{ old('name', $isEdit ? $role->name : '') }}"
        class="w-full px-3 py-2.5 border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-maroon/20"
        placeholder="e.g. mediator-clerk">
    <p class="text-xs text-muted mt-1">Lowercase, hyphenated. This name is used internally for permission checks.</p>
    @error('name')
        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="mb-2">
    <label class="block text-xs font-semibold text-muted uppercase tracking-wide mb-3">Permissions</label>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach ($permissions as $module => $modulePermissions)
            @php
                $moduleLabel = ucwords(str_replace('_', ' ', $module));
                $modulePermissionIds = $modulePermissions->pluck('id')->all();
            @endphp
            <div class="border border-border rounded-md p-3.5">
                <div class="flex items-center justify-between mb-2.5 pb-2 border-b border-border/70">
                    <span class="text-sm font-semibold text-ink">{{ $moduleLabel }}</span>
                    <label class="flex items-center gap-1.5 cursor-pointer">
                        <input type="checkbox"
                            class="js-module-toggle h-3.5 w-3.5 rounded border-border text-maroon focus:ring-maroon/30"
                            data-module="{{ $module }}">
                        <span class="text-[10.5px] font-medium text-muted uppercase tracking-wide">All</span>
                    </label>
                </div>

                <div class="space-y-1.5">
                    @foreach ($modulePermissions as $permission)
                        @php
                            $actionLabel = ucfirst(
                                str_replace('_', ' ', explode('.', $permission->name)[1] ?? $permission->name),
                            );
                        @endphp
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                class="js-permission-checkbox h-3.5 w-3.5 rounded border-border text-maroon focus:ring-maroon/30"
                                data-module="{{ $module }}" @checked(in_array($permission->id, $checkedPermissions))>
                            <span class="text-sm text-body">{{ $actionLabel }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    @error('permissions')
        <p class="text-red-600 text-xs mt-2">{{ $message }}</p>
    @enderror
    @error('permissions.*')
        <p class="text-red-600 text-xs mt-2">{{ $message }}</p>
    @enderror
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function syncModuleToggle(moduleToggle) {
            const module = moduleToggle.dataset.module;
            const boxes = document.querySelectorAll(`.js-permission-checkbox[data-module="${module}"]`);
            const allChecked = boxes.length > 0 && Array.from(boxes).every(b => b.checked);
            moduleToggle.checked = allChecked;
        }

        document.querySelectorAll('.js-module-toggle').forEach(function(toggle) {
            syncModuleToggle(toggle);

            toggle.addEventListener('change', function() {
                const module = this.dataset.module;
                document.querySelectorAll(`.js-permission-checkbox[data-module="${module}"]`)
                    .forEach(box => box.checked = toggle.checked);
            });
        });

        document.querySelectorAll('.js-permission-checkbox').forEach(function(box) {
            box.addEventListener('change', function() {
                const moduleToggle = document.querySelector(
                    `.js-module-toggle[data-module="${this.dataset.module}"]`);
                if (moduleToggle) syncModuleToggle(moduleToggle);
            });
        });
    });
</script>
