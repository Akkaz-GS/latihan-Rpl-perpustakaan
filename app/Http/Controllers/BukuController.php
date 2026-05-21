<?php
namespace App\Http\Controllers;

use App\Models\{Buku, Kategori};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $query = Buku::with('kategori');

        if ($request->filled('search'))   $query->cari($request->search);
        if ($request->filled('kategori')) $query->where('kategori_id', $request->kategori);
        if ($request->filled('status')) {
            if ($request->status === 'tersedia') $query->tersedia();
            if ($request->status === 'habis')    $query->where('stok_tersedia', 0);
        }

        $bukus     = $query->latest()->paginate(12)->withQueryString();
        $kategoris = Kategori::all();

        return view('buku.index', compact('bukus', 'kategoris'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('buku.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'        => 'required|string|max:255',
            'kategori_id'  => 'required|exists:kategoris,id',
            'pengarang'    => 'required|string|max:255',
            'penerbit'     => 'required|string|max:255',
            'tahun_terbit' => 'required|digits:4|integer|min:1900|max:'.date('Y'),
            'isbn'         => 'nullable|string|max:20|unique:bukus',
            'stok'         => 'required|integer|min:0',
            'deskripsi'    => 'nullable|string',
            'rak'          => 'nullable|string|max:50',
            'cover'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('cover')) {
            $validated['cover'] = $request->file('cover')->store('covers', 'public');
        }

        Buku::create($validated);
        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    public function show(Buku $buku)
    {
        $buku->load('kategori');
        return view('buku.show', compact('buku'));
    }

    public function edit(Buku $buku)
    {
        $kategoris = Kategori::all();
        return view('buku.edit', compact('buku', 'kategoris'));
    }

    public function update(Request $request, Buku $buku)
    {
        $validated = $request->validate([
            'judul'        => 'required|string|max:255',
            'kategori_id'  => 'required|exists:kategoris,id',
            'pengarang'    => 'required|string|max:255',
            'penerbit'     => 'required|string|max:255',
            'tahun_terbit' => 'required|digits:4|integer|min:1900|max:'.date('Y'),
            'isbn'         => 'nullable|string|max:20|unique:bukus,isbn,'.$buku->id,
            'stok'         => 'required|integer|min:0',
            'deskripsi'    => 'nullable|string',
            'rak'          => 'nullable|string|max:50',
            'cover'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('cover')) {
            if ($buku->cover) Storage::disk('public')->delete($buku->cover);
            $validated['cover'] = $request->file('cover')->store('covers', 'public');
        }

        $selisih = $validated['stok'] - $buku->stok;
        $validated['stok_tersedia'] = max(0, $buku->stok_tersedia + $selisih);

        $buku->update($validated);
        return redirect()->route('buku.index')->with('success', 'Buku berhasil diperbarui!');
    }

    public function destroy(Buku $buku)
    {
        if ($buku->peminjamans()->whereIn('status',['dipinjam','terlambat'])->exists()) {
            return back()->with('error', 'Tidak bisa hapus buku yang sedang dipinjam!');
        }
        if ($buku->cover) Storage::disk('public')->delete($buku->cover);
        $buku->delete();
        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus!');
    }
}