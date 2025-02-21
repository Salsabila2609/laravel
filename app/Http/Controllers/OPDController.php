<?php

namespace App\Http\Controllers;

use App\Models\OPD;
use Illuminate\Http\Request;

class OPDController extends Controller
{
    public function index()
    {
        $opds = OPD::all(); 
        return inertia('Profil/OPD', ['opds' => $opds]);
    }

    public function lgo()
    {
        $opds = OPD::all(); 
        return inertia('Admin/Profil/LGO', ['opds' => $opds]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'contact' => 'required|string',
            'link' => 'nullable|url', 
            'file' => 'nullable|file|mimes:jpg,png,pdf|max:2048' // Validasi file upload
        ]);

        $data = $request->only(['name', 'address', 'contact', 'link']);

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('uploads/opd', 'public');
        }

        OPD::create($data);

        return redirect()->route('opd.lgo')->with('success', 'OPD berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'contact' => 'required|string',
            'link' => 'nullable|url',
            'file' => 'nullable|file|mimes:jpg,png,pdf|max:5120' // Validasi file upload
        ]);

        $opd = OPD::findOrFail($id);
        $data = $request->only(['name', 'address', 'contact', 'link']);

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('uploads/opd', 'public');
        }

        $opd->update($data);

        return redirect()->route('opd.lgo')->with('success', 'OPD berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $opd = OPD::findOrFail($id);
        $opd->delete();
        return redirect()->route('opd.lgo')->with('success', 'OPD berhasil dihapus!');
    }
}
