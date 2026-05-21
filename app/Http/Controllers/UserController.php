<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // ── ADMIN: Kelola semua user ──────────────────────────────
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('role'))   $query->where('role', $request->role);
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name','like',"%{$request->search}%")
                  ->orWhere('email','like',"%{$request->search}%")
                  ->orWhere('no_anggota','like',"%{$request->search}%");
            });
        }

        $users = $query->latest()->paginate(15)->withQueryString();
        return view('admin.users.index', compact('users'));
    }

    public function create() { return view('admin.users.create'); }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users',
            'password'   => 'required|min:8',
            'role'       => 'required|in:admin,pustakawan,anggota',
            'no_telepon' => 'nullable|string|max:20',
            'alamat'     => 'nullable|string',
        ]);

        $prefix = match($validated['role']) {
            'admin'      => 'ADM',
            'pustakawan' => 'PST',
            default      => 'ANG',
        };
        $count = User::where('role', $validated['role'])->count() + 1;

        User::create([
            ...$validated,
            'password'       => Hash::make($validated['password']),
            'no_anggota'     => $prefix . '-' . str_pad($count, 4, '0', STR_PAD_LEFT),
            'tanggal_daftar' => now()->toDateString(),
            'is_verified'    => true,
            'is_aktif'       => true,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan!');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,'.$user->id,
            'role'       => 'required|in:admin,pustakawan,anggota',
            'no_telepon' => 'nullable|string|max:20',
            'alamat'     => 'nullable|string',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);
        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri!');
        }
        if ($user->peminjamans()->whereIn('status',['dipinjam','terlambat'])->exists()) {
            return back()->with('error', 'User masih memiliki peminjaman aktif!');
        }
        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus!');
    }

    public function toggleAktif(User $user)
    {
        $user->update(['is_aktif' => !$user->is_aktif]);
        $status = $user->is_aktif ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "User berhasil $status!");
    }

    // ── PUSTAKAWAN: Verifikasi anggota ────────────────────────
    public function daftarAnggota(Request $request)
    {
        $query = User::where('role', 'anggota');

        if ($request->filled('status')) {
            $query->where('is_verified', $request->status === 'verified');
        }
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name','like',"%{$request->search}%")
                  ->orWhere('no_anggota','like',"%{$request->search}%");
            });
        }

        $anggota = $query->latest()->paginate(15)->withQueryString();
        return view('pustakawan.anggota.index', compact('anggota'));
    }

    public function verifikasi(User $user)
    {
        abort_if(!$user->isAnggota(), 403);
        $user->update(['is_verified' => true, 'is_aktif' => true]);
        return back()->with('success', "Anggota {$user->name} berhasil diverifikasi!");
    }

    public function nonaktifkan(User $user)
    {
        $user->update(['is_aktif' => false]);
        return back()->with('success', "Anggota {$user->name} dinonaktifkan!");
    }
}