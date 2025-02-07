<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = ['penulis', 'judul', 'kategori', 'gambar_utama', 'gambar_lampiran', 'isi_berita'];
    public function kategori()
    {
        return $this->belongsToMany(Kategori::class, 'news_kategori', 'news_id', 'kategori_id');
    }
    protected $casts = [
        'gambar_lampiran' => 'array',
    ];    
    // Relasi dengan Attachment
    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

}
