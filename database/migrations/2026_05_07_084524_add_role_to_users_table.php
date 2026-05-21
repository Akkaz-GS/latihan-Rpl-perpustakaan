<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin','pustakawan','anggota'])->default('anggota')->after('email');
            $table->string('no_anggota', 20)->unique()->nullable()->after('role');
            $table->string('no_telepon', 20)->nullable()->after('no_anggota');
            $table->text('alamat')->nullable()->after('no_telepon');
            $table->date('tanggal_daftar')->nullable()->after('alamat');
            $table->boolean('is_aktif')->default(true)->after('tanggal_daftar');
            $table->boolean('is_verified')->default(false)->after('is_aktif');
        });
    }
    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role','no_anggota','no_telepon','alamat','tanggal_daftar','is_aktif','is_verified']);
        });
    }
};