<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Kecamatan;

class KecamatanController extends Controller
{
    // Menampilkan semua data kecamatan
    public function index()
    {
        $kecamatans = Kecamatan::all();
        return Inertia::render('Profil/Kecamatan', [
            'kecamatans' => $kecamatans,
        ]);
    }

    // Menampilkan halaman admin untuk daftar kecamatan
    public function subdistrict()
    {
        $kecamatans = Kecamatan::all();
        return Inertia::render('Admin/Profil/Subdistrict', [
            'kecamatans' => $kecamatans,
        ]);
    }

    // Menampilkan form untuk menambahkan kecamatan baru
    public function create()
    {
        return Inertia::render('Admin/Profil/AddSubdistrict');
    }

    // Menyimpan data kecamatan baru
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'link' => 'nullable|url', // Validasi URL untuk link opsional
        ]);

        Kecamatan::create($validatedData);

        return redirect()->back()->with('success', 'Kecamatan berhasil ditambahkan.');
    }

    // Menampilkan form untuk mengedit data kecamatan
    public function edit($id)
    {
        $kecamatan = Kecamatan::findOrFail($id);
        return Inertia::render('Admin/Profil/EditSubdistrict', [
            'kecamatan' => $kecamatan,
        ]);
    }

    // Memperbarui data kecamatan yang sudah ada
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'link' => 'nullable|url',
        ]);

        $kecamatan = Kecamatan::findOrFail($id);
        $kecamatan->update($validatedData);

        return redirect()->route('kecamatan.subdistrict')->with('success', 'Kecamatan berhasil diperbarui.');
    }

    // Menghapus data kecamatan
    public function destroy($id)
    {
        $kecamatan = Kecamatan::findOrFail($id);
        $kecamatan->delete();

        return redirect()->route('kecamatan.subdistrict')->with('success', 'Kecamatan berhasil dihapus.');
    }
}
