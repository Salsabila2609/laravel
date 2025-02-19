<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pejabat;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\HttpResponseException;

class PejabatController extends Controller
{
    public function index()
    {
        try {
            $pejabats = Pejabat::select('id', 'name', 'position')->get();
            return Inertia::render('Profil/PejabatDaerah', ['pejabats' => $pejabats]);
        } catch (\Exception $e) {
            Log::error('Error fetching pejabat: ' . $e->getMessage());
            return abort(500, 'Terjadi kesalahan saat mengambil data pejabat.');
        }
    }

    public function localOfficial()
    {
        try {
            $pejabats = Pejabat::select('id', 'name', 'position')->get();
            return Inertia::render('Admin/Profil/LocalOfficial', ['pejabats' => $pejabats]);
        } catch (\Exception $e) {
            Log::error('Error fetching pejabat (admin): ' . $e->getMessage());
            return abort(500, 'Terjadi kesalahan saat mengambil data pejabat.');
        }
    }

    public function create()
    {
        return Inertia::render('Admin/Profil/AddLocalOfficial');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
        ]);

        try {
            Pejabat::create($validated);
            return redirect()->route('pejabat.localOfficial')->with('success', 'Pejabat berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Error storing pejabat: ' . $e->getMessage());
            return abort(500, 'Terjadi kesalahan saat menambahkan pejabat.');
        }
    }

    public function edit($id)
    {
        try {
            $pejabat = Pejabat::findOrFail($id);
            return Inertia::render('Admin/Profil/EditLocalOfficial', ['pejabat' => $pejabat]);
        } catch (ModelNotFoundException $e) {
            return abort(404, 'Pejabat tidak ditemukan.');
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
        ]);

        try {
            $pejabat = Pejabat::findOrFail($id);
            $pejabat->update($validated);
            return redirect()->route('pejabat.localOfficial')->with('success', 'Pejabat berhasil diperbarui!');
        } catch (ModelNotFoundException $e) {
            return abort(404, 'Pejabat tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Error updating pejabat: ' . $e->getMessage());
            return abort(500, 'Terjadi kesalahan saat memperbarui pejabat.');
        }
    }

    public function destroy($id)
    {
        try {
            $pejabat = Pejabat::findOrFail($id);
            $pejabat->delete();
            return redirect()->route('pejabat.localOfficial')->with('success', 'Pejabat berhasil dihapus!');
        } catch (ModelNotFoundException $e) {
            return abort(404, 'Pejabat tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Error deleting pejabat: ' . $e->getMessage());
            return abort(500, 'Terjadi kesalahan saat menghapus pejabat.');
        }
    }
}