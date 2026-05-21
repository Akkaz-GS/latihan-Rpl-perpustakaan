<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Kategori extends Model
{
    protected $fillable = ['nama', 'slug', 'deskripsi'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($k) {
            $k->slug = Str::slug($k->nama);
        });
    }

    public function bukus() { return $this->hasMany(Buku::class); }
}   