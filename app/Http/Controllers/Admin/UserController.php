<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::with('roles')
            ->oldest()
            ->paginate(40);

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        return view('admin.users.create', $this->dropdownData());
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        $user->syncRoles([$validated['role']]);

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'User created.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', array_merge(
            ['user' => $user],
            $this->dropdownData()
        ));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $this->validated($request, $user);

        $user->name = $validated['name'];
        $user->username = $validated['username'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = $validated['password'];
        }

        $user->save();

        $user->syncRoles([$validated['role']]);

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'User updated.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return back()->with('status', 'User deleted.');
    }

    /**
     * Role options for the create/edit form dropdown.
     */
    private function dropdownData(): array
    {
        return [
            'roles' => Role::orderBy('name')->get(),
        ];
    }

    private function validated(Request $request, ?User $user = null): array
    {
        $userId = $user?->id;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:50', 'alpha_dash', 'unique:users,username,' . $userId],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $userId],
            // Password is required on create; on update, leave blank to keep the current one.
            'password' => [$user ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'exists:roles,name'],
        ]);

        return $validated;
    }
}
