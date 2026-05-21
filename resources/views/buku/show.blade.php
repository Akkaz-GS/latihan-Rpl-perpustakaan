 @extends('layouts.app')
@section('title', $buku->judul)
@section('page-title', 'Detail Buku')

@section('header-actions')
    @if(auth()->user()->isPustakawan())
    <a href="{{ route('pustakawan.buku.edit', $buku) }}" class="btn-primary text-sm">
        <i class="fas fa-edit mr-1"></i> Edit Buku
    </a>
    @endif
    @if(auth()->user()->isAnggota() && $buku->stok_tersedia > 0)
    <a href="{{ route('anggota.pinjam.form', $buku) }}" class="btn-accent text-sm">
        <i class="fas fa-hand-holding-heart mr-1"></i> Pinjam Buku
    </a>
    @endif
@endsection

@section('content')
<div class="grid grid-cols-3 gap-6">
    <div class="col-span-1">
        <div class="card p-4">
            <img src="{{ $buku->cover_url }}" alt="{{ $buku->judul }}"
                 class="w-full rounded-lg object-cover" style="max-height:320px">
            <div class="mt-4 text-center">
                <span class="badge-{{ strtolower($buku->status_stok) === 'tersedia' ? 'tersedia' : (strtolower($buku->status_stok) === 'terbatas' ? 'terbatas' : 'habis') }} text-sm">
                    {{ $buku->status_stok }}
                </span>
                <p class="text-sm text-gray-500 mt-2">{{ $buku->stok_tersedia }} dari {{ $buku->stok }} tersedia</p>
            </div>
        </div>
    </div>

    <div class="col-span-2 space-y-5">
        <div class="card p-6">
            <p class="text-sm font-medium mb-1" style="color:#c8873a">{{ $buku->kategori->nama }}</p>
            <h1 class="font-display text-2xl font-bold text-gray-800 mb-1">{{ $buku->judul }}</h1>
            <p class="text-gray-500">{{ $buku->pengarang }}</p>

            <div class="grid grid-cols-2 gap-4 mt-5 pt-5 border-t border-gray-100">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Penerbit</p>
                    <p class="text-sm font-medium text-gray-700 mt-0.5">{{ $buku->penerbit }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Tahun Terbit</p>
                    <p class="text-sm font-medium text-gray-700 mt-0.5">{{ $buku->tahun_terbit }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide">ISBN</p>
                    <p class="text-sm font-medium text-gray-700 mt-0.5">{{ $buku->isbn ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Lokasi Rak</p>
                    <p class="text-sm font-medium text-gray-700 mt-0.5">{{ $buku->rak ?? '-' }}</p>
                </div>
            </div>

            @if($buku->deskripsi)
            <div class="mt-5 pt-5 border-t border-gray-100">
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">Deskripsi</p>
                <p class="text-sm text-gray-600 leading-relaxed">{{ $buku->deskripsi }}</p>
            </div>
            @endif
        </div>

        @if(auth()->user()->isAnggota() && $buku->stok_tersedia > 0)
        <div class="card p-5 border-2" style="border-color:#c8873a">
            <p class="font-medium text-gray-800 mb-2">Ingin meminjam buku ini?</p>
            <p class="text-sm text-gray-500 mb-3">Durasi peminjaman 14 hari. Denda Rp 1.000/hari jika terlambat.</p>
            <a href="{{ route('anggota.pinjam.form', $buku) }}" class="btn-accent text-sm">
                <i class="fas fa-hand-holding-heart mr-1"></i> Pinjam Sekarang
            </a>
        </div>
        @endif
    </div>
</div>
@endsection