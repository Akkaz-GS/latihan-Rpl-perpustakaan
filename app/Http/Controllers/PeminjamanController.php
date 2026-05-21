<?php
namespace App\Http\Controllers;

use App\Models\{Buku, User, Peminjaman};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        Peminjaman::where('status','dipinjam')
            ->where('tanggal_jatuh_tempo', '<', now()->toDateString())
            ->update(['status' => 'terlambat']);

        $query = Peminjaman::with(['user','buku']);

        if ($request->filled('status'))  $query->where('status', $request->status);
        if ($request->filled('tanggal')) $query->whereDate('tanggal_pinjam', $request->tanggal);
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->whereHas('user', fn($u) => $u->where('name','like',"%{$request->search}%"))
                  ->orWhereHas('buku', fn($b) => $b->where('judul','like',"%{$request->search}%"))
                  ->orWhere('kode_peminjaman','like',"%{$request->search}%");
            });
        }

        $peminjamans = $query->latest()->paginate(15)->withQueryString();
        return view('pustakawan.peminjaman.index', compact('peminjamans'));
    }

    public function create()
    {
        $anggota = User::where('role','anggota')
                       ->where('is_verified', true)
                       ->where('is_aktif', true)->get();
        $bukus   = Buku::tersedia()->get();
        return view('pustakawan.peminjaman.create', compact('anggota','bukus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'             => 'required|exists:users,id',
            'buku_id'             => 'required|exists:bukus,id',
            'tanggal_pinjam'      => 'required|date',
            'tanggal_jatuh_tempo' => 'required|date|after:tanggal_pinjam',
            'catatan'             => 'nullable|string',
        ]);

        $buku = Buku::findOrFail($validated['buku_id']);
        if ($buku->stok_tersedia < 1) {
            return back()->with('error','Stok buku habis!');
        }

        $sudah = Peminjaman::where('user_id', $validated['user_id'])
            ->where('buku_id', $validated['buku_id'])
            ->whereIn('status',['dipinjam','terlambat'])->exists();
        if ($sudah) {
            return back()->with('error','Anggota sudah meminjam buku ini!');
        }

        $validated['diproses_oleh'] = auth()->id();

        DB::transaction(function () use ($validated, $buku) {
            Peminjaman::create($validated);
            $buku->decrement('stok_tersedia');
        });

        return redirect()->route('pustakawan.peminjaman.index')
            ->with('success','Peminjaman berhasil dicatat!');
    }

    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load(['user','buku.kategori','diprosesoleh']);
        return view('pustakawan.peminjaman.show', compact('peminjaman'));
    }

    public function kembalikan(Peminjaman $peminjaman)
    {
        if ($peminjaman->status === 'dikembalikan') {
            return back()->with('error','Buku sudah dikembalikan!');
        }

        $denda = $peminjaman->denda_hitung;

        DB::transaction(function () use ($peminjaman, $denda) {
            $peminjaman->update([
                'status'          => 'dikembalikan',
                'tanggal_kembali' => now()->toDateString(),
                'denda'           => $denda,
                'diproses_oleh'   => auth()->id(),
            ]);
            $peminjaman->buku->increment('stok_tersedia');
        });

        $msg = 'Buku berhasil dikembalikan!';
        if ($denda > 0) $msg .= ' Denda: Rp ' . number_format($denda, 0, ',', '.');

        return redirect()->route('pustakawan.peminjaman.index')->with('success', $msg);
    }
}