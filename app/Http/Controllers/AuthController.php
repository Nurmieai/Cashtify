<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function landing()
    {
        if (Auth::check() && Auth::user()->hasRole('Penjual')) {
            return redirect()->route('dashboard');
        }

        return view('livewire.user.landing');
    }

    public function showLoginForm()
    {
        if (Auth::check()) {

            if (Auth::user()->hasRole('Penjual')) {
                return redirect()->route('dashboard');
            }

            return redirect()->route('landing');
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
            $user = Auth::user();

            // REDIRECT BERDASARKAN ROLE
            if ($user->hasRole('Penjual')) {
                return redirect()->route('dashboard');
            }

            if ($user->hasRole('Pembeli')) {
                return redirect()->route('landing');
            }

            return redirect()->route('landing');
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
            'email'    => [
                'required',
                'regex:/^[a-zA-Z0-9._%+-]+@(gmail\.com|mine\.com|keren\.com)$/'
            ],
            'password' => ['required', 'confirmed', 'min:5'],
        ]);

        $user = User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'usr_created_by'=> null,
        ]);

        // DEFAULT ROLE PEMBELI
        $user->assignRole('Pembeli');

        Auth::login($user);

        return redirect()->route('landing');
    }

    public function dashboard()
    {
        if (!Auth::check() || !Auth::user()->hasRole('Penjual')) {
            return redirect()->route('landing');
        }

        return view('livewire.admin.dashboard');
    }

    public function usersPage(Request $request)
    {
        $search = $request->search;

        $users = User::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('usr_created_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        return view('livewire.admin.users.index', compact('users'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing');
    }
}
