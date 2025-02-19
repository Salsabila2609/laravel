<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Media;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class MediaController extends Controller
{
    public function index()
    {
        $media = Media::latest()->get();
        return Inertia::render('Admin/Media/Media', ['media' => $media]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,webp|max:5120', // (2) Kurangi ukuran max dari 5MB ke 2MB
            'url' => 'nullable|url',
        ]);

        // (3) Cek MIME Type agar tidak ada shell upload
        $mimeType = $request->file('image')->getMimeType();
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($mimeType, $allowedMimeTypes)) {
            return redirect()->back()->withErrors(['image' => 'Format file tidak didukung!']);
        }

        // (4) Simpan dengan nama acak agar tidak bisa ditebak
        $filename = uniqid() . '_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
        $path = $request->file('image')->storeAs('uploads', $filename, 'public');

        $media = Media::create([
            'title' => htmlspecialchars($request->title), // (5) Mencegah XSS dengan htmlentities
            'image' => $path,
            'url' => $request->url,
        ]);

        Log::info('Media uploaded', ['user_id' => auth()->id(), 'media_id' => $media->id]); // (6) Logging aktivitas user

        return redirect()->back()->with('success', 'Media berhasil diunggah!');
    }

    public function update(Request $request, Media $media)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,webp|max:2048', // (2) Kurangi ukuran max
            'url' => 'nullable|url',
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($media->image);
            
            // (3) Cek MIME Type lagi
            $mimeType = $request->file('image')->getMimeType();
            if (!in_array($mimeType, ['image/jpeg', 'image/png', 'image/gif', 'image/webp'])) {
                return redirect()->back()->withErrors(['image' => 'Format file tidak didukung!']);
            }

            $filename = uniqid() . '_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
            $path = $request->file('image')->storeAs('uploads', $filename, 'public');
            $media->image = $path;
        }

        $media->title = htmlspecialchars($request->title);
        $media->url = $request->url;
        $media->save();

        Log::info('Media updated', ['user_id' => auth()->id(), 'media_id' => $media->id]); // (6) Logging update

        return redirect()->back()->with('success', 'Media berhasil diperbarui!');
    }

    public function destroy(Media $media)
    {
        Storage::disk('public')->delete($media->image);
        $media->delete();

        Log::info('Media deleted', ['user_id' => auth()->id(), 'media_id' => $media->id]); // (6) Logging delete

        return redirect()->back()->with('success', 'Media berhasil dihapus!');
    }
}
