 @extends('layouts.app')
@section('title','Tambah Buku')
@section('page-title','Tambah Buku Baru')

@section('content')
<div class="max-w-3xl">
    <div class="card p-8">
        <form action="{{ route('pustakawan.buku.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div class="grid grid-cols-2 gap-5">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Judul Buku <span class="text-red-500">*</span></label>
                    <input type="text" name="judul" value="{{ old('judul') }}" required class="input-field">
                    @error('judul')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Kategori <span class="text-red-500">*</span></label>
                    <select name="kategori_id" required class="input-field bg-white">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoris as $kat)
                        <option value="{{ $kat->id }}" {{ old('kategori_id')==$kat->id ? 'selected' : '' }}>{{ $kat->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">ISBN</label>
                    <input type="text" name="isbn" value="{{ old('isbn') }}" class="input-field" placeholder="978-xxx-xxx">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Pengarang <span class="text-red-500">*</span></label>
                    <input type="text" name="pengarang" value="{{ old('pengarang') }}" required class="input-field">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Penerbit <span class="text-red-500">*</span></label>
                    <input type="text" name="penerbit" value="{{ old('penerbit') }}" required class="input-field">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tahun Terbit <span class="text-red-500">*</span></label>
                    <input type="number" name="tahun_terbit" value="{{ old('tahun_terbit') }}" required
                           min="1900" max="{{ date('Y') }}" class="input-field">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Stok <span class="text-red-500">*</span></label>
                    <input type="number" name="stok" value="{{ old('stok', 0) }}" required min="0" class="input-field">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Lokasi Rak</label>
                    <input type="text" name="rak" value="{{ old('rak') }}" class="input-field" placeholder="A1, B3, dst.">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi</label>
                    <textarea name="deskripsi" rows="4" class="input-field resize-none">{{ old('deskripsi') }}</textarea>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Cover Buku</label>
                    <input type="file" name="cover" accept="image/*"
                           class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-gray-100 file:text-gray-700">
                    <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG. Maks 2MB.</p>
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-1"></i> Simpan Buku
                </button>
                <a href="{{ route('buku.index') }}" class="px-5 py-2 border border-gray-200 text-gray-600 rounded-lg text-sm hover:bg-gray-50">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection