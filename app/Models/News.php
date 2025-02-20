<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class News extends Model
{
    use HasFactory;

    protected $table = 'news';
    protected $primaryKey = 'uuid'; // Menggunakan UUID sebagai primary key
    public $incrementing = false; // Karena UUID bukan angka auto-increment
    protected $keyType = 'string'; // UUID adalah string

    protected $fillable = [
        'uuid',
        'slug',
        'penulis',
        'judul',
        'kategori',
        'gambar_utama',
        'gambar_lampiran',
        'isi_berita',
        'gambar_utama_keterangan',
        'gambar_lampiran_keterangan',
        'views',
    ];

    protected $casts = [
        'gambar_lampiran' => 'array',
  ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($news) {
            $uuid = Str::uuid(); // Buat UUID terlebih dahulu
            $news->uuid = $uuid;
            $news->slug = Str::slug($news->judul) . '-' . substr($uuid, 0, 8); // Gunakan UUID yang sama untuk konsistensi
        });
        
    }

    public function getRouteKeyName()
    {
        return 'uuid'; // Gunakan UUID untuk pencarian model secara default
    }

    // Relasi ke tabel kategori (Many-to-Many)
    public function kategori()
    {
        return $this->belongsToMany(Kategori::class, 'news_kategori', 'news_id', 'kategori_id');
    }

    // Relasi dengan Attachment
    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }
}
