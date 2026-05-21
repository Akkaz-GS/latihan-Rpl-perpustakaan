 @extends('layouts.app')
@section('title','Catat Peminjaman')
@section('page-title','Catat Peminjaman Baru')

@section('content')
<div class="max-w-2xl">
    <div class="card p-8">
        <form action="{{ route('pustakawan.peminjaman.store') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Anggota <span class="text-red-500">*</span></label>
                <select name="user_id" required class="input-field bg-white">
                    <option value="">-- Pilih Anggota --</option>
                    @foreach($anggota as $a)
                    <option value="{{ $a->id }}" {{ old('user_id')==$a->id ? 'selected':'' }}>
                        {{ $a->name }} ({{ $a->no_anggota }})
                    </option>
                    @endforeach
                </select>
                @error('user_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Buku <span class="text-red-500">*</span></label>
                <select name="buku_id" required class="input-field bg-white">
                    <option value="">-- Pilih Buku --</option>
                    @foreach($bukus as $b)
                    <option value="{{ $b->id }}" {{ old('buku_id')==$b->id ? 'selected':'' }}>
                        {{ $b->judul }} (Stok: {{ $b->stok_tersedia }})
                    </option>
                    @endforeach
                </select>
                @error('buku_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Pinjam <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_pinjam"
                           value="{{ old('tanggal_pinjam', now()->format('Y-m-d')) }}"
                           required class="input-field">
                    @error('tanggal_pinjam')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Jatuh Tempo <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_jatuh_tempo"
                           value="{{ old('tanggal_jatuh_tempo', now()->addDays(14)->format('Y-m-d')) }}"
                           required class="input-field">
                    @error('tanggal_jatuh_tempo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Catatan</label>
                <textarea name="catatan" rows="3" class="input-field resize-none"
                          placeholder="Catatan tambahan...">{{ old('catatan') }}</textarea>