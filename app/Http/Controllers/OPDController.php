<?php

namespace App\Http\Controllers;

use App\Models\OPD;
use Illuminate\Http\Request;

class OPDController extends Controller
{
    // Menampilkan semua OPD
    public function index()
    {
        $opds = OPD::all(); // Mengambil semua data OPD
        return inertia('Profil/OPD', ['opds' => $opds]);
    }

    // Menampilkan halaman tambah OPD dan data yang ada
    public function lgo()
    {
        $opds = OPD::all(); // Mengambil semua data OPD
        return inertia('Admin/Profil/LGO', ['opds' => $opds]);
    }

    // Menampilkan halaman form untuk membuat OPD baru
    // Menyimpan OPD baru
    public function store(Request $request)
    {
        // Validasi dan menyimpan data OPD
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'contact' => 'required|string',
            'link' => 'nullable|url', 
        ]);

        OPD::create($request->all());

        // Redirect ke halaman daftar OPD setelah berhasil ditambahkan
        return redirect()->route('opd.lgo')->with('success', 'OPD berhasil ditambahkan!');
    }

    // Menampilkan halaman form untuk mengedit OPD

    // Mengupdate data OPD yang ada
    public function update(Request $request, $id)
    {
        // Validasi dan memperbarui data OPD
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'contact' => 'required|string',
            'link' => 'nullable|url',
        ]);

        $opd = OPD::findOrFail($id);
        $opd->update($request->all());

        // Redirect ke halaman daftar OPD setelah berhasil diperbarui
        return redirect()->route('opd.lgo')->with('success', 'OPD berhasil diperbarui!');
    }

    // Menghapus data OPD
    public function destroy($id)
    {
        $opd = OPD::findOrFail($id);
        $opd->delete();

        // Redirect ke halaman daftar OPD setelah berhasil dihapus
        return redirect()->route('opd.lgo')->with('success', 'OPD berhasil dihapus!');
    }
}
