 @extends('layouts.app')
@section('title','Laporan & Statistik')
@section('page-title','Laporan & Statistik')
@section('page-subtitle','Ringkasan data perpustakaan')

@section('content')

{{-- Summary Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-3 gap-5 mb-6">
    <div class="card p-5">
        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Total Koleksi</p>
        <p class="text-3xl font-display font-bold" style="color:#1a3a4a">{{ number_format($stats['total_buku']) }}</p>
        <p class="text-xs text-gray-500 mt-1">buku dalam perpustakaan</p>
    </div>
    <div class="card p-5">
        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Total Anggota</p>
        <p class="text-3xl font-display font-bold" style="color:#1a3a4a">{{ number_format($stats['total_anggota']) }}</p>
        <p class="text-xs text-gray-500 mt-1">anggota terdaftar</p>
    </div>
    <div class="card p-5">
        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Dipinjam Aktif</p>
        <p class="text-3xl font-display font-bold text-amber-500">{{ number_format($stats['dipinjam_aktif']) }}</p>
        <p class="text-xs text-gray-500 mt-1">sedang dipinjam</p>
    </div>
    <div class="card p-5">
        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Terlambat</p>
        <p class="text-3xl font-display font-bold text-red-500">{{ number_format($stats['terlambat']) }}</p>
        <p class="text-xs text-gray-500 mt-1">melewati jatuh tempo</p>
    </div>
    <div class="card p-5">
        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Dikembalikan Bulan Ini</p>
        <p class="text-3xl font-display font-bold text-emerald-600">{{ number_format($stats['dikembalikan_bulan']) }}</p>
        <p class="text-xs text-gray-500 mt-1">{{ now()->isoFormat('MMMM Y') }}</p>
    </div>
    <div class="card p-5">
        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Denda Bulan Ini</p>
        <p class="text-3xl font-display font-bold" style="color:#1a3a4a">
            Rp {{ number_format($stats['denda_bulan'], 0, ',', '.') }}
        </p>
        <p class="text-xs text-gray-500 mt-1">terkumpul bulan ini</p>
    </div>
</div>

<div class="grid grid-cols-2 gap-5">

    {{-- Grafik Peminjaman Per Bulan --}}
    <div class="card p-5">
        <h3 class="font-display font-semibold text-gray-800 mb-4">Peminjaman per Bulan ({{ now()->year }})</h3>
        @php
            $bulanNames = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
            $maxVal = max(1, $stats['peminjaman_per_bulan']->max('total') ?? 1);
        @endphp
        <div class="flex items-end gap-1.5 h-40">
            @foreach($bulanNames as $i => $bln)
            @php
                $row = $stats['peminjaman_per_bulan']->firstWhere('bulan', $i + 1);
                $val = $row?->total ?? 0;
                $height = ($val / $maxVal) * 100;
            @endphp
            <div class="flex-1 flex flex-col items-center gap-1">
                <span class="text-xs text-gray-600">{{ $val ?: '' }}</span>
                <div class="w-full rounded-t-sm" style="height:{{ $height }}%; background:#1a3a4a; min-height:3px"></div>
                <span class="text-xs text-gray-400">{{ $bln }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Buku Terpopuler --}}
    <div class="card p-5">
        <h3 class="font-display font-semibold text-gray-800 mb-4">Buku Paling Sering Dipinjam</h3>
        <div class="space-y-3">
            @foreach($stats['buku_populer'] as $i => $buku)
            <div class="flex items-center gap-3">
                <span class="w-6 h-6 rounded-full text-xs font-bold flex items-center justify-center flex-shrink-0 text-white"
                      style="{{ $i===0 ? 'background:#f59e0b' : ($i===1 ? 'background:#9ca3af' : 'background:#c8873a') }}">
                    {{ $i + 1 }}
                </span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ $buku->judul }}</p>
                    <div class="flex items-center gap-2 mt-0.5">
                        <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full" style="width:{{ ($buku->peminjamans_count / ($stats['buku_populer']->max('peminjamans_count') ?: 1)) * 100 }}%; background:#c8873a"></div>
                        </div>
                        <span class="text-xs text-gray-400 flex-shrink-0">{{ $buku->peminjamans_count }}x</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Anggota Paling Aktif --}}
    <div class="card p-5 col-span-2">
        <h3 class="font-display font-semibold text-gray-800 mb-4">Anggota Paling Aktif</h3>
        @if($stats['anggota_aktif']->count())
        <div class="grid grid-cols-5 gap-4">
            @foreach($stats['anggota_aktif'] as $anggota)
            <div class="text-center">
                <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-bold text-lg mx-auto mb-2"
                     style="background:#1a3a4a">
                    {{ strtoupper(substr($anggota->name,0,1)) }}
                </div>
                <p class="text-sm font-medium text-gray-800 truncate">{{ $anggota->name }}</p>
                <p class="text-xs text-gray-400">{{ $anggota->peminjamans_count }} buku</p>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-center text-gray-400 text-sm py-4">Belum ada data</p>
        @endif
    </div>
</div>
@endsection