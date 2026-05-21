<?php
namespace App\Http\Controllers;

use App\Models\{Buku, Peminjaman};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnggotaController extends Controller
{
    public function formPinjam(Buku $buku)
    {
        abort_if($buku->stok_tersedia < 1, 404, 'Buku tidak tersedia');
        return view('anggota.form-pinjam', compact('buku'));
    }

    public function ajukanPinjam(Request $request)
    {
        $validated = $request->validate([
            'buku_id' => 'required|exists:bukus,id',
            'catatan' => 'nullable|string|max:255',
        ]);

        $user = auth()->user();
        $buku = Buku::findOrFail($validated['buku_id']);

        if ($buku->stok_tersedia < 1) {
            return back()->with('error', 'Maaf, stok buku habis!');
        }

        $sudahPinjam = $user->peminjamans()
            ->where('buku_id', $buku->id)
            ->whereIn('status', ['dipinjam','terlambat'])->exists();

        if ($sudahPinjam) {
            return back()->with('error', 'Anda sudah meminjam buku ini!');
        }

        DB::transaction(function () use ($user, $buku, $validated) {
            Peminjaman::create([
                'user_id'             => $user->id,
                'buku_id'             => $buku->id,
                'tanggal_pinjam'      => now()->toDateString(),
                'tanggal_jatuh_tempo' => now()->addDays(14)->toDateString(),
                'status'              => 'dipinjam',
                'catatan'             => $validated['catatan'] ?? null,
            ]);
            $buku->decrement('stok_tersedia');
        });

        return redirect()->route('anggota.riwayat')
            ->with('success', 'Peminjaman berhasil! Kembalikan sebelum '
                . now()->addDays(14)->format('d M Y') . '.');
    }

    public function riwayat()
    {
        $peminjamans = auth()->user()->peminjamans()
            ->with('buku.kategori')->latest()->paginate(10);
        return view('anggota.riwayat', compact('peminjamans'));
    }

    public function ajukanKembali(Peminjaman $peminjaman)
    {
        abort_if($peminjaman->user_id !== auth()->id(), 403);
        abort_if($peminjaman->status === 'dikembalikan', 400, 'Sudah dikembalikan');

        $denda = $peminjaman->denda_hitung;

        DB::transaction(function () use ($peminjaman, $denda) {
            $peminjaman->update([
                'status'          => 'dikembalikan',
                'tanggal_kembali' => now()->toDateString(),
                'denda'           => $denda,
            ]);
            $peminjaman->buku->increment('stok_tersedia');
        });

        $msg = 'Buku berhasil dikembalikan!';
        if ($denda > 0) $msg .= ' Denda: Rp ' . number_format($denda, 0, ',', '.');

        return redirect()->route('anggota.riwayat')->with('success', $msg);
    }
}