<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::latest()->get();
        return Inertia::render('Admin/Video/VideoUpload', [
            'videos' => $videos
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'video' => 'required|mimes:mp4,mov,avi,wmv|max:1000000', // Maksimal 50MB
        ]);

        $videoPath = $request->file('video')->store('videos', 'public');

        Video::create([
            'title' => $request->title,
            'video_path' => $videoPath,
        ]);

        return redirect()->route('videos.index')->with('success', 'Video uploaded successfully');
    }

    public function update(Request $request, $id)
    {
        $video = Video::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'video' => 'nullable|mimes:mp4,mov,avi,wmv|max:50000',
        ]);

        if ($request->hasFile('video')) {
            if (Storage::exists('public/' . $video->video_path)) {
                Storage::delete('public/' . $video->video_path);
            }

            $videoPath = $request->file('video')->store('videos', 'public');
            $video->update(['video_path' => $videoPath]);
        }

        $video->update(['title' => $request->title]);

        return redirect()->route('videos.index')->with('success', 'Video updated successfully');
    }

    public function destroy($id)
    {
        $video = Video::findOrFail($id);

        if (Storage::exists('public/' . $video->video_path)) {
            Storage::delete('public/' . $video->video_path);
        }

        $video->delete();

        return redirect()->route('videos.index')->with('success', 'Video deleted successfully');
    }
}
