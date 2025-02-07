<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pejabat;
use Inertia\Inertia;

class PejabatController extends Controller
{
    public function index()
    {
        // Ambil data pejabat dari database
        $pejabats = Pejabat::select('id', 'name', 'position')->get();

        // Kirim data ke halaman Inertia
        return Inertia::render('Profil/PejabatDaerah', [
            'pejabats' => $pejabats,
        ]);
    }

    public function localOfficial()
    {
        // Ambil data pejabat yang sama untuk halaman kedua
        $pejabats = Pejabat::select('id', 'name', 'position')->get();

        // Kirim data ke halaman kedua Inertia
        return Inertia::render('Admin/Profil/LocalOfficial', [
            'pejabats' => $pejabats,
        ]);
    }

    // Halaman tambah pejabat
    public function create()
    {
        return Inertia::render('Admin/Profil/AddLocalOfficial');
    }

    // Menyimpan data pejabat baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
        ]);

        Pejabat::create($validated);

        return redirect()->route('pejabat.localOfficial')->with('success', 'Pejabat berhasil ditambahkan!');
    }

    public function edit($id)
{
    // Ambil data pejabat berdasarkan ID
    $pejabat = Pejabat::findOrFail($id);

    // Kirim data ke halaman EditLocalOfficial
    return Inertia::render('Admin/Profil/EditLocalOfficial', [
        'pejabat' => $pejabat
    ]);
}

public function update(Request $request, $id)
{
    // Validasi input
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'position' => 'required|string|max:255',
    ]);

    // Cari pejabat berdasarkan ID
    $pejabat = Pejabat::findOrFail($id);

    // Perbarui data pejabat
    $pejabat->update($validated);

    // Redirect ke halaman daftar pejabat
    return redirect()->route('pejabat.localOfficial')->with('success', 'Pejabat berhasil diperbarui!');
}

public function destroy($id)
{
    // Cari pejabat berdasarkan ID dan hapus
    $pejabat = Pejabat::findOrFail($id);
    $pejabat->delete();

    // Redirect kembali ke daftar pejabat dengan pesan sukses
    return redirect()->route('pejabat.localOfficial')->with('success', 'Pejabat berhasil dihapus!');
}


}
