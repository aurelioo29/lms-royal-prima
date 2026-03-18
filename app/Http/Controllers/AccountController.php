<?php

namespace App\Http\Controllers;

use App\Http\Requests\Account\StoreAccountRequest;
use App\Http\Requests\Account\UpdateAccountRequest;
use App\Models\JobCategory;
use App\Models\JobTitle;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->get('q');
        $role = $request->get('role');
        $status = $request->get('status');

        $accounts = User::query()
            ->with(['role', 'jobCategory', 'jobTitle'])
            ->when($q, function ($query) use ($q) {
                $query->where(function ($qBuilder) use ($q) {
                    $qBuilder->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('nik', 'like', "%{$q}%")
                        ->orWhere('phone', 'like', "%{$q}%");
                });
            })
            ->when($role, function ($query) use ($role) {
                $query->whereHas('role', function ($qBuilder) use ($role) {
                    $qBuilder->where('slug', $role);
                });
            })
            ->when($status !== null && $status !== '', function ($query) use ($status) {
                $query->where('is_active', (bool) $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $roles = Role::orderBy('level')->get();

        return view('accounts.index', compact('accounts', 'roles', 'q', 'role', 'status'));
    }

    public function create(): View
    {
        $roles = Role::orderBy('level')->get();
        $jobCategories = JobCategory::orderBy('name')->get();
        $jobTitles = JobTitle::orderBy('name')->get();

        return view('accounts.create', compact('roles', 'jobCategories', 'jobTitles'));
    }

    public function store(StoreAccountRequest $request): RedirectResponse
    {
        $role = Role::findOrFail($request->role_id);

        $plainPassword = $request->filled('password')
            ? $request->password
            : 'Royal12345'; // or use Str::random(10)

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($plainPassword),
            'role_id' => $role->id,

            'nik' => $request->nik,
            'phone' => $request->phone,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'job_category_id' => $request->job_category_id,
            'job_title_id' => $request->job_title_id,
            'jabatan' => $request->jabatan,
            'unit' => $request->unit,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('accounts.index')
            ->with('success', "Account {$user->name} created successfully. Default password: {$plainPassword}");
    }

    public function show(User $account): View
    {
        $account->load(['role', 'jobCategory', 'jobTitle']);

        return view('accounts.show', compact('account'));
    }

    public function edit(User $account): View
    {
        $roles = Role::orderBy('level')->get();
        $jobCategories = JobCategory::orderBy('name')->get();
        $jobTitles = JobTitle::orderBy('name')->get();

        return view('accounts.edit', compact('account', 'roles', 'jobCategories', 'jobTitles'));
    }

    public function update(UpdateAccountRequest $request, User $account): RedirectResponse
    {
        $role = Role::findOrFail($request->role_id);

        $titleName = null;
        $isPerawat = false;

        if ($request->job_title_id) {
            $titleName = JobTitle::where('id', $request->job_title_id)->value('name');
            $isPerawat = strtolower(trim($titleName ?? '')) === 'perawat';
        }

        $payload = [
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $role->id,
            'nik' => $request->nik,
            'phone' => $request->phone,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'job_category_id' => $request->job_category_id,
            'job_title_id' => $request->job_title_id,
            'jabatan' => $isPerawat ? $request->jabatan : null,
            'unit' => $isPerawat ? $request->unit : null,
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->filled('password')) {
            $payload['password'] = Hash::make($request->password);
        }

        $account->update($payload);

        return redirect()
            ->route('accounts.index')
            ->with('success', 'Akun berhasil diupdate.');
    }

    public function destroy(User $account): RedirectResponse
    {
        if (auth()->id() === $account->id) {
            return back()->with('error', 'Kamu tidak bisa menghapus akunmu sendiri.');
        }

        $account->delete();

        return redirect()
            ->route('accounts.index')
            ->with('success', 'Akun berhasil dihapus.');
    }
}
