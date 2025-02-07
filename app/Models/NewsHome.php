<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class NewsHome extends Model
{
    use HasFactory;
    protected $table = 'News';

    protected $fillable = [
        'penulis',
        'judul',
        'isi_berita',
        'gambar_utama',
        'gambar_lampiran',
        'kategori',
        'gambar_utama_keterangan',
        'gambar_lampiran_keterangan',
    ];

    // Accessor untuk format tanggal created_at
    public function getCreatedAtAttribute($value)
    {
        // Format tanggal menjadi format yang lebih mudah dibaca
        return Carbon::parse($value)->format('d-m-Y H:i');
    }

    // Accessor untuk kategori (mengubah JSON menjadi array dan menampilkan kategori)
    public function getKategoriAttribute($value)
    {
        // Jika kategori disimpan dalam format JSON, ubah menjadi array
        return json_decode($value, true);
    }
}