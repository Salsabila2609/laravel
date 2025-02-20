<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'documents';

    protected $fillable = ['document_name', 'upload_date', 'file_path', 'category', 'year'];
}
