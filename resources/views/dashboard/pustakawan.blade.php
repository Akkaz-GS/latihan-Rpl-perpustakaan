 @extends('layouts.app')
@section('title','Dashboard Pustakawan')
@section('page-title','Dashboard Pustakawan')
@section('page-subtitle','Kelola buku dan peminjaman')

@section('content')
<div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
    @php $cards = [
        ['label'=>'Total Buku',       'value'=>$data['total_buku'],       'icon'=>'fa-book',               'color'=>'bg-blue-500'],
        ['label'=>'Dipinjam Aktif',   'value'=>$data['dipinjam_aktif'],   'icon'=>'fa-hand-holding-heart', 'color'=>'bg-amber-500'],
        ['label'=>'Terlambat',        'value'=>$data['terlambat'],        'icon'=>'fa-clock',              'color'=>'bg-red-500'],
        ['label'=>'Belum Verifikasi', 'value'=>$data['belum_verifikasi'], 'icon'=>'fa-user-clock',         'color'=>'bg-violet-500'],
    ]; @endphp

    @foreach($cards as $c)
    <div class="card p-5 flex items-center gap-4">
        <div class="w-12 h-12 {{ $c['color'] }} rounded-xl flex items-center justify-center text-white flex-shrink-0">
            <i class="fas {{ $c['icon'] }}"></i>
        </div>
        <div>
            <p class="text-2xl font-display font-bold text-gray-800">{{ number_format($c['value']) }}</p>
            <p class="text-xs text-gray-500 mt-0.5">{{ $c['label'] }}</p>
        </div>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-3 gap-5">
    <div class="col-span-2 card p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-display font-semibold text-gray-800">Peminjaman Terbaru</h3>
            <a href="{{ route('pustakawan.peminjaman.index') }}" class="text-xs hover:underline" style="color:#c8873a">Lihat semua →</a>
        </div>
        <div class="space-y-3">
            @forelse($data['peminjaman_baru'] as $p)
            <div class="flex items-center gap-4 py-2 border-b border-gray-50 last:border-0">
                <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                     style="background:#1a3a4a">
                    {{ strtoupper(substr($p->user->name,0,1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ $p->buku->judul }}</p>
                    <p class="text-xs text-gray-400">{{ $p->user->name }} · {{ $p->tanggal_pinjam->format('d M Y') }}</p>
                </div>
                <span class="badge-{{ $p->status }}">{{ ucfirst($p->status) }}</span>
            </div>
            @empty
            <p class="text-center text-gray-400 text-sm py-8">Belum ada peminjaman</p>
            @endforelse
        </div>
    </div>

    <div class="card p-5">
        <h3 class="font-display font-semibold text-gray-800 mb-4">Menu Cepat</h3>
        <div class="space-y-2">
            <a href="{{ route('pustakawan.peminjaman.create') }}"
               class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-all">
                <div class="w-9 h-9 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600">
                    <i class="fas fa-plus-circle text-sm"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700">Catat Peminjaman</p>
                    <p class="text-xs text-gray-400">Tambah data baru</p>
                </div>
            </a>
            <a href="{{ route('pustakawan.peminjaman.index') }}"
               class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-all">
                <div class="w-9 h-9 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600">
                    <i class="fas fa-list text-sm"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700">Semua Peminjaman</p>
                    <p class="text-xs text-gray-400">Kelola & proses kembali</p>
                </div>
            </a>
            <a href="{{ route('pustakawan.anggota.index') }}"
               class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-all">
                <div class="w-9 h-9 bg-violet-100 rounded-lg flex items-center justify-center text-violet-600">
                    <i class="fas fa-user-check text-sm"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700">Verifikasi Anggota</p>
                    <p class="text-xs text-gray-400">Setujui akun baru</p>
                </div>
            </a>
            <a href="{{ route('pustakawan.buku.create') }}"
               class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-all">
                <div class="w-9 h-9 bg-amber-100 rounded-lg flex items-center justify-center text-amber-600">
                    <i class="fas fa-book-medical text-sm"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700">Tambah Buku</p>
                    <p class="text-xs text-gray-400">Koleksi baru</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection