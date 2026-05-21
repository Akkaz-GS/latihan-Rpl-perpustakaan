 @extends('layouts.app')
@section('title','Dashboard Admin')
@section('page-title','Dashboard Admin')
@section('page-subtitle','Kelola sistem perpustakaan')

@section('content')
<div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
    @php $cards = [
        ['label'=>'Total Anggota',    'value'=>$data['total_anggota'],    'icon'=>'fa-users',      'color'=>'bg-blue-500'],
        ['label'=>'Pustakawan',       'value'=>$data['total_pustakawan'], 'icon'=>'fa-user-tie',   'color'=>'bg-violet-500'],
        ['label'=>'Belum Verifikasi', 'value'=>$data['belum_verifikasi'], 'icon'=>'fa-user-clock', 'color'=>'bg-amber-500'],
        ['label'=>'Total Buku',       'value'=>$data['total_buku'],       'icon'=>'fa-book',       'color'=>'bg-emerald-500'],
    ]; @endphp

    @foreach($cards as $card)
    <div class="card p-5 flex items-center gap-4">
        <div class="w-12 h-12 {{ $card['color'] }} rounded-xl flex items-center justify-center text-white flex-shrink-0">
            <i class="fas {{ $card['icon'] }}"></i>
        </div>
        <div>
            <p class="text-2xl font-display font-bold text-gray-800">{{ number_format($card['value']) }}</p>
            <p class="text-xs text-gray-500 mt-0.5">{{ $card['label'] }}</p>
        </div>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-3 gap-5">
    <div class="col-span-2 card p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-display font-semibold text-gray-800">User Terbaru</h3>
            <a href="{{ route('admin.users.index') }}" class="text-xs hover:underline" style="color:#c8873a">Kelola User →</a>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-xs text-gray-400 border-b">
                    <th class="text-left pb-2">Nama</th>
                    <th class="text-left pb-2">Role</th>
                    <th class="text-left pb-2">Status</th>
                    <th class="text-left pb-2">Tgl Daftar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($data['user_terbaru'] as $u)
                <tr class="hover:bg-gray-50">
                    <td class="py-2.5">
                        <p class="font-medium text-gray-800">{{ $u->name }}</p>
                        <p class="text-xs text-gray-400">{{ $u->email }}</p>
                    </td>
                    <td class="py-2.5">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium
                            {{ $u->role==='pustakawan' ? 'bg-violet-100 text-violet-700' : 'bg-blue-100 text-blue-700' }}">
                            {{ $u->role_label }}
                        </span>
                    </td>
                    <td class="py-2.5">
                        @if($u->isAnggota())
                        <span class="px-2 py-0.5 rounded-full text-xs
                            {{ $u->is_verified ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ $u->is_verified ? 'Terverifikasi' : 'Belum Verif' }}
                        </span>
                        @else
                        <span class="text-xs text-gray-400">—</span>
                        @endif
                    </td>
                    <td class="py-2.5 text-xs text-gray-500">
                        {{ $u->tanggal_daftar?->format('d M Y') ?? '-' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="card p-5">
        <h3 class="font-display font-semibold text-gray-800 mb-4">Menu Admin</h3>
        <div class="space-y-2">
            <a href="{{ route('admin.users.index') }}"
               class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-all group">
                <div class="w-9 h-9 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600">
                    <i class="fas fa-users text-sm"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700">Kelola User</p>
                    <p class="text-xs text-gray-400">Tambah, edit, hapus user</p>
                </div>
            </a>
            <a href="{{ route('admin.users.create') }}"
               class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-all group">
                <div class="w-9 h-9 bg-violet-100 rounded-lg flex items-center justify-center text-violet-600">
                    <i class="fas fa-user-plus text-sm"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700">Tambah User</p>
                    <p class="text-xs text-gray-400">Buat akun baru</p>
                </div>
            </a>
            <a href="{{ route('admin.laporan.index') }}"
               class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-all group">
                <div class="w-9 h-9 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600">
                    <i class="fas fa-chart-bar text-sm"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700">Laporan</p>
                    <p class="text-xs text-gray-400">Statistik & export PDF</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection