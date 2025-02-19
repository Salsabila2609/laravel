<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use HasFactory;

    protected $table = 'layanans'; // Nama tabel di database
    
    protected $fillable = [
        'title', // Judul layanan
        'url',   // URL layanan
        'icon'   // Path ikon layanan yang diupload
    ];
}
