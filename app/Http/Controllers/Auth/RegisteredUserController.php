<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $jobCategories = \App\Models\JobCategory::orderBy('name')->get();
        $jobTitles     = \App\Models\JobTitle::orderBy('name')->get();

        return view('auth.register', compact('jobCategories', 'jobTitles'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'nik'        => ['required', 'string', 'max:50', Rule::unique('users', 'nik')],
            'phone'      => ['required', 'string', 'max:30', Rule::unique('users', 'phone')],
            'birth_date' => ['required', 'date'],
            'gender'     => ['required', Rule::in(['M', 'F'])],

            'email'      => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users', 'email')],
            'password'   => ['required', 'confirmed', Rules\Password::defaults()],

            'role_slug'  => ['required', Rule::in(['karyawan', 'instructor'])],

            // wajib kalau role = karyawan
            'job_category_id' => ['nullable', 'integer', 'required_if:role_slug,karyawan'],
            'job_title_id'    => ['nullable', 'integer', 'required_if:role_slug,karyawan'],

            // perawat (nanti kita enforce lagi setelah tahu category yang dipilih)
            'jabatan' => ['nullable', 'string', 'max:255'],
            'unit'    => ['nullable', 'string', 'max:255'],
        ]);

        // Ambil role_id dari slug
        $roleId = DB::table('roles')->where('slug', $request->role_slug)->value('id');
        if (!$roleId) abort(500, 'Role belum tersedia. Pastikan roles: karyawan & instructor sudah ada.');

        // Kalau role = karyawan, cek apakah category = Perawat
        $isPerawat = false;
        if ($request->role_slug === 'karyawan' && $request->job_category_id) {
            $catName = DB::table('job_categories')->where('id', $request->job_category_id)->value('name');
            $isPerawat = strtolower(trim($catName ?? '')) === 'perawat';
        }

        // Enforce wajib jabatan & unit kalau perawat
        if ($isPerawat) {
            $request->validate([
                'jabatan' => ['required', 'string', 'max:255'],
                'unit'    => ['required', 'string', 'max:255'],
            ]);
        } else {
            // kalau bukan perawat, kosongin aja biar bersih
            $request->merge(['jabatan' => null, 'unit' => null]);
        }

        $user = User::create([
            'name'       => $request->name,
            'nik'        => $request->nik,
            'email'      => $request->email,
            'phone'      => $request->phone,
            'birth_date' => $request->birth_date,
            'gender'     => $request->gender,

            'role_id'    => $roleId,
            'is_active'  => true,
            'password'   => Hash::make($request->password),

            // simpan job fields (boleh null kalau instructor)
            'job_category_id' => $request->role_slug === 'karyawan' ? $request->job_category_id : null,
            'job_title_id'    => $request->role_slug === 'karyawan' ? $request->job_title_id : null,
            'jabatan'         => $request->role_slug === 'karyawan' ? $request->jabatan : null,
            'unit'            => $request->role_slug === 'karyawan' ? $request->unit : null,
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
