<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Peminjaman extends Model
{
    protected $table = 'peminjamans';
    
    protected $fillable = [
        'user_id','buku_id','kode_peminjaman','tanggal_pinjam',
        'tanggal_jatuh_tempo','tanggal_kembali','status','denda',
        'catatan','diproses_oleh'
    ];

    protected $casts = [
        'tanggal_pinjam'      => 'date',
        'tanggal_jatuh_tempo' => 'date',
        'tanggal_kembali'     => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($p) {
            $p->kode_peminjaman = 'PJM-' . strtoupper(Str::random(8));
        });
    }

    public function user() { return $this->belongsTo(User::class); }
    public function buku() { return $this->belongsTo(Buku::class); }
    public function diprosesoleh() { return $this->belongsTo(User::class, 'diproses_oleh'); }

    public function getHariTerlambatAttribute() {
        if ($this->status === 'dikembalikan') return 0;
        return max(0, now()->startOfDay()->diffInDays($this->tanggal_jatuh_tempo->startOfDay(), false) * -1);
    }
    public function getDendaHitungAttribute() { return $this->hari_terlambat * 1000; }

    public function scopeAktif($query) { return $query->where('status', 'dipinjam'); }
    public function scopeTerlambat($query) { return $query->where('status', 'terlambat'); }
}