 @extends('layouts.app')
@section('title','Tambah User')
@section('page-title','Tambah User Baru')

@section('content')
<div class="max-w-lg">
    <div class="card p-8">
        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required class="input-field">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" required class="input-field">
                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Role <span class="text-red-500">*</span></label>
                <select name="role" required class="input-field bg-white">
                    <option value="">-- Pilih Role --</option>
                    <option value="anggota"    {{ old('role')==='anggota'    ? 'selected':'' }}>Anggota</option>
                    <option value="pustakawan" {{ old('role')==='pustakawan' ? 'selected':'' }}>Pustakawan</option>
                    <option value="admin"      {{ old('role')==='admin'      ? 'selected':'' }}>Admin</option>
                </select>
                @error('role')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">No. Telepon</label>
                <input type="text" name="no_telepon" value="{{ old('no_telepon') }}"
                       class="input-field" placeholder="08xxxxxxxxxx">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Alamat</label>
                <textarea name="alamat" rows="2" class="input-field resize-none"
                          placeholder="Alamat lengkap">{{ old('alamat') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Password <span class="text-red-500">*</span></label>
                <input type="password" name="password" required class="input-field" placeholder="Minimal 8 karakter">
                @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-1"></i> Simpan User
                </button>
                <a href="{{ route('admin.users.index') }}"
                   class="px-5 py-2 border border-gray-200 text-gray-600 rounded-lg text-sm hover:bg-gray-50">
                   Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection