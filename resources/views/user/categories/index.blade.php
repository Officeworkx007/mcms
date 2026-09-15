@extends('user.layouts.master')

@section('title', 'Categories')

@section('content')

    <div class="u-page-head">
        <div class="u-page-head-left">
            <span class="u-page-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M4 6h16M4 12h16M4 18h7" />
                </svg>
            </span>
            <div>
                <h1>Categories</h1>
                <p class="u-page-subtitle">Nature of case, used for filtering and reports.</p>
            </div>
        </div>
        @can('case_category.create')
            <a href="{{ route('user.categories.create') }}" class="u-btn-primary u-btn-sm">+ Add Category</a>
        @endcan
    </div>

    @if (session('status'))
        <div class="u-alert u-alert-success">{{ session('status') }}</div>
    @endif

    @if (session('error'))
        <div class="u-alert u-alert-error">{{ session('error') }}</div>
    @endif

    <form method="GET" action="{{ route('user.categories.index') }}" class="u-filter-row">
        <label for="mediator_id" class="u-filter-label">Filter by mediator</label>
        <select id="mediator_id" name="mediator_id" onchange="this.form.submit()" class="u-field-input u-filter-select">
            <option value="">All mediators (overall)</option>
            @foreach ($mediators as $mediator)
                <option value="{{ $mediator->id }}" @selected($mediatorId == $mediator->id)>
                    {{ $mediator->advocate_name }}
                </option>
            @endforeach
        </select>

        @if ($mediatorId)
            <a href="{{ route('user.categories.index') }}" class="u-filter-clear">Clear filter</a>
        @endif
    </form>

    <div class="u-table-card">
        <div class="u-table-scroll">
            <table class="u-table">
                <thead>
                    <tr>
                        <th class="u-th">S.No</th>
                        <th class="u-th">Name</th>
                        <th class="u-th">Description</th>
                        <th class="u-th">Cases</th>
                        <th class="u-th">Pending</th>
                        <th class="u-th">Settled</th>
                        <th class="u-th">Unsettled</th>
                        <th class="u-th">Status</th>
                        <th class="u-th u-th-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr class="u-tr">
                            <td class="u-td u-td-muted">{{ $categories->firstItem() + $loop->index }}</td>
                            <td class="u-td u-td-strong">{{ $category->name }}</td>
                            <td class="u-td">{{ \Illuminate\Support\Str::limit($category->description, 80) ?? '—' }}</td>
                            <td class="u-td u-td-strong">{{ $category->cases_count }}</td>
                            <td class="u-td" style="color: var(--amber);">{{ $category->pending_count }}</td>
                            <td class="u-td" style="color: #0F9D74;">{{ $category->settled_count }}</td>
                            <td class="u-td" style="color: var(--coral-deep);">{{ $category->unsettled_count }}</td>
                            <td class="u-td">
                                @if ($category->is_active)
                                    <span class="u-badge u-badge-active">Active</span>
                                @else
                                    <span class="u-badge u-badge-inactive">Inactive</span>
                                @endif
                            </td>
                            <td class="u-td u-td-right">
                                @can('case_category.edit')
                                    <a href="{{ route('user.categories.edit', $category) }}" class="u-link-edit">Edit</a>
                                @endcan
                                @can('case_category.delete')
                                    <form action="{{ route('user.categories.destroy', $category) }}" method="POST"
                                        class="u-inline-form"
                                        onsubmit="return confirm('Delete this category? Cases using it will keep their other details, but lose this category tag.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="u-link-delete">Delete</button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="u-td-empty">No categories yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="u-pagination">
        {{ $categories->links() }}
    </div>

@endsection

