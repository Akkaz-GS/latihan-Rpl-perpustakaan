@extends('layouts.app')
@section('title','Dashboard')
@section('page-title','Halo, ' . auth()->user()->name . '!')
@section('page-subtitle','Selamat datang di Pustaka Digital')

@section('content')

@if(!$data['is_verified'])
<div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-xl flex items-start gap-3">
    <i class="fas fa-clock text-amber-500 mt-0.5"></i>
    <div>
        <p class="font-medium text-amber-800 text-sm">Akun belum diverifikasi</p>
        <p class="text-xs text-amber-600 mt-0.5">Akun Anda sedang menunggu verifikasi dari pustakawan. Setelah diverifikasi, Anda bisa meminjam buku.</p>
    </div>
</div>
@endif

@if($data['dipinjam']->count())
<div class="card p-5 mb-5">
    <h3 class="font-display font-semibold text-gray-800 mb-4">📚 Sedang Dipinjam</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        @foreach($data['dipinjam'] as $p)
        <div class="flex gap-3 p-3 border border-gray-100 rounded-xl">
            <img src="{{ $p->buku->cover_url }}" class="w-12 h-16 object-cover rounded-lg flex-shrink-0 bg-gray-100">
            <div class="flex-1 min-w-0">
                <p class="font-medium text-sm text-gray-800 truncate">{{ $p->buku->judul }}</p>
                <p class="text-xs text-gray-400">{{ $p->buku->pengarang }}</p>
                <div class="mt-2 flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500">Jatuh tempo:</p>
                        <p class="text-xs font-semibold {{ $p->hari_terlambat > 0 ? 'text-red-500' : 'text-gray-700' }}">
                            {{ $p->tanggal_jatuh_tempo->format('d M Y') }}
                            @if($p->hari_terlambat > 0)
                            <span>({{ $p->hari_terlambat }}h terlambat)</span>
                            @endif
                        </p>
                    </div>
                    <form action="{{ route('anggota.kembalikan', $p) }}" method="POST"
                          onsubmit="return confirm('Konfirmasi pengembalian?')">
                        @csrf @method('PATCH')
                        <button class="text-xs px-3 py-1.5 text-white rounded-lg" style="background:#059669">
                            Kembalikan
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<div class="card p-5">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-display font-semibold text-gray-800">🔍 Buku Tersedia</h3>
        <a href="{{ route('buku.index') }}" class="text-xs hover:underline" style="color:#c8873a">Lihat semua →</a>
    </div>
    <div class="grid grid-cols-3 md:grid-cols-6 gap-3">
        @foreach($data['buku_tersedia'] as $buku)
        <a href="{{ route('buku.show', $buku) }}" class="group">
            <div class="h-32 bg-gray-100 rounded-lg overflow-hidden mb-1.5">
                <img src="{{ $buku->cover_url }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            </div>
            <p class="text-xs font-medium text-gray-700 truncate">{{ $buku->judul }}</p>
            <p class="text-xs text-gray-400 truncate">{{ $buku->pengarang }}</p>
        </a>
        @endforeach
    </div>
</div>
@endsection