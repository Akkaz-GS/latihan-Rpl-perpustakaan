 @extends('layouts.app')
@section('title','Pinjam Buku')
@section('page-title','Pinjam Buku')

@section('content')
<div class="max-w-lg">
    <div class="card p-8">
        <div class="flex gap-4 mb-6 pb-6 border-b border-gray-100">
            <img src="{{ $buku->cover_url }}" class="w-16 h-22 object-cover rounded-lg flex-shrink-0 bg-gray-100">
            <div>
                <p class="text-xs font-medium mb-1" style="color:#c8873a">{{ $buku->kategori->nama }}</p>
                <h3 class="font-display font-bold text-gray-800">{{ $buku->judul }}</h3>
                <p class="text-sm text-gray-500">{{ $buku->pengarang }}</p>
                <p class="text-xs text-gray-400 mt-1">Stok tersedia: {{ $buku->stok_tersedia }}</p>
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 text-sm text-blue-700">
            <i class="fas fa-info-circle mr-1"></i>
            Durasi peminjaman <strong>14 hari</strong>. Denda <strong>Rp 1.000/hari</strong> jika terlambat.
            Jatuh tempo: <strong>{{ now()->addDays(14)->format('d M Y') }}</strong>
        </div>

        <form action="{{ route('anggota.pinjam.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="buku_id" value="{{ $buku->id }}">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Catatan (opsional)</label>
                <textarea name="catatan" rows="3" class="input-field resize-none"
                          placeholder="Catatan tambahan...">{{ old('catatan') }}</textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-accent"
                        onsubmit="return confirm('Konfirmasi peminjaman buku ini?')">
                    <i class="fas fa-hand-holding-heart mr-1"></i> Konfirmasi Pinjam
                </button>
                <a href="{{ route('buku.show', $buku) }}"
                   class="px-5 py-2 border border-gray-200 text-gray-600 rounded-lg text-sm hover:bg-gray-50">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection