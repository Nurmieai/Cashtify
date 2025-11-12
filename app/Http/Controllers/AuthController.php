<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function landing()
    {
        // Kalau sudah login dan bukan admin, tetap di landing
        // Kalau admin, arahkan ke dashboard
        if (Auth::check()) {
            if (Auth::user()->is_admin) {
                return redirect()->route('dashboard');
            }
        }

        return view('livewire.user.landing');
    }

    public function showLoginForm()
    {
        if (Auth::check()) {
            if (Auth::user()->is_admin) {
                return redirect()->route('dashboard');
            } else {
                return redirect()->route('landing');
            }
        }

        return view('livewire.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Bedakan berdasarkan role
            if (Auth::user()->is_admin) {
                return redirect()->route('dashboard');
            } else {
                return redirect()->route('landing');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('landing');
        }

        return view('livewire.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email' => ['required','regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/'],
            'password' => ['required', 'confirmed', 'min:5'],
        ]);

        $user = User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'usr_created_by'=> null,
            'is_admin'      => false, // default user biasa
        ]);

        Auth::login($user);

        return redirect()->route('landing');
    }

    public function dashboard()
    {
        // Hanya admin yang boleh ke dashboard
        if (!Auth::check() || !Auth::user()->is_admin) {
            return redirect()->route('landing');
        }

        return view('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing');
    }
}
