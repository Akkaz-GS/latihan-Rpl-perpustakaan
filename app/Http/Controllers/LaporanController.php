<?php
namespace App\Http\Controllers;

use App\Models\{Buku, User, Peminjaman};
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        $stats = [
            'total_buku'         => Buku::count(),
            'total_anggota'      => User::where('role','anggota')->count(),
            'dipinjam_aktif'     => Peminjaman::whereIn('status',['dipinjam','terlambat'])->count(),
            'terlambat'          => Peminjaman::where('status','terlambat')->count(),
            'dikembalikan_bulan' => Peminjaman::where('status','dikembalikan')
                                    ->whereMonth('tanggal_kembali', now()->month)->count(),
            'denda_bulan'        => Peminjaman::where('status','dikembalikan')
                                    ->whereMonth('tanggal_kembali', now()->month)->sum('denda'),
            'buku_populer'       => Buku::withCount('peminjamans')
                                    ->orderByDesc('peminjamans_count')->take(5)->get(),
            'peminjaman_per_bulan' => Peminjaman::selectRaw('MONTH(tanggal_pinjam) as bulan, COUNT(*) as total')
                                    ->whereYear('tanggal_pinjam', now()->year)
                                    ->groupBy('bulan')->orderBy('bulan')->get(),
            'anggota_aktif'      => User::where('role','anggota')
                                    ->withCount(['peminjamans' => fn($q) =>
                                        $q->whereIn('status',['dipinjam','terlambat'])
                                    ])->orderByDesc('peminjamans_count')->take(5)->get(),
        ];

        return view('laporan.index', compact('stats'));
    }
}