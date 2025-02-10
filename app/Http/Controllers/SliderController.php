<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class SliderController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Sliders/Index', [
            'sliders' => Slider::orderBy('order', 'asc')->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            $imagePath = null;

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = time() . '_' . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();
                $imagePath = $file->storeAs('public/sliders', $fileName);
                $imagePath = Storage::url($imagePath);
            }

            $maxOrder = Slider::max('order') ?? 0;

            Slider::create([
                'title' => $request->title,
                'description' => $request->description,
                'image_path' => $imagePath,
                'is_active' => true,
                'order' => $maxOrder + 1
            ]);

            return redirect()->back()->with('message', 'Slider berhasil ditambahkan');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan slider');
        }
    }

    public function destroy(Slider $slider)
    {
        try {
            if ($slider->image_path) {
                $path = str_replace('/storage/', 'public/', $slider->image_path);
                Storage::delete($path);
            }

            $slider->delete();

            return redirect()->back()->with('message', 'Slider berhasil dihapus');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus slider');
        }
    }

    public function toggleActive(Slider $slider)
    {
        try {
            $slider->update([
                'is_active' => !$slider->is_active
            ]);

            return redirect()->back()->with('message', 'Status slider berhasil diperbarui');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui status');
        }
    }
}