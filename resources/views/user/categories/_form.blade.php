@php
    $isEdit = isset($category);
@endphp

<div class="u-field">
    <label for="name" class="u-field-label">Name</label>
    <input id="name" name="name" type="text" required autofocus
        value="{{ old('name', $isEdit ? $category->name : '') }}" class="u-field-input">
    @error('name')
        <p class="u-field-error">{{ $message }}</p>
    @enderror
</div>

<div class="u-field">
    <label for="description" class="u-field-label">Description</label>
    <textarea id="description" name="description" rows="3" class="u-field-input u-field-textarea">{{ old('description', $isEdit ? $category->description : '') }}</textarea>
    @error('description')
        <p class="u-field-error">{{ $message }}</p>
    @enderror
</div>

<div class="u-field">
    <label for="sort_order" class="u-field-label">Sort Order</label>
    <input id="sort_order" name="sort_order" type="number" min="0" step="1" required
        value="{{ old('sort_order', $isEdit ? $category->sort_order : '') }}" class="u-field-input">
    <p class="u-field-hint">Lower numbers appear first. Use gaps (10, 20, 30...) to make room for reordering later.</p>
    @error('sort_order')
        <p class="u-field-error">{{ $message }}</p>
    @enderror
</div>

<div class="u-field u-field-checkbox-row">
    <label class="u-checkbox-label">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $isEdit ? $category->is_active : true)) class="u-checkbox">
        Active (shown in dropdowns)
    </label>
    @error('is_active')
        <p class="u-field-error">{{ $message }}</p>
    @enderror
</div>

<style>
    .u-field {
        margin-bottom: 18px;
    }

    .u-field-label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: var(--ink);
        margin-bottom: 7px;
    }

    .u-field-input {
        width: 100%;
        padding: 12px 14px;
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        color: var(--ink);
        background: var(--paper);
        border: 1.5px solid var(--line);
        border-radius: 10px;
        outline: none;
        transition: border-color 0.15s, box-shadow 0.15s;
    }

    .u-field-input:focus {
        border-color: var(--indigo);
        box-shadow: 0 0 0 4px rgba(76, 63, 224, 0.12);
    }

    .u-field-textarea {
        resize: vertical;
        min-height: 84px;
        font-family: 'Inter', sans-serif;
    }

    .u-field-hint {
        font-size: 12px;
        color: var(--ink-soft);
        margin-top: 6px;
        line-height: 1.5;
    }

    .u-field-error {
        font-size: 12px;
        color: var(--coral-deep);
        margin-top: 6px;
    }

    .u-field-checkbox-row {
        margin-bottom: 4px;
    }

    .u-checkbox-label {
        display: flex;
        align-items: center;
        gap: 9px;
        font-size: 14px;
        color: var(--ink-soft);
        cursor: pointer;
    }

    .u-checkbox {
        width: 16px;
        height: 16px;
        accent-color: var(--indigo);
        cursor: pointer;
    }
</style>
