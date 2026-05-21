<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->foreignId('buku_id')->constrained('bukus')->onDelete('restrict');
            $table->string('kode_peminjaman', 20)->unique();
            $table->date('tanggal_pinjam');
            $table->date('tanggal_jatuh_tempo');
            $table->date('tanggal_kembali')->nullable();
            $table->enum('status', ['dipinjam','dikembalikan','terlambat'])->default('dipinjam');
            $table->integer('denda')->default(0);
            $table->text('catatan')->nullable();
            $table->foreignId('diproses_oleh')->nullable()->constrained('users');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('peminjamans'); }
};