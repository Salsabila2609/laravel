<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageUploadController extends Controller
{
    public function upload(Request $request)
    {
        // Validasi file
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Simpan file ke storage
        $file = $request->file('file');
        $path = $file->store('images', 'public'); // Menyimpan di storage/app/public/images

        // Kembalikan URL file yang sudah disimpan
        return response()->json([
            'success' => true,
            'url' => Storage::url($path), // URL file di storage publik
        ]);
    }
}
