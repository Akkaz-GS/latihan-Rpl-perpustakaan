<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi — Pustaka Digital</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family:'DM Sans',sans-serif; }
        .font-display { font-family:'Playfair Display',serif; }
        .input-field { width:100%; padding:.625rem .875rem; border:1.5px solid #e5e7eb; border-radius:8px; font-size:.875rem; transition:all .2s; outline:none; }
        .input-field:focus { border-color:#1a3a4a; box-shadow:0 0 0 3px rgba(26,58,74,.1); }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-8" style="background:#f5f0e8;">

    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="font-display text-3xl font-bold" style="color:#1a3a4a">📚 Pustaka Digital</h1>
            <p class="text-gray-500 mt-1">Daftar akun anggota baru</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-8">
            @if($errors->any())
            <div class="bg-red-50 text-red-700 border border-red-200 rounded-lg px-4 py-3 mb-4">
                <ul class="list-disc list-inside space-y-1 text-sm">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="input-field" placeholder="Budi Santoso">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="input-field" placeholder="nama@email.com">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">No. Telepon</label>
                    <input type="text" name="no_telepon" value="{{ old('no_telepon') }}"
                           class="input-field" placeholder="08xxxxxxxxxx">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Alamat</label>
                    <textarea name="alamat" rows="2" class="input-field"
                              placeholder="Jl. Contoh No. 1">{{ old('alamat') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required
                           class="input-field" placeholder="Minimal 8 karakter">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password_confirmation" required
                           class="input-field" placeholder="Ulangi password">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3 text-white font-medium rounded-lg transition-all text-sm"
                            style="background:#1a3a4a;" onmouseover="this.style.background='#2d5a70'"
                            onmouseout="this.style.background='#1a3a4a'">
                        Daftar Sekarang <i class="fas fa-arrow-right ml-1"></i>
                    </button>
                </div>
            </form>

            <p class="text-center text-sm text-gray-500 mt-5">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-medium hover:underline" style="color:#c8873a">
                    Masuk di sini
                </a>
            </p>
        </div>

        <p class="text-center text-xs text-gray-400 mt-4">
            ⚠️ Akun baru perlu diverifikasi pustakawan sebelum bisa meminjam buku
        </p>
    </div>

</body>
</html>
