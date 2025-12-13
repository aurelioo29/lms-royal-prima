<?php

namespace App\Http\Controllers;

use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->get('q');

        $roles = Role::query()
            ->when(
                $q,
                fn($query) => $query
                    ->where('name', 'like', "%{$q}%")
                    ->orWhere('slug', 'like', "%{$q}%")
            )
            ->orderByDesc('level')
            ->paginate(10)
            ->withQueryString();

        return view('roles.index', compact('roles', 'q'));
    }

    public function create(): View
    {
        return view('roles.create');
    }

    public function store(StoreRoleRequest $request): RedirectResponse
    {
        Role::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'level' => $request->level,
            'can_manage_users' => (bool) $request->boolean('can_manage_users'),
        ]);

        return redirect()->route('roles.index')->with('success', 'Role berhasil dibuat.');
    }

    public function edit(Role $role): View
    {
        return view('roles.edit', compact('role'));
    }

    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        $role->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'level' => $request->level,
            'can_manage_users' => (bool) $request->boolean('can_manage_users'),
        ]);

        return redirect()->route('roles.index')->with('success', 'Role berhasil diupdate.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        // Optional safety: jangan hapus role yang punya user
        if ($role->users()->exists()) {
            return back()->with('error', 'Role tidak bisa dihapus karena masih dipakai oleh user.');
        }

        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role berhasil dihapus.');
    }

    // show() opsional, kalau kamu nggak butuh, boleh kosong atau hapus dari route (resource only)
    public function show(Role $role): View
    {
        return view('roles.show', compact('role'));
    }
}
