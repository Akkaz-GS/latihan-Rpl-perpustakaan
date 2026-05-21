 @extends('layouts.app')
@section('title','Detail Peminjaman')
@section('page-title','Detail Peminjaman')

@section('content')
<div class="max-w-2xl">
    <div class="card p-8 space-y-5">
        <div class="flex items-center justify-between pb-5 border-b border-gray-100">
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide">Kode Peminjaman</p>
                <p class="font-mono font-bold text-lg text-gray-800">{{ $peminjaman->kode_peminjaman }}</p>
            </div>
            <span class="badge-{{ $peminjaman->status }} text-sm">{{ ucfirst($peminjaman->status) }}</span>
        </div>

        <div class="grid grid-cols-2 gap-5">
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Anggota</p>
                <p class="font-medium text-gray-800">{{ $peminjaman->user->name }}</p>
                <p class="text-sm text-gray-500">{{ $peminjaman->user->no_anggota }}</p>
                <p class="text-sm text-gray-500">{{ $peminjaman->user->email }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Buku</p>
                <p class="font-medium text-gray-800">{{ $peminjaman->buku->judul }}</p>
                <p class="text-sm text-gray-500">{{ $peminjaman->buku->pengarang }}</p>
                <p class="text-sm text-gray-500">{{ $peminjaman->buku->kategori->nama }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Tanggal Pinjam</p>
                <p class="font-medium text-gray-800">{{ $peminjaman->tanggal_pinjam->format('d M Y') }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Jatuh Tempo</p>
                <p class="font-medium {{ $peminjaman->hari_terlambat > 0 && $peminjaman->status !== 'dikembalikan' ? 'text-red-500' : 'text-gray-800' }}">
                    {{ $peminjaman->tanggal_jatuh_tempo->format('d M Y') }}
                </p>
            </div>
            @if($peminjaman->tanggal_kembali)
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Tanggal Kembali</p>
                <p class="font-medium text-gray-800">{{ $peminjaman->tanggal_kembali->format('d M Y') }}</p>
            </div>
            @endif
            @if($peminjaman->denda > 0)
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Denda</p>
                <p class="font-bold text-red-500">Rp {{ number_format($peminjaman->denda,0,',','.') }}</p>
            </div>
            @endif
            @if($peminjaman->catatan)
            <div class="col-span-2">
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Catatan</p>
                <p class="text-gray-600">{{ $peminjaman->catatan }}</p>
            </div>
            @endif
            @if($peminjaman->diprosesoleh)
            <div class="col-span-2">
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Diproses Oleh</p>
                <p class="text-gray-600">{{ $peminjaman->diprosesoleh->name }}</p>
            </div>
            @endif
        </div>

        <div class="flex gap-3 pt-5 border-t border-gray-100">
            @if($peminjaman->status !== 'dikembalikan')
            <form action="{{ route('pustakawan.peminjaman.kembalikan', $peminjaman) }}" method="POST"
                  onsubmit="return confirm('Konfirmasi pengembalian buku ini?')">
                @csrf @method('