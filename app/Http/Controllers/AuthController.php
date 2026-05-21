<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()    { return view('auth.login'); }
    public function showRegister() { return view('auth.register'); }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->with('error', 'Email atau password salah!')->onlyInput('email');
        }

        $user = Auth::user();
        if (!$user->is_aktif) {
            Auth::logout();
            return back()->with('error', 'Akun Anda dinonaktifkan. Hubungi admin.');
        }

        $request->session()->regenerate();
        return redirect()->intended(route('dashboard'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users',
            'password'   => 'required|min:8|confirmed',
            'no_telepon' => 'nullable|string|max:20',
            'alamat'     => 'nullable|string',
        ]);

        $count = User::where('role', 'anggota')->count() + 1;

        $user = User::create([
            ...$validated,
            'password'       => Hash::make($validated['password']),
            'role'           => 'anggota',
            'no_anggota'     => 'ANG-' . str_pad($count, 4, '0', STR_PAD_LEFT),
            'tanggal_daftar' => now()->toDateString(),
            'is_verified'    => false,
        ]);

        Auth::login($user);
        return redirect()->route('dashboard')
            ->with('info', 'Registrasi berhasil! Akun Anda menunggu verifikasi pustakawan.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}