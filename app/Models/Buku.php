<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Buku extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'kategori_id','judul','slug','pengarang','penerbit',
        'tahun_terbit','isbn','stok','stok_tersedia',
        'deskripsi','cover','rak'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($buku) {
            $buku->slug = Str::slug($buku->judul) . '-' . time();
            $buku->stok_tersedia = $buku->stok;
        });
    }

    public function kategori() { return $this->belongsTo(Kategori::class); }
    public function peminjamans() { return $this->hasMany(Peminjaman::class); }

    public function scopeTersedia($query) { return $query->where('stok_tersedia', '>', 0); }
    public function scopeCari($query, $keyword) {
        return $query->where('judul', 'like', "%$keyword%")
                     ->orWhere('pengarang', 'like', "%$keyword%")
                     ->orWhere('isbn', 'like', "%$keyword%");
    }

    public function getCoverUrlAttribute() {
        return $this->cover ? asset('storage/' . $this->cover) : asset('images/default-book.png');
    }
    public function getStatusStokAttribute() {
        if ($this->stok_tersedia == 0) return 'Habis';
        if ($this->stok_tersedia <= 2) return 'Terbatas';
        return 'Tersedia';
    }
}