@push('styles')
    <style>
        .u-page-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 22px;
            animation: uFadeUp 0.5s cubic-bezier(.2, .8, .2, 1);
        }

        .u-page-head-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .u-page-icon {
            width: 22px;
            height: 22px;
            color: var(--indigo);
            display: flex;
            flex-shrink: 0;
        }

        .u-page-icon svg {
            width: 100%;
            height: 100%;
        }

        .u-page-head h1 {
            font-size: 17px;
            font-weight: 700;
            letter-spacing: -0.01em;
        }

        .u-page-subtitle {
            font-size: 12px;
            color: var(--ink-soft);
            margin-top: 1px;
        }

        .u-btn-primary {
            background: var(--coral);
            color: #fff;
            border: none;
            font-size: 14px;
            font-weight: 600;
            padding: 12px 22px;
            border-radius: 10px;
            cursor: pointer;
            box-shadow: 0 10px 24px -8px rgba(255, 107, 74, 0.45);
            transition: background 0.15s, transform 0.15s;
            display: inline-block;
        }

        .u-btn-primary:hover {
            background: var(--coral-deep);
        }

        .u-btn-primary:active {
            transform: translateY(1px);
        }

        .u-btn-sm {
            padding: 10px 18px;
            font-size: 13.5px;
        }

        .u-alert {
            padding: 13px 16px;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 500;
            margin-bottom: 18px;
            animation: uFadeUp 0.4s ease;
        }

        .u-alert-success {
            background: rgba(34, 211, 178, 0.12);
            color: #0F9D74;
            border: 1px solid rgba(34, 211, 178, 0.3);
        }

        .u-alert-error {
            background: rgba(255, 107, 74, 0.12);
            color: var(--coral-deep);
            border: 1px solid rgba(255, 107, 74, 0.3);
        }

        .u-filter-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 18px;
            flex-wrap: wrap;
        }

        .u-filter-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--ink-soft);
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        .u-field-input {
            padding: 10px 13px;
            font-family: 'Inter', sans-serif;
            font-size: 13.5px;
            color: var(--ink);
            background: var(--paper-raised);
            border: 1.5px solid var(--line);
            border-radius: 9px;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }

        .u-field-input:focus {
            border-color: var(--indigo);
            box-shadow: 0 0 0 4px rgba(76, 63, 224, 0.12);
        }

        .u-filter-select {
            min-width: 220px;
        }

        .u-filter-clear {
            font-size: 13px;
            font-weight: 600;
            color: var(--indigo);
        }

        .u-filter-clear:hover {
            text-decoration: underline;
        }

        .u-table-card {
            background: var(--paper-raised);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            animation: uFadeUp 0.5s cubic-bezier(.2, .8, .2, 1) 0.08s backwards;
        }

        .u-table-scroll {
            overflow-x: auto;
        }

        .u-table {
            width: 100%;
            font-size: 13.5px;
            border-collapse: collapse;
        }

        .u-th {
            text-align: left;
            padding: 12px 16px;
            font-size: 10.5px;
            font-weight: 700;
            color: var(--ink-soft);
            text-transform: uppercase;
            letter-spacing: 0.03em;
            border-bottom: 1px solid var(--line);
            background: rgba(76, 63, 224, 0.03);
        }

        .u-th-right {
            text-align: right;
        }

        .u-tr {
            border-bottom: 1px solid var(--line);
            transition: background 0.15s;
        }

        .u-tr:last-child {
            border-bottom: none;
        }

        .u-tr:hover {
            background: rgba(76, 63, 224, 0.03);
        }

        .u-td {
            padding: 12px 16px;
            color: var(--ink-soft);
            vertical-align: middle;
        }

        .u-td-strong {
            color: var(--ink);
            font-weight: 600;
        }

        .u-td-muted {
            color: var(--ink-soft);
        }

        .u-td-right {
            text-align: right;
            white-space: nowrap;
        }

        .u-td-empty {
            padding: 40px 16px;
            text-align: center;
            color: var(--ink-soft);
        }

        .u-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 100px;
            font-size: 11.5px;
            font-weight: 600;
            border: 1px solid;
        }

        .u-badge-active {
            background: rgba(34, 211, 178, 0.12);
            color: #0F9D74;
            border-color: rgba(34, 211, 178, 0.3);
        }

        .u-badge-inactive {
            background: rgba(75, 77, 107, 0.08);
            color: var(--ink-soft);
            border-color: var(--line);
        }

        .u-link-edit {
            color: var(--indigo);
            font-weight: 600;
            font-size: 13px;
        }

        .u-link-edit:hover {
            text-decoration: underline;
        }

        .u-inline-form {
            display: inline;
            margin-left: 12px;
        }

        .u-link-delete {
            color: var(--coral-deep);
            font-weight: 600;
            font-size: 13px;
            background: none;
            border: none;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
        }

        .u-link-delete:hover {
            text-decoration: underline;
        }

        .u-pagination {
            margin-top: 18px;
        }

        @keyframes uFadeUp {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endpush
