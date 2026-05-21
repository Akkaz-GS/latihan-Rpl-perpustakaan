 @extends('layouts.app')
@section('title','Edit Buku')
@section('page-title','Edit Buku')

@section('content')
<div class="max-w-3xl">
    <div class="card p-8">
        <form action="{{ route('pustakawan.buku.update', $buku) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-2 gap-5">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Judul Buku <span class="text-red-500">*</span></label>
                    <input type="text" name="judul" value="{{ old('judul', $buku->judul) }}" required class="input-field">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Kategori <span class="text-red-500">*</span></label>
                    <select name="kategori_id" required class="input-field bg-white">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoris as $kat)
                        <option value="{{ $kat->id }}" {{ old('kategori_id', $buku->kategori_id)==$kat->id ? 'selected' : '' }}>{{ $kat->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">ISBN</label>
                    <input type="text" name="isbn" value="{{ old('isbn', $buku->isbn) }}" class="input-field">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Pengarang <span class="text-red-500">*</span></label>
                    <input type="text" name="pengarang" value="{{ old('pengarang', $buku->pengarang) }}" required class="input-field">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Penerbit <span class="text-red-500">*</span></label>
                    <input type="text" name="penerbit" value="{{ old('penerbit', $buku->penerbit) }}" required class="input-field">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tahun Terbit <span class="text-red-500">*</span></label>
                    <input type="number" name="tahun_terbit" value="{{ old('tahun_terbit', $buku->tahun_terbit) }}"
                           required min="1900" max="{{ date('Y') }}" class="input-field">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Stok <span class="text-red-500">*</span></label>
                    <input type="number" name="stok" value="{{ old('stok', $buku->stok) }}" required min="0" class="input-field">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Lokasi Rak</label>
                    <input type="text" name="rak" value="{{ old('rak', $buku->rak) }}" class="input-field">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi</label>
                    <textarea name="deskripsi" rows="4" class="input-field resize-none">{{ old('deskripsi', $buku->deskripsi) }}</textarea>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Cover Buku</label>
                    @if($buku->cover)
                    <img src="{{ $buku->cover_url }}" class="w-20 h-28 object-cover rounded-lg mb-2">
                    @endif
                    <input type="file" name="cover" accept="image/*"
                           class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-gray-100 file:text-gray-700">
                    <p class="text-xs text-gray-400 mt-1">Kosongkan jika tidak ingin mengubah cover.</p>
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-1"></i> Update Buku
                </button>
                <a href="{{ route('buku.index') }}" class="px-5 py-2 border border-gray-200 text-gray-600 rounded-lg text-sm hover:bg-gray-50">Batal</a>
                <form action="{{ route('pustakawan.buku.destroy', $buku) }}" method="POST" class="ml-auto"
                      onsubmit="return confirm('Hapus buku ini?')">
                    @csrf @method('DELETE')
                    <button class="px-5 py-2 bg-red-50 text-red-600 border border-red-200 rounded-lg text-sm hover:bg-red-100">
                        <i class="fas fa-trash mr-1"></i> Hapus
                    </button>
                </form>
            </div>
        </form>
    </div>
</div>
@endsection