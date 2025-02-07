<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('penulis');
            $table->string('judul');
            $table->text('isi_berita');
            $table->string('gambar_utama')->nullable();
            $table->json('gambar_lampiran')->nullable();  // This will store multiple images as JSON
            $table->text('gambar_utama_keterangan')->nullable();  // Keterangan untuk gambar utama
            $table->text('gambar_lampiran_keterangan')->nullable();  // Keterangan untuk gambar lampiran
            $table->json('kategori');
            $table->timestamps();
            $table->integer('views')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
