<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    DashboardController,
    BukuController,
    PeminjamanController,
    LaporanController,
    UserController,
    AnggotaController,
};

// ── Public ──────────────────────────────────────────────────
Route::get('/', fn() => redirect()->route('login'));

// ── Auth (guest only) ────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [AuthController::class, 'login']);
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ── Semua yang sudah login ───────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/buku',          [BukuController::class, 'index'])->name('buku.index');
    Route::get('/buku/{buku}',   [BukuController::class, 'show'])->name('buku.show');
});

// ── ANGGOTA ──────────────────────────────────────────────────
Route::middleware(['auth', 'anggota.verified'])->prefix('anggota')->name('anggota.')->group(function () {
    Route::get('/pinjam/{buku}',             [AnggotaController::class, 'formPinjam'])->name('pinjam.form');
    Route::post('/pinjam',                   [AnggotaController::class, 'ajukanPinjam'])->name('pinjam.store');
    Route::get('/riwayat',                   [AnggotaController::class, 'riwayat'])->name('riwayat');
    Route::patch('/kembalikan/{peminjaman}', [AnggotaController::class, 'ajukanKembali'])->name('kembalikan');
});

// ── PUSTAKAWAN ───────────────────────────────────────────────
Route::middleware(['auth', 'pustakawan'])->prefix('pustakawan')->name('pustakawan.')->group(function () {
    // Kelola Buku
    Route::get('/buku/create',       [BukuController::class, 'create'])->name('buku.create');
    Route::post('/buku',             [BukuController::class, 'store'])->name('buku.store');
    Route::get('/buku/{buku}/edit',  [BukuController::class, 'edit'])->name('buku.edit');
    Route::put('/buku/{buku}',       [BukuController::class, 'update'])->name('buku.update');
    Route::delete('/buku/{buku}',    [BukuController::class, 'destroy'])->name('buku.destroy');

    // Kelola Peminjaman
    Route::get('/peminjaman',                          [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/create',                   [PeminjamanController::class, 'create'])->name('peminjaman.create');
    Route::post('/peminjaman',                         [PeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::get('/peminjaman/{peminjaman}',             [PeminjamanController::class, 'show'])->name('peminjaman.show');
    Route::patch('/peminjaman/{peminjaman}/kembalikan',[PeminjamanController::class, 'kembalikan'])->name('peminjaman.kembalikan');

    // Verifikasi Anggota
    Route::get('/anggota',                      [UserController::class, 'daftarAnggota'])->name('anggota.index');
    Route::patch('/anggota/{user}/verifikasi',  [UserController::class, 'verifikasi'])->name('anggota.verifikasi');
    Route::patch('/anggota/{user}/nonaktif',    [UserController::class, 'nonaktifkan'])->name('anggota.nonaktif');
});

// ── ADMIN ────────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Kelola User
    Route::get('/users',                 [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create',          [UserController::class, 'create'])->name('users.create');
    Route::post('/users',                [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit',     [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}',          [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}',       [UserController::class, 'destroy'])->name('users.destroy');
    Route::patch('/users/{user}/toggle', [UserController::class, 'toggleAktif'])->name('users.toggle');

    // Laporan
    Route::get('/laporan',            [LaporanController::class, 'index'])->name('laporan.index');
});