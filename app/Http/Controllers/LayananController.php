<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Layanan;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class LayananController extends Controller
{
    public function index()
    {
        $layanans = Layanan::latest()->get();
        return Inertia::render('Admin/Layanan/UpdateLayanan', ['layanans' => $layanans]);
    }

    public function store(Request $request)
{
    
    $request->validate([
        'title' => 'required|string|max:255',
        'url' => 'required|url',
        'icon' => 'required|image|mimes:jpg,png,jpeg,gif,webp|max:5120',
    ]);

    try {
        $path = $request->file('icon')->store('uploads', 'public');
        Layanan::create([
            'title' => $request->title,
            'url' => $request->url,
            'icon' => $path,
        ]);

        return redirect()->back()->with('success', 'Layanan berhasil ditambahkan!');
    } catch (\Exception $e) {
        \Log::error('Error saat menyimpan layanan: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Gagal menyimpan layanan.');
    }
}
    public function update(Request $request, Layanan $layanan)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'url' => 'required|url',
        'icon' => 'nullable|image|mimes:jpg,png,jpeg,gif,webp|max:5120',
    ]);

    $layanan->title = $request->title;
    $layanan->url = $request->url;

    if ($request->hasFile('icon')) {
        if ($layanan->icon) {
            Storage::disk('public')->delete($layanan->icon); // Hapus gambar lama
        }
        $path = $request->file('icon')->store('uploads', 'public'); // Simpan yang baru
        $layanan->icon = $path;
    }

    $layanan->save();

    return redirect()->back()->with('success', 'Layanan berhasil diperbarui!');
}
    public function destroy($id)
{
    $layanan = Layanan::findOrFail($id);

    if ($layanan->icon) {
        Storage::disk('public')->delete($layanan->icon);
    }

    $layanan->delete();

    return redirect()->back()->with('success', 'Layanan berhasil dihapus.');
}
}
