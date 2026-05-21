 @extends('layouts.app')
@section('title','Kelola Peminjaman')
@section('page-title','Kelola Peminjaman')
@section('page-subtitle','Manajemen peminjaman dan pengembalian buku')

@section('header-actions')
<a href="{{ route('pustakawan.peminjaman.create') }}" class="btn-accent text-sm flex items-center gap-2">
    <i class="fas fa-plus"></i> Catat Peminjaman
</a>
@endsection

@section('content')
<div class="card p-4 mb-5">
    <form method="GET" class="flex flex-wrap gap-3">
        <div class="flex-1 min-w-48 relative">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama / judul / kode..."
                   class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none">
        </div>
        <select name="status" class="px-3 py-2.5 text-sm border border-gray-200 rounded-lg bg-white focus:outline-none">
            <option value="">Semua Status</option>
            <option value="dipinjam"     {{ request('status')==='dipinjam'     ? 'selected':'' }}>Dipinjam</option>
            <option value="dikembalikan" {{ request('status')==='dikembalikan' ? 'selected':'' }}>Dikembalikan</option>
            <option value="terlambat"    {{ request('status')==='terlambat'    ? 'selected':'' }}>Terlambat</option>
        </select>
        <input type="date" name="tanggal" value="{{ request('tanggal') }}"
               class="px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none">
        <button type="submit" class="btn-primary text-sm">Filter</button>
        @if(request()->anyFilled(['search','status','tanggal']))
        <a href="{{ route('pustakawan.peminjaman.index') }}"
           class="px-4 py-2.5 text-sm text-gray-500 border border-gray-200 rounded-lg hover:bg-gray-50">Reset</a>
        @endif
    </form>
</div>

<div class="card overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wide">
                <th class="text-left px-5 py-3.5">Kode</th>
                <th class="text-left px-5 py-3.5">Anggota</th>
                <th class="text-left px-5 py-3.5">Buku</th>
                <th class="text-left px-5 py-3.5">Tgl Pinjam</th>
                <th class="text-left px-5 py-3.5">Jatuh Tempo</th>
                <th class="text-left px-5 py-3.5">Status</th>
                <th class="text-left px-5 py-3.5">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($peminjamans as $p)
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-3.5 font-mono text-xs text-gray-500">{{ $p->kode_peminjaman }}</td>
                <td class="px-5 py-3.5">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold"
                             style="background:#1a3a4a">
                            {{ strtoupper(substr($p->user->name,0,1)) }}