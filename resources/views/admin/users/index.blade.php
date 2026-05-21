 @extends('layouts.app')
@section('title','Kelola User')
@section('page-title','Kelola User')
@section('page-subtitle','Manajemen anggota dan pustakawan')

@section('header-actions')
<a href="{{ route('admin.users.create') }}" class="btn-accent text-sm flex items-center gap-2">
    <i class="fas fa-user-plus"></i> Tambah User
</a>
@endsection

@section('content')
<div class="card p-4 mb-5">
    <form method="GET" class="flex gap-3">
        <div class="flex-1 relative">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama, email, no. anggota..."
                   class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none">
        </div>
        <select name="role" class="px-3 py-2.5 text-sm border border-gray-200 rounded-lg bg-white focus:outline-none">
            <option value="">Semua Role</option>
            <option value="anggota"    {{ request('role')==='anggota'    ? 'selected':'' }}>Anggota</option>
            <option value="pustakawan" {{ request('role')==='pustakawan' ? 'selected':'' }}>Pustakawan</option>
            <option value="admin"      {{ request('role')==='admin'      ? 'selected':'' }}>Admin</option>
        </select>
        <button type="submit" class="btn-primary text-sm">Filter</button>
        @if(request()->anyFilled(['search','role']))
        <a href="{{ route('admin.users.index') }}"
           class="px-4 py-2.5 text-sm text-gray-500 border border-gray-200 rounded-lg hover:bg-gray-50">Reset</a>
        @endif
    </form>
</div>

<div class="card overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wide">
                <th class="text-left px-5 py-3.5">User</th>
                <th class="text-left px-5 py-3.5">No. ID</th>
                <th class="text-left px-5 py-3.5">Role</th>
                <th class="text-left px-5 py-3.5">Status</th>
                <th class="text-left px-5 py-3.5">Tgl Daftar</th>
                <th class="text-left px-5 py-3.5">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($users as $user)
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-3.5">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold"
                             style="{{ $user->isAdmin() ? 'background:#7c3aed' : ($user->isPustakawan() ? 'background:#0284c7' : 'background:#c8873a') }}">
                            {{ strtoupper(substr($user->name,0,1)) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $user->email }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-5 py-3.5 font-mono text-xs text-gray-600">{{ $user->no_anggota ?? '-' }}</td>
                <td class="px-5 py-3.5">
                    <span class="px-2.5 py-1 rounded-full text-xs font-medium
                        {{ $user->isAdmin() ? 'bg-violet-100 text-violet-700' : ($user->isPustakawan() ? 'bg-sky-100 text-sky-700' : 'bg-amber-100 text-amber-700') }}">
                        {{ $user->role_label }}
                    </span>
                </td>
                <td class="px-5 py-3.5">
                    <span class="px-2 py-0.5 rounded-full text-xs {{ $user->is_aktif ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-600' }}">
                        {{ $user->is_aktif ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td class="px-5 py-3.5 text-xs text-gray-500">{{ $user->tanggal_daftar?->format('d M Y') ?? '-' }}</td>
                <td class="px-5 py-3.5">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.users.edit', $user) }}"
                           class="text-xs px-3 py-1.5 border border-gray-200 rounded-lg hover:bg-gray-50 text-gray-600">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.users.toggle', $user) }}" method="POST">
                            @csrf @method('PATCH')
                            <button class="text-xs px-3 py-1.5 border rounded-lg transition-colors
                                {{ $user->is_aktif ? 'border-amber-200 text-amber-600 hover:bg-amber-50' : 'border-emerald-200 text-emerald-600 hover:bg-emerald-50' }}">
                                {{ $user->is_aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>
                        @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                              onsubmit="return confirm('Hapus user {{ $user->name }}?')">
                            @csrf @method('DELETE')
                            <button class="text-xs px-3 py-1.5 border border-red-200 text-red-500 hover:bg-red-50 rounded-lg">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-12 text-gray-400">
                    <i class="fas fa-users text-3xl mb-2 opacity-30"></i>
                    <p>Tidak ada user ditemukan</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-5 py-4 border-t border-gray-100">{{ $users->links() }}</div>
</div>
@endsection