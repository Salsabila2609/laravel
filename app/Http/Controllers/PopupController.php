<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Popup;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;


class PopupController extends Controller
{
    public function index()
    {
        $popups = Popup::all();
        return Inertia::render('Admin/Popup/PopupUpload', [
            'popups' => $popups
        ]);
    }

    public function store(Request $request)
    {
        // Validasi gambar
        $request->validate([
            'image_popup' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Simpan gambar ke storage
        $imagePath = $request->file('image_popup')->store('popups', 'public');

        // Simpan path gambar ke database
        $popup = Popup::create([
            'image_popup' => $imagePath,
        ]);

        return redirect()->route('popup.index');
    }

    // Di AppServiceProvider atau layout controller
    public function handle($request, Closure $next)
    {
        $popups = Popup::latest()->get();
        
        // Share popups data with all views
        Inertia::share('popups', $popups);
        
        return $next($request);
    }

    public function update(Request $request, $id)
    {
        $popup = Popup::findOrFail($id);
        
        // Validasi gambar baru
        $request->validate([
            'image_popup' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        try {
            if ($request->hasFile('image_popup')) {
                // Hapus gambar lama jika ada
                if ($popup->image_popup && Storage::exists('public/' . $popup->image_popup)) {
                    Storage::delete('public/' . $popup->image_popup);
                }
                
                // Simpan gambar baru
                $imagePath = $request->file('image_popup')->store('popups', 'public');
                $popup->image_popup = $imagePath;
                $popup->save();
                
                return redirect()->route('popup.index')->with('success', 'Popup updated successfully');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update popup: ' . $e->getMessage());
        }
    
        return redirect()->back()->with('error', 'No image provided');
    }

    public function destroy($id)
    {
        $popup = Popup::findOrFail($id);

        // Hapus gambar dari storage
        Storage::delete('public/' . $popup->image_popup);
        $popup->delete();

        return redirect()->route('popup.index');
    }
}
