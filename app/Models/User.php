<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
        'role', 'no_anggota', 'no_telepon',
        'alamat', 'tanggal_daftar', 'is_aktif', 'is_verified',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'tanggal_daftar' => 'date',
        'is_aktif'       => 'boolean',
        'is_verified'    => 'boolean',
    ];

    public function isAdmin()      { return $this->role === 'admin'; }
    public function isPustakawan() { return $this->role === 'pustakawan'; }
    public function isAnggota()    { return $this->role === 'anggota'; }
    public function isStaff()      { return in_array($this->role, ['admin', 'pustakawan']); }

    public function peminjamans()  { return $this->hasMany(Peminjaman::class); }

    public function getRoleLabelAttribute(): string {
        return match($this->role) {
            'admin'      => 'Admin',
            'pustakawan' => 'Pustakawan',
            default      => 'Anggota',
        };
    }

    public function getPeminjamanAktifAttribute(): int {
        return $this->peminjamans()->whereIn('status', ['dipinjam','terlambat'])->count();
    }
}