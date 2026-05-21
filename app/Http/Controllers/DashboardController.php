<?php
namespace App\Http\Controllers;

use App\Models\{Buku, User, Peminjaman};

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            $data = [
                'total_anggota'    => User::where('role','anggota')->count(),
                'total_pustakawan' => User::where('role','pustakawan')->count(),
                'belum_verifikasi' => User::where('role','anggota')->where('is_verified', false)->count(),
                'total_buku'       => Buku::count(),
                'user_terbaru'     => User::whereIn('role',['anggota','pustakawan'])->latest()->take(5)->get(),
            ];
            return view('dashboard.admin', compact('data'));
        }

        if ($user->isPustakawan()) {
            $data = [
                'total_buku'       => Buku::count(),
                'dipinjam_aktif'   => Peminjaman::whereIn('status',['dipinjam','terlambat'])->count(),
                'terlambat'        => Peminjaman::where('status','terlambat')->count(),
                'belum_verifikasi' => User::where('role','anggota')->where('is_verified', false)->count(),
                'peminjaman_baru'  => Peminjaman::with(['user','buku'])->latest()->take(6)->get(),
            ];
            return view('dashboard.pustakawan', compact('data'));
        }

        $data = [
            'is_verified'   => $user->is_verified,
            'dipinjam'      => $user->peminjamans()->whereIn('status',['dipinjam','terlambat'])->with('buku')->get(),
            'riwayat'       => $user->peminjamans()->where('status','dikembalikan')->latest()->take(3)->with('buku')->get(),
            'buku_tersedia' => Buku::tersedia()->latest()->take(6)->get(),
        ];
        return view('dashboard.anggota', compact('data'));
    }
}