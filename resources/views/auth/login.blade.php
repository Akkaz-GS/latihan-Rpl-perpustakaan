 <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Pustaka Digital</title>
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
<body class="min-h-screen flex" style="background:#f5f0e8;">

    {{-- Kiri --}}
    <div class="hidden lg:flex flex-col justify-between w-1/2 p-12 text-white" style="background:#0f2530;">
        <div>
            <h1 class="font-display text-4xl font-bold" style="color:#c8873a">📚 Pustaka Digital</h1>
            <p class="text-white/60 mt-2 text-sm">Sistem Perpustakaan Modern</p>
        </div>
        <div>
            <blockquote class="font-display text-3xl font-medium leading-relaxed text-white/90 mb-8">
                "Buku adalah jendela dunia. Perpustakaan adalah pintunya."
            </blockquote>
            <div class="flex items-center gap-6 text-white/50 text-sm">
                <div class="text-center">
                    <div class="text-2xl font-bold font-display" style="color:#c8873a">1.200+</div>
                    <div>Koleksi Buku</div>
                </div>
                <div class="w-px h-10 bg-white/20"></div>
                <div class="text-center">
                    <div class="text-2xl font-bold font-display" style="color:#c8873a">350+</div>
                    <div>Anggota Aktif</div>
                </div>
                <div class="w-px h-10 bg-white/20"></div>
                <div class="text-center">
                    <div class="text-2xl font-bold font-display" style="color:#c8873a">50+</div>
                    <div>Kategori</div>
                </div>
            </div>
        </div>
        <p class="text-white/30 text-xs">© {{ date('Y') }} Pustaka Digital</p>
    </div>

    {{-- Kanan --}}
    <div class="flex-1 flex items-center justify-center p-8">
        <div class="w-full max-w-md">
            <div class="mb-8">
                <h2 class="font-display text-3xl font-bold text-gray-800">Selamat Datang</h2>
                <p class="text-gray-500 mt-1">Masuk ke akun Anda untuk melanjutkan</p>
            </div>

            @if(session('error'))
            <div class="bg-red-50 text-red-600 border border-red-200 rounded-lg px-4 py-3 mb-4 text-sm">
                {{ session('error') }}
            </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="input-field" placeholder="nama@email.com">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required
                               class="input-field pr-10" placeholder="••••••••">
                        <button type="button" onclick="togglePass()"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-eye text-sm" id="eye-icon"></i>
                        </button>
                    </div>
                    @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded">
                        Ingat saya
                    </label>
                </div>
                <button type="submit" class="w-full py-3 text-white font-medium rounded-lg transition-all text-sm"
                        style="background:#1a3a4a;" onmouseover="this.style.background='#2d5a70'"
                        onmouseout="this.style.background='#1a3a4a'">
                    Masuk <i class="fas fa-arrow-right ml-1"></i>
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-6">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-medium hover:underline" style="color:#c8873a">
                    Daftar sekarang
                </a>
            </p>
        </div>
    </div>

    <script>
        function togglePass() {
            const pwd = document.getElementById('password');
            const icon = document.getElementById('eye-icon');
            if (pwd.type === 'password') {
                pwd.type = 'text';
                icon.className = 'fas fa-eye-slash text-sm';
            } else {
                pwd.type = 'password';
                icon.className = 'fas fa-eye text-sm';
            }
        }
    </script>
</body>
</html>
