<?php
namespace Database\Seeders;

use App\Models\{User, Kategori, Buku};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'           => 'Super Admin',
            'email'          => 'admin@pustaka.id',
            'password'       => Hash::make('password'),
            'role'           => 'admin',
            'no_anggota'     => 'ADM-0001',
            'tanggal_daftar' => now(),
            'is_aktif'       => true,
            'is_verified'    => true,
        ]);

        User::create([
            'name'           => 'Siti Rahayu',
            'email'          => 'pustakawan@pustaka.id',
            'password'       => Hash::make('password'),
            'role'           => 'pustakawan',
            'no_anggota'     => 'PST-0001',
            'no_telepon'     => '081234567890',
            'tanggal_daftar' => now(),
            'is_aktif'       => true,
            'is_verified'    => true,
        ]);

        User::create([
            'name'           => 'Budi Santoso',
            'email'          => 'budi@example.com',
            'password'       => Hash::make('password'),
            'role'           => 'anggota',
            'no_anggota'     => 'ANG-0001',
            'no_telepon'     => '089876543210',
            'tanggal_daftar' => now(),
            'is_aktif'       => true,
            'is_verified'    => true,
        ]);

        $kategoris = ['Fiksi','Non-Fiksi','Sains & Teknologi','Sejarah','Filsafat','Sastra'];
        foreach ($kategoris as $nama) {
            Kategori::create(['nama' => $nama, 'slug' => \Illuminate\Support\Str::slug($nama)]);
        }

        $books = [
            ['judul'=>'Laskar Pelangi',   'pengarang'=>'Andrea Hirata',         'penerbit'=>'Bentang Pustaka', 'tahun'=>2005, 'stok'=>5, 'kat'=>1],
            ['judul'=>'Bumi Manusia',      'pengarang'=>'Pramoedya Ananta Toer', 'penerbit'=>'Lentera',         'tahun'=>1980, 'stok'=>3, 'kat'=>6],
            ['judul'=>'Sapiens',           'pengarang'=>'Yuval Noah Harari',     'penerbit'=>'KPG',             'tahun'=>2014, 'stok'=>4, 'kat'=>2],
            ['judul'=>'Clean Code',        'pengarang'=>'Robert C. Martin',      'penerbit'=>'Prentice Hall',   'tahun'=>2008, 'stok'=>2, 'kat'=>3],
            ['judul'=>'Atomic Habits',     'pengarang'=>'James Clear',           'penerbit'=>'Penguin',         'tahun'=>2018, 'stok'=>6, 'kat'=>2],
            ['judul'=>'Sejarah Indonesia', 'pengarang'=>'M.C. Ricklefs',         'penerbit'=>'Gadjah Mada UP',  'tahun'=>2001, 'stok'=>3, 'kat'=>4],
        ];

        foreach ($books as $b) {
            Buku::create([
                'kategori_id'  => $b['kat'],
                'judul'        => $b['judul'],
                'pengarang'    => $b['pengarang'],
                'penerbit'     => $b['penerbit'],
                'tahun_terbit' => $b['tahun'],
                'stok'         => $b['stok'],
                'rak'          => 'RAK-' . chr(64 + $b['kat']),
            ]);
        }
    }
}