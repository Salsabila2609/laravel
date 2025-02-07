<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Popup extends Model
{
    use HasFactory;

    protected $table = 'Popups';

    // Kolom yang bisa diisi
    protected $fillable = ['image_popup'];
}
