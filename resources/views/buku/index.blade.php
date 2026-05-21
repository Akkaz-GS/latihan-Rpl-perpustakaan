 @extends('layouts.app')
@section('title','Katalog Buku')
@section('page-title','Katalog Buku')
@section('page-subtitle','Jelajahi koleksi buku perpustakaan')

@section('header-actions')
    @if(auth()->user()->isPustakawan())
    <a href="{{ route('pustakawan.buku.create') }}" class="btn-accent text-sm flex items-center gap-2">
        <i class="fas fa-plus"></i> Tambah Buku
    </a>
    @endif
@endsection

@section('content')
<div class="card p-5 mb-6">
    <form method="GET" class="flex flex-wrap gap-3">
        <div class="flex-1 min-w-48 relative">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari judul, pengarang, ISBN..."
                   class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none" style="focus:border-color:#1a3a4a">
        </div>
        <select name="kategori" class="px-3 py-2.5 text-sm border border-gray-200 rounded-lg bg-white focus:outline-none">
            <option value="">Semua Kategori</option>
            @foreach($kategoris as $kat)
            <option value="{{ $kat->id }}" {{ request('kategori')==$kat->id ? 'selected' : '' }}>{{ $kat->nama }}</option>
            @endforeach
        </select>
        <select name="status" class="px-3 py-2.5 text-sm border border-gray-200 rounded-lg bg-white focus:outline-none">
            <option value="">Semua Status</option>
            <option value="tersedia" {{ request('status')==='tersedia' ? 'selected' : '' }}>Tersedia</option>
            <option value="habis"    {{ request('status')==='habis'    ? 'selected' : '' }}>Habis</option>
        </select>
        <button type="submit" class="btn-primary text-sm">Filter</button>
        @if(request()->anyFilled(['search','kategori','status']))
        <a href="{{ route('buku.index') }}" class="px-4 py-2.5 text-sm text-gray-500 border border-gray-200 rounded-lg hover:bg-gray-50">Reset</a>
        @endif
    </form>
</div>

<p class="text-sm text-gray-500 mb-4">Menampilkan {{ $bukus->total() }} buku</p>

<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5">
    @forelse($bukus as $buku)
    <div class="card overflow-hidden group hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
        <div class="relative h-44 bg-gray-100 overflow-hidden">
            <img src="{{ $buku->cover_url }}" alt="{{ $buku->judul }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            <span class="absolute top-2 right-2 text-xs font-medium badge-{{ strtolower($buku->status_stok) === 'tersedia' ? 'tersedia' : (strtolower($buku->status_stok) === 'terbatas' ? 'terbatas' : 'habis') }}">
                {{ $buku->status_stok }}
            </span>
        </div>
        <div class="p-3">
            <p class="text-xs font-medium mb-1" style="color:#c8873a">{{ $buku->kategori->nama }}</p>
            <h3 class="font-display font-semibold text-sm text-gray-800 leading-snug line-clamp-2 mb-1">{{ $buku->judul }}</h3>
            <p class="text-xs text-gray-500">{{ $buku->pengarang }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $buku->stok_tersedia }}/{{ $buku->stok }} tersedia</p>
            <div class="mt-3 flex gap-2">
                <a href="{{ route('buku.show', $buku) }}"
                   class="flex-1 text-center py-1.5 text-xs border rounded-lg transition-all hover:text-white"
                   style="border-color:#1a3a4a; color:#1a3a4a;"
                   onmouseover="this.style.background='#1a3a4a';this.style.color='white'"
                   onmouseout="this.style.background='white';this.style.color='#1a3a4a'">
                    Detail
                </a>
                @if(auth()->user()->isPustakawan())
                <a href="{{ route('pustakawan.buku.edit', $buku) }}"
                   class="px-2.5 py-1.5 text-xs border border-gray-200 text-gray-500 rounded-lg hover:bg-gray-50">
                    <i class="fas fa-edit"></i>
                </a>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full text-center py-16 text-gray-400">
        <i class="fas fa-book-open text-4xl mb-3 opacity-30"></i>
        <p class="font-medium">Tidak ada buku ditemukan</p>
        <p class="text-sm mt-1">Coba ubah filter pencarian</p>
    </div>
    @endforelse
</div>

<div class="mt-6">{{ $bukus->links() }}</div>
@endsection