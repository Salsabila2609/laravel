<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OPD extends Model
{
    use HasFactory;

    // Menentukan nama tabel
    protected $table = 'opds';

    // Jika tabel Anda tidak menggunakan timestamp, tambahkan:
    public $timestamps = true;

    // Jika diperlukan, tambahkan kolom yang boleh diisi (mass assignable)
    protected $fillable = ['name', 'address', 'contact', 'link'];
}

