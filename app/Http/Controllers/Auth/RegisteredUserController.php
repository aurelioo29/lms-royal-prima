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
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            // dari form
            'name'       => ['required', 'string', 'max:255'],
            'nik'        => ['required', 'string', 'max:50', Rule::unique('users', 'nik')],
            'phone'      => ['required', 'string', 'max:30', Rule::unique('users', 'phone')],
            'birth_date' => ['required', 'date'],
            'gender'     => ['required', Rule::in(['M', 'F'])],

            // auth
            'email'      => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users', 'email')],
            'password'   => ['required', 'confirmed', Rules\Password::defaults()],

            // hidden (role narasumber)
            'role_slug'  => ['required', Rule::in(['instructor'])],
        ]);

        // Ambil role_id narasumber dari slug
        $roleId = DB::table('roles')->where('slug', 'instructor')->value('id');

        if (!$roleId) {
            abort(500, 'Role Narasumber (instructor) belum tersedia. Jalankan seeder roles dulu.');
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
        ]);

        event(new Registered($user));
        Auth::login($user);

        // arahkan kemana? sementara ke dashboard
        return redirect()->route('dashboard');
    }
}
