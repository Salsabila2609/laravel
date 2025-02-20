<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->uuid('uuid')->unique(); // UUID sebagai primary identifier
            $table->string('slug')->unique(); // Slug sebagai URL-friendly identifier
            $table->string('penulis');
            $table->string('judul');
            $table->text('isi_berita');
            $table->string('gambar_utama')->nullable();
            $table->json('gambar_lampiran')->nullable();
            $table->text('gambar_utama_keterangan')->nullable();
            $table->text('gambar_lampiran_keterangan')->nullable();
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
