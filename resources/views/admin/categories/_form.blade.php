@php
    $isEdit = isset($category);
@endphp

<div class="mb-4">
    <label for="name" class="block text-xs font-semibold text-muted uppercase tracking-wide mb-1.5">Name</label>
    <input id="name" name="name" type="text" required autofocus
        value="{{ old('name', $isEdit ? $category->name : '') }}"
        class="w-full px-3 py-2.5 border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-maroon/20">
    @error('name')
        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label for="description"
        class="block text-xs font-semibold text-muted uppercase tracking-wide mb-1.5">Description</label>
    <textarea id="description" name="description" rows="3"
        class="w-full px-3 py-2.5 border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-maroon/20">{{ old('description', $isEdit ? $category->description : '') }}</textarea>
    @error('description')
        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label for="sort_order" class="block text-xs font-semibold text-muted uppercase tracking-wide mb-1.5">Sort
        Order</label>
    <input id="sort_order" name="sort_order" type="number" min="0" step="1" required
        value="{{ old('sort_order', $isEdit ? $category->sort_order : '') }}"
        class="w-full px-3 py-2.5 border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-maroon/20">
    <p class="text-xs text-muted mt-1">Lower numbers appear first. Use gaps (10, 20, 30...) to make room for
        reordering later.</p>
    @error('sort_order')
        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="mb-2">
    <label class="flex items-center gap-2 text-sm text-body">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $isEdit ? $category->is_active : true))
            class="rounded border-border text-maroon focus:ring-maroon/20">
        Active (shown in dropdowns)
    </label>
    @error('is_active')
        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>
