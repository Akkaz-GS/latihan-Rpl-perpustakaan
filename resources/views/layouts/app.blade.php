<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Pustaka Digital')</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: {
                colors: {
                    primary: { DEFAULT:'#1a3a4a', light:'#2d5a70', dark:'#0f2530' },
                    accent:  { DEFAULT:'#c8873a', light:'#e09a4a' },
                    cream:   { DEFAULT:'#f5f0e8', dark:'#e8e0d0' },
                },
                fontFamily: {
                    display: ['"Playfair Display"','serif'],
                    body:    ['"DM Sans"','sans-serif']
                }
            }}
        }
    </script>
    <style>
        body { font-family:'DM Sans',sans-serif; background:#f5f0e8; }
        .font-display { font-family:'Playfair Display',serif; }
        .sidebar-link { transition:all .15s; }
        .sidebar-link.active { background:rgba(200,135,58,.15); color:#c8873a; border-left:3px solid #c8873a; }
        .sidebar-link:hover:not(.active) { background:rgba(255,255,255,.05); }
        .card { background:white; border-radius:12px; box-shadow:0 2px 12px rgba(26,58,74,.08); }
        .btn-primary { background:#1a3a4a; color:white; padding:.5rem 1.25rem; border-radius:8px; font-weight:500; transition:all .2s; display:inline-block; }
        .btn-primary:hover { background:#2d5a70; }
        .btn-accent { background:#c8873a; color:white; padding:.5rem 1.25rem; border-radius:8px; font-weight:500; transition:all .2s; display:inline-block; }
        .btn-accent:hover { background:#e09a4a; }
        .badge-dipinjam     { background:#dbeafe; color:#1e40af; padding:2px 8px; border-radius:999px; font-size:12px; }
        .badge-dikembalikan { background:#d1fae5; color:#065f46; padding:2px 8px; border-radius:999px; font-size:12px; }
        .badge-terlambat    { background:#fee2e2; color:#991b1b; padding:2px 8px; border-radius:999px; font-size:12px; }
        .badge-tersedia     { background:#d1fae5; color:#065f46; padding:2px 8px; border-radius:999px; font-size:12px; }
        .badge-terbatas     { background:#fef3c7; color:#92400e; padding:2px 8px; border-radius:999px; font-size:12px; }
        .badge-habis        { background:#fee2e2; color:#991b1b; padding:2px 8px; border-radius:999px; font-size:12px; }
        .input-field { width:100%; padding:.625rem .875rem; border:1.5px solid #e5e7eb; border-radius:8px; font-size:.875rem; transition:all .2s; outline:none; }
        .input-field:focus { border-color:#1a3a4a; box-shadow:0 0 0 3px rgba(26,58,74,.1); }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen">
<div class="flex h-screen overflow-hidden">

    {{-- SIDEBAR --}}
    <aside class="w-64 flex flex-col fixed h-full z-30" style="background:#0f2530;">
        <div class="p-6 border-b border-white/10">
            <h1 class="font-display text-2xl font-bold" style="color:#c8873a">📚 Pustaka</h1>
            <p class="text-white/40 text-xs mt-1">Sistem Perpustakaan Digital</p>
        </div>

        <div class="px-4 py-3 border-b border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0"
                    style="{{ auth()->user()->isAdmin() ? 'background:#7c3aed' : (auth()->user()->isPustakawan() ? 'background:#0284c7' : 'background:#c8873a') }}; color:white">
                    {{ strtoupper(substr(auth()->user()->name,0,1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                    <span class="text-xs px-1.5 py-0.5 rounded font-medium
                        {{ auth()->user()->isAdmin() ? 'bg-violet-500/20 text-violet-300' : (auth()->user()->isPustakawan() ? 'bg-sky-500/20 text-sky-300' : 'bg-amber-500/20 text-amber-300') }}">
                        {{ auth()->user()->role_label }}
                    </span>
                </div>
            </div>
        </div>

        <nav class="flex-1 px-3 py-4 overflow-y-auto space-y-0.5">
            <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/70 text-sm {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-home w-4 text-center"></i> Dashboard
            </a>
            <a href="{{ route('buku.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/70 text-sm {{ request()->routeIs('buku.*') ? 'active' : '' }}">
                <i class="fas fa-search w-4 text-center"></i> Cari Buku
            </a>

            @if(auth()->user()->isAnggota())
            <div class="pt-3 pb-1"><p class="text-white/25 text-xs uppercase tracking-widest px-3">Peminjaman</p></div>
            <a href="{{ route('anggota.riwayat') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/70 text-sm {{ request()->routeIs('anggota.riwayat') ? 'active' : '' }}">
                <i class="fas fa-history w-4 text-center"></i> Riwayat Pinjaman
            </a>
            @endif

            @if(auth()->user()->isPustakawan())
            <div class="pt-3 pb-1"><p class="text-white/25 text-xs uppercase tracking-widest px-3">Kelola Buku</p></div>
            <a href="{{ route('pustakawan.buku.create') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/70 text-sm {{ request()->routeIs('pustakawan.buku.*') ? 'active' : '' }}">
                <i class="fas fa-book-medical w-4 text-center"></i> Tambah Buku
            </a>
            <div class="pt-3 pb-1"><p class="text-white/25 text-xs uppercase tracking-widest px-3">Peminjaman</p></div>
            <a href="{{ route('pustakawan.peminjaman.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/70 text-sm {{ request()->routeIs('pustakawan.peminjaman.*') ? 'active' : '' }}">
                <i class="fas fa-hand-holding-heart w-4 text-center"></i> Kelola Peminjaman
            </a>
            <a href="{{ route('pustakawan.peminjaman.create') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/70 text-sm">
                <i class="fas fa-plus w-4 text-center"></i> Catat Peminjaman
            </a>
            <div class="pt-3 pb-1"><p class="text-white/25 text-xs uppercase tracking-widest px-3">Anggota</p></div>
            <a href="{{ route('pustakawan.anggota.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/70 text-sm {{ request()->routeIs('pustakawan.anggota.*') ? 'active' : '' }}">
                <i class="fas fa-user-check w-4 text-center"></i> Verifikasi Anggota
            </a>
            @endif

            @if(auth()->user()->isAdmin())
            <div class="pt-3 pb-1"><p class="text-white/25 text-xs uppercase tracking-widest px-3">Manajemen</p></div>
            <a href="{{ route('admin.users.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/70 text-sm {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-users-cog w-4 text-center"></i> Kelola User
            </a>
            <div class="pt-3 pb-1"><p class="text-white/25 text-xs uppercase tracking-widest px-3">Laporan</p></div>
            <a href="{{ route('admin.laporan.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/70 text-sm {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                <i class="fas fa-chart-bar w-4 text-center"></i> Laporan & Statistik
            </a>
            @endif
        </nav>

        <div class="p-4 border-t border-white/10">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="w-full flex items-center gap-3 px-3 py-2 text-white/50 hover:text-white text-sm transition-all">
                    <i class="fas fa-sign-out-alt w-4 text-center"></i> Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="flex-1 ml-64 overflow-y-auto">
        <header class="bg-white border-b border-gray-100 px-8 py-4 flex items-center justify-between sticky top-0 z-20">
            <div>
                <h2 class="font-display text-xl font-semibold text-primary">@yield('page-title','Dashboard')</h2>
                <p class="text-sm text-gray-400">@yield('page-subtitle')</p>
            </div>
            <div class="flex items-center gap-3">
                @yield('header-actions')
                <span class="text-sm text-gray-400">{{ now()->isoFormat('dddd, D MMMM Y') }}</span>
            </div>
        </header>

        <div class="px-8 pt-5">
            @if(session('success'))
            <div class="flex items-center gap-3 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg px-4 py-3 mb-4 text-sm">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="flex items-center gap-3 bg-red-50 text-red-700 border border-red-200 rounded-lg px-4 py-3 mb-4 text-sm">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
            @endif
            @if(session('warning'))
            <div class="flex items-center gap-3 bg-amber-50 text-amber-700 border border-amber-200 rounded-lg px-4 py-3 mb-4 text-sm">
                <i class="fas fa-exclamation-triangle"></i> {{ session('warning') }}
            </div>
            @endif
            @if(session('info'))
            <div class="flex items-center gap-3 bg-blue-50 text-blue-700 border border-blue-200 rounded-lg px-4 py-3 mb-4 text-sm">
                <i class="fas fa-info-circle"></i> {{ session('info') }}
            </div>
            @endif
            @if($errors->any())
            <div class="bg-red-50 text-red-700 border border-red-200 rounded-lg px-4 py-3 mb-4">
                <ul class="list-disc list-inside space-y-1 text-sm">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
            @endif
        </div>

        <div class="px-8 py-4">@yield('content')</div>
    </main>
</div>
@stack('scripts')
</body>
</html>