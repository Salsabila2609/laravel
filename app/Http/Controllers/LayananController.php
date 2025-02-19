<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Layanan;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class LayananController extends Controller
{
    // READ: Menampilkan semua layanan
    public function index()
    {
        $layanans = Layanan::latest()->get();
        return Inertia::render('Admin/Layanan/UpdateLayanan', ['layanans' => $layanans]);
    }

    // CREATE: Menambahkan layanan baru
    public function store(Request $request)
{
    // Debugging: Periksa request masuk dengan benar
    \Log::info('Data yang diterima:', $request->all());

    // Validasi data
    $request->validate([
        'title' => 'required|string|max:255',
        'url' => 'required|url',
        'icon' => 'required|image|mimes:jpg,png,jpeg,gif,webp|max:5120',
    ]);

    try {
        // Simpan file ikon ke storage
        $path = $request->file('icon')->store('uploads', 'public');

        // Simpan ke database
        Layanan::create([
            'title' => $request->title,
            'url' => $request->url,
            'icon' => $path,
        ]);

        \Log::info('Layanan berhasil disimpan ke database.');
        return redirect()->back()->with('success', 'Layanan berhasil ditambahkan!');
    } catch (\Exception $e) {
        \Log::error('Error saat menyimpan layanan: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Gagal menyimpan layanan.');
    }
}

    // UPDATE: Mengedit layanan
    public function update(Request $request, Layanan $layanan)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'url' => 'required|url',
        'icon' => 'nullable|image|mimes:jpg,png,jpeg,gif,webp|max:5120',
    ]);

    // Update teks
    $layanan->title = $request->title;
    $layanan->url = $request->url;

    // Jika ada file baru, hapus yang lama lalu simpan yang baru
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

    // DELETE: Menghapus layanan
    public function destroy($id)
{
    $layanan = Layanan::findOrFail($id);

    // Hapus gambar hanya jika path icon tidak null
    if ($layanan->icon) {
        Storage::disk('public')->delete($layanan->icon);
    }

    // Hapus data layanan dari database
    $layanan->delete();

    return redirect()->back()->with('success', 'Layanan berhasil dihapus.');
}
}
