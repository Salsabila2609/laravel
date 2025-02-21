<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Kecamatan;

class KecamatanController extends Controller
{
    public function index()
    {
        $kecamatans = Kecamatan::all();
        return Inertia::render('Profil/Kecamatan', [
            'kecamatans' => $kecamatans,
        ]);
    }

    public function subdistrict()
    {
        $kecamatans = Kecamatan::all();
        return Inertia::render('Admin/Profil/Subdistrict', [
            'kecamatans' => $kecamatans,
        ]);
    }
    public function create()
    {
        return Inertia::render('Admin/Profil/AddSubdistrict');
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'link' => 'nullable|url', 
        ]);

        Kecamatan::create($validatedData);

        return redirect()->back()->with('success', 'Kecamatan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kecamatan = Kecamatan::findOrFail($id);
        return Inertia::render('Admin/Profil/EditSubdistrict', [
            'kecamatan' => $kecamatan,
        ]);
    }

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

    public function destroy($id)
    {
        $kecamatan = Kecamatan::findOrFail($id);
        $kecamatan->delete();

        return redirect()->route('kecamatan.subdistrict')->with('success', 'Kecamatan berhasil dihapus.');
    }
}
