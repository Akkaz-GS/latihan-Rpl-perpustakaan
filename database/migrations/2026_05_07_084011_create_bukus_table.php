<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('bukus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategoris')->onDelete('restrict');
            $table->string('judul');
            $table->string('slug')->unique();
            $table->string('pengarang');
            $table->string('penerbit');
            $table->year('tahun_terbit');
            $table->string('isbn', 20)->unique()->nullable();
            $table->integer('stok')->default(0);
            $table->integer('stok_tersedia')->default(0);
            $table->text('deskripsi')->nullable();
            $table->string('cover')->nullable();
            $table->string('rak')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down(): void { Schema::dropIfExists('bukus'); }
};