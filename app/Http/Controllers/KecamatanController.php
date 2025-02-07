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
        return Inertia::render('Profil/KecamatanSekabupaten', [
            'kecamatans' => $kecamatans,
        ]);
    }

    public function subdistrict()
    {
        $kecamatans = Kecamatan::all(); // Pastikan kecamatan adalah model yang benar
        return Inertia::render('Admin/Profil/Subdistrict', [
            'kecamatans' => $kecamatans,
        ]);
    }

        // Show the form to create a new kecamatan
    public function create()
    {
        return Inertia::render('Admin/Profil/AddSubdistrict');
    }

    // Store the newly created kecamatan data
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'link' => 'nullable|url', // Validasi link sebagai URL
        ]);
    
        $kecamatan = Kecamatan::create($validatedData);

        return redirect()->back()->with('success', 'Kecamatan berhasil ditambahkan');
    }
    

    // Show the form to edit an existing Kecamatan
    public function edit($id)
    {
        $kecamatan = Kecamatan::findOrFail($id);
        return Inertia::render('Admin/Profil/EditSubdistrict', [
            'kecamatan' => $kecamatan,
        ]);
    }

    // Update an existing kecamatan
    public function update(Request $request, $id)
    {
        // Validate and update the kecamatan data
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'contact' => 'required|string',
            'link' => 'nullable|url',
        ]);

        $kecamatan = Kecamatan::findOrFail($id);
        $kecamatan->update($request->all());

        // Redirect back to the kecamatan list page after successful update
        return redirect()->route('kecamatan.subdistrict')->with('success', 'Kecamatan berhasil diperbarui!');
    }

    // Delete an kecamatan record
    public function destroy($id)
    {
        $kecamatan = Kecamatan::findOrFail($id);
        $kecamatan->delete();

        // Redirect back to the kecamatan list page after deletion
        return redirect()->route('kecamatan.subdistrict')->with('success', 'Kecamatan berhasil dihapus!');
    }
}
