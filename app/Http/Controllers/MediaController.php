<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Media;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    // READ: Menampilkan semua media
    public function index()
    {
        $media = Media::latest()->get();
        return Inertia::render('Admin/Media/Media', ['media' => $media]);
    }

    // CREATE: Upload media baru
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255', // Validasi title
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,webp|max:5120',
            'url' => 'nullable|url',
        ]);
        
        $path = $request->file('image')->store('uploads', 'public');

        Media::create([
            'title' => $request->title, // Menambahkan title
            'image' => $path,
            'url' => $request->url,
        ]);

        return redirect()->back()->with('success', 'Media berhasil diunggah!');
    }

    // UPDATE: Edit media
    public function update(Request $request, Media $media)
    {
        $request->validate([
            'title' => 'required|string|max:255', // Validasi title
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,webp|max:5120',
            'url' => 'nullable|url',
        ]);
        
    
        // Jika ada gambar baru, hapus gambar lama dan simpan yang baru
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($media->image); // Hapus gambar lama
            $path = $request->file('image')->store('uploads', 'public'); // Simpan gambar baru
            $media->image = $path;
        }

        // Update title dan URL
        $media->title = $request->title; // Update title
        $media->url = $request->url;
        $media->save();

        return redirect()->back()->with('success', 'Media berhasil diperbarui!');
    }

    // DELETE: Hapus media
    public function destroy(Media $media)
    {
        Storage::disk('public')->delete($media->image);
        $media->delete();

        return redirect()->back()->with('success', 'Media berhasil dihapus!');
    }
}
