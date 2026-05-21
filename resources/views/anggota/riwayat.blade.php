 @extends('layouts.app')
@section('title','Riwayat Pinjaman')
@section('page-title','Riwayat Pinjaman')
@section('page-subtitle','Daftar buku yang pernah Anda pinjam')

@section('content')
<div class="card overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wide">
                <th class="text-left px-5 py-3.5">Buku</th>
                <th class="text-left px-5 py-3.5">Tgl Pinjam</th>
                <th class="text-left px-5 py-3.5">Jatuh Tempo</th>
                <th class="text-left px-5 py-3.5">Tgl Kembali</th>
                <th class="text-left px-5 py-3.5">Status</th>
                <th class="text-left px-5 py-3.5">Denda</th>
                <th class="text-left px-5 py-3.5">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($peminjamans as $p)
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-3.5">
                    <div class="flex items-center gap-3">
                        <img src="{{ $p->buku->cover_url }}"
                             class="w-8 h-11 object-cover rounded flex-shrink-0 bg-gray-100">
                        <div class="min-w-0">
                            <p class="font-medium text-gray-800 truncate max-w-[180px]">{{ $p->buku->judul }}</p>
                            <p class="text-xs text-gray-400">{{ $p->buku->pengarang }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-5 py-3.5 text-gray-600">{{ $p->tanggal_pinjam->format('d M Y') }}</td>
                <td class="px-5 py-3.5">
                    <span class="{{ $p->hari_terlambat > 0 && $p->status !== 'dikembalikan' ? 'text-red-500 font-medium' : 'text-gray-600' }}">
                        {{ $p->tanggal_jatuh_tempo->format('d M Y') }}
                    </span>
                </td>
                <td class="px-5 py-3.5 text-gray-600">
                    {{ $p->tanggal_kembali ? $p->tanggal_kembali->format('d M Y') : '-' }}
                </td>
                <td class="px-5 py-3.5">
                    <span class="badge-{{ $p->status }}">{{ ucfirst($p->status) }}</span>
                    @if($p->hari_terlambat > 0 && $p->status !== 'dikembalikan')
                    <p class="text-xs text-red-500 mt-0.5">{{ $p->hari_terlambat }} hari terlambat</p>
                    @endif
                </td>
                <td class="px-5 py-3.5">
                    @if($p->denda > 0)
                    <span class="text-red-500 font-medium">Rp {{ number_format($p->denda, 0, ',', '.') }}</span>
                    @else
                    <span class="text-gray-400">-</span>
                    @endif
                </td>
                <td class="px-5 py-3.5">
                    @if($p->status !== 'dikembalikan')
                    <form action="{{ route('anggota.kembalikan', $p) }}" method="POST"
                          onsubmit="return confirm('Konfirmasi pengembalian buku ini?')">
                        @csrf @method('PATCH')
                        <button class="text-xs px-3 py-1.5 text-white rounded-lg" style="background:#059669">
                            Kembalikan
                        </button>
                    </form>
                    @else
                    <span class="text-xs text-gray-400">Selesai</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center py-12 text-gray-400">
                    <i class="fas fa-history text-3xl mb-2 opacity-30"></i>
                    <p>Belum ada riwayat peminjaman</p>
                    <a href="{{ route('buku.index') }}" class="text-sm mt-2 inline-block hover:underline" style="color:#c8873a">
                        Cari buku sekarang →
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-5 py-4 border-t border-gray-100">{{ $peminjamans->links() }}</div>
</div>
@endsection