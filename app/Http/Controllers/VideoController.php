<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\RateLimiter;
use Inertia\Inertia;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::latest()->get();
        return Inertia::render('Admin/Video/Video', [
            'videos' => $videos
        ]);
    }

    public function store(Request $request)
    {
        $key = 'video-upload:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            return redirect()->back()->with('error', 'Terlalu banyak percobaan, coba lagi nanti.');
        }
        RateLimiter::hit($key, 60);

        $request->validate([
            'title' => 'required|string|max:255',
            'video' => 'required|file|mimetypes:video/mp4,video/mov,video/avi,video/wmv|max:50000',
        ]);

        if ($request->hasFile('video')) {
            $file = $request->file('video');
            $extension = strtolower($file->getClientOriginalExtension());
            $allowedExtensions = ['mp4', 'mov', 'avi', 'wmv'];

            if (!in_array($extension, $allowedExtensions)) {
                return redirect()->back()->with('error', 'Format video tidak didukung.');
            }

            $hashName = hash_file('sha256', $file->getRealPath()) . '.' . $extension;
            $videoPath = $file->storeAs('videos', $hashName, 'public');

            Video::create([
                'title' => htmlspecialchars(strip_tags($request->title)),
                'video_path' => $videoPath,
            ]);

            return redirect()->route('videos.index')->with('success', 'Video berhasil diunggah');
        }

        return redirect()->back()->with('error', 'Gagal mengunggah video.');
    }

    public function update(Request $request, $id)
    {
        $video = Video::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'video' => 'nullable|file|mimetypes:video/mp4,video/mov,video/avi,video/wmv|max:50000',
        ]);

        if ($request->hasFile('video')) {
            if (!empty($video->video_path) && Storage::disk('public')->exists($video->video_path)) {
                Storage::disk('public')->delete($video->video_path);
            }

            $file = $request->file('video');
            $extension = strtolower($file->getClientOriginalExtension());
            $hashName = hash_file('sha256', $file->getRealPath()) . '.' . $extension;
            $videoPath = $file->storeAs('videos', $hashName, 'public');

            $video->update(['video_path' => $videoPath]);
        }

        $video->update(['title' => htmlspecialchars(strip_tags($request->title))]);

        return redirect()->route('videos.index')->with('success', 'Video berhasil diperbarui');
    }

    public function destroy($id)
    {
        $video = Video::findOrFail($id);

        if (!empty($video->video_path) && Storage::disk('public')->exists($video->video_path)) {
            Storage::disk('public')->delete($video->video_path);
        }

        $video->delete();

        return redirect()->route('videos.index')->with('success', 'Video berhasil dihapus');
    }
}