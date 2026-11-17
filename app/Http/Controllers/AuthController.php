<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
        public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'nim' => ['required', 'string', 'max:20', 'unique:members,nim'],
            'email' => ['required', 'email', 'max:100', 'ends_with:@pens.ac.id', 'unique:users,email', 'unique:members,email'],
            'nomor_telepon' => ['required', 'string', 'max:15'],
            'alamat' => ['required', 'string', 'max:2000'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['nama'],
                'email' => $validated['email'],
                'password' => $validated['password'],
                'role' => 'member',
            ]);

            Member::create([
                'user_id' => $user->id,
                'nama' => $validated['nama'],
                'nim' => $validated['nim'],
                'email' => $validated['email'],
                'nomor_telepon' => $validated['nomor_telepon'],
                'alamat' => $validated['alamat'],
                'status' => 'aktif',
            ]);

            return $user;
        });

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('member.dashboard')
            ->with('success', 'Pendaftaran berhasil. Selamat datang, '.$user->name.'.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'Email atau password salah.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'))
        ->with('success', 'Login berhasil, selamat datang ' . Auth::user()->name . '.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Logout berhasil.');
    }
}
