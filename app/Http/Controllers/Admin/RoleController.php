<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Role name that can never be edited or deleted.
     */
    private const PROTECTED_ROLE = 'admin';

    public function index()
    {
        $roles = Role::with('permissions')->get();
        $allPermissions = Permission::all();
        return view('admin.roles.index', compact('roles', 'allPermissions'));
    }

    public function create()
    {
        $permissions = $this->generatePermissionsFromConfig();

        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create([
            'name' => strtolower($request->name),
            'guard_name' => 'web',
        ]);

        $this->syncPermissionsFromIds($role, $request->input('permissions', []));

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role created successfully.');
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);

        if ($this->isProtectedRole($role)) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'This role cannot be edited.');
        }

        $permissions = $this->generatePermissionsFromConfig();
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        if ($this->isProtectedRole($role)) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Admin role cannot be edited');
        }

        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update([
            'name' => strtolower($request->name),
        ]);

        $this->syncPermissionsFromIds($role, $request->input('permissions', []));

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        if ($this->isProtectedRole($role)) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'This role cannot be deleted.');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role deleted successfully.');
    }

    /**
     * Whether this role is locked from edit/delete.
     */
    private function isProtectedRole(Role $role): bool
    {
        return $role->name === self::PROTECTED_ROLE;
    }

    /**
     * Sync a role's permissions from an array of permission IDs.
     * Handles the empty-array case (all checkboxes unchecked) correctly.
     */
    private function syncPermissionsFromIds(Role $role, array $ids): void
    {
        $names = Permission::whereIn('id', $ids)->pluck('name')->toArray();
        $role->syncPermissions($names);
    }

    /**
     * Ensure every module.action pair from config exists as a Permission row,
     * grouped by module for the checkbox UI.
     */
    private function generatePermissionsFromConfig()
    {
        $structure = config('permission.custom_permissions');

        $permissions = [];

        foreach ($structure as $module => $actions) {
            foreach ($actions as $action) {
                $permissions[] = Permission::firstOrCreate([
                    'name' => $module . '.' . $action,
                    'guard_name' => 'web',
                ]);
            }
        }

        return collect($permissions)->groupBy(function ($perm) {
            return explode('.', $perm->name)[0];
        });
    }
}
