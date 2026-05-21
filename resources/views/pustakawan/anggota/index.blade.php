 @extends('layouts.app')
@section('title','Verifikasi Anggota')
@section('page-title','Verifikasi Anggota')
@section('page-subtitle','Setujui akun anggota yang baru mendaftar')

@section('content')
<div class="card p-4 mb-5">
    <form method="GET" class="flex gap-3">
        <div class="flex-1 relative">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama / no. anggota..."
                   class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none">
        </div>
        <select name="status" class="px-3 py-2.5 text-sm border border-gray-200 rounded-lg bg-white focus:outline-none">
            <option value="">Semua Status</option>
            <option value="pending"   {{ request('status')==='pending'   ? 'selected':'' }}>Belum Terverifikasi</option>
            <option value="verified"  {{ request('status')==='verified'  ? 'selected':'' }}>Terverifikasi</option>
        </select>
        <button type="submit" class="btn-primary text-sm">Filter</button>
    </form>
</div>

<div class="card overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wide">
                <th class="text-left px-5 py-3.5">Anggota</th>
                <th class="text-left px-5 py-3.5">No. Anggota</th>
                <th class="text-left px-5 py-3.5">Kontak</th>
                <th class="text-left px-5 py-3.5">Tgl Daftar</th>
                <th class="text-left px-5 py-3.5">Status</th>
                <th class="text-left px-5 py-3.5">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($anggota as $a)
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-3.5">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold"
                             style="background:#c8873a">
                            {{ strtoupper(substr($a->name,0,1)) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $a->name }}</p>
                            <p class="text-xs text-gray-400">{{ $a->email }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-5 py-3.5 font-mono text-xs text-gray-600">{{ $a->no_anggota }}</td>
                <td class="px-5 py-3.5 text-gray-600">{{ $a->no_telepon ?? '-' }}</td>
                <td class="px-5 py-3.5 text-xs text-gray-500">{{ $a->tanggal_daftar?->format('d M Y') ?? '-' }}</td>
                <td class="px-5 py-3.5">
                    @if($a->is_verified)
                    <span class="px-2.5 py-1 rounded-full text-xs bg-emerald-100 text-emerald-700 font-medium">✓ Terverifikasi</span>
                    @else
                    <span class="px-2.5 py-1 rounded-full text-xs bg-amber-100 text-amber-700 font-medium">⏳ Menunggu</span>
                    @endif
                </td>
                <td class="px-5 py-3.5">
                    <div class="flex items-center gap-2">
                        @if(!$a->is_verified)
                        <form action="{{ route('pustakawan.anggota.verifikasi', $a) }}" method="POST">
                            @csrf @method('PATCH')
                            <button class="text-xs px-3 py-1.5 text-white rounded-lg" style="background:#059669">
                                <i class="fas fa-check mr-1"></i> Verifikasi
                            </button>
                        </form>
                        @endif
                        <form action="{{ route('pustakawan.anggota.nonaktif', $a) }}" method="POST"
                              onsubmit="return confirm('Nonaktifkan anggota ini?')">
                            @csrf @method('PATCH')
                            <button class="text-xs px-3 py-1.5 border border-red-200 text-red-500 hover:bg-red-50 rounded-lg">
                                {{ $a->is_aktif ? 'Nonaktifkan' : 'Nonaktif' }}
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-12 text-gray-400">
                    <i class="fas fa-users text-3xl mb-2 opacity-30"></i>
                    <p>Tidak ada data anggota</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-5 py-4 border-t border-gray-100">{{ $anggota->links() }}</div>
</div>
@endsection