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
        return Inertia::render('Admin/Popup/Popup', [
            'popups' => $popups
        ]);
    }

    public function store(Request $request)
    {
        // Validasi gambar dan title
        $request->validate([
            'title' => 'required|string|max:255',
            'image_popup' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Simpan gambar ke storage
        $imagePath = $request->file('image_popup')->store('popups', 'public');

        // Simpan data ke database
        $popup = Popup::create([
            'title' => $request->title,
            'image_popup' => $imagePath,
        ]);

        return redirect()->route('popup.index');
    }

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
        
        // Validasi input
        $request->validate([
            'title' => 'required|string|max:255',
            'image_popup' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
            }
            
            // Update title
            $popup->title = $request->title;
            $popup->save();
            
            return redirect()->route('popup.index')->with('success', 'Popup updated successfully');
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
