<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class DocumentController extends Controller
{
    // Menampilkan semua dokumen
    public function index()
    {
        $documents = Document::all(); // Mengambil semua dokumen
        return Inertia::render('DocumentsList', ['documents' => $documents]);
    }

    // Menampilkan halaman unggah dokumen
    public function uploadPage()
    {
        $documents = Document::all(); // Mengambil semua dokumen
        return Inertia::render('Admin/PublicInformation/Document', ['documents' => $documents]);
    }

    // Validasi request untuk menyimpan atau mengupdate dokumen
    private function validateDocumentRequest(Request $request, $isUpdate = false)
    {
        $rules = [
            'document_name' => 'required|string|max:255',
            'file' => $isUpdate ? 'nullable|file|mimes:pdf,doc,docx,xls,xlsx' : 'required|file|mimes:pdf,doc,docx,xls,xlsx',
            'category' => 'required|string|max:100',
            'year' => 'nullable|integer|min:2000|max:' . date('Y'), // Validasi tahun jika ada
        ];

        return $request->validate($rules);
    }

    // Menyimpan dokumen baru
    public function store(Request $request)
    {
        $this->validateDocumentRequest($request); // Validasi request

        $path = $request->file('file')->store('documents', 'public');

        Document::create([
            'document_name' => $request->document_name,
            'file_path' => $path,
            'upload_date' => now(),
            'category' => $request->category,
            'year' => $this->determineYear($request->category, $request->year),
        ]);

        return redirect()->route('documents.upload')->with('success', 'Document uploaded successfully!');
    }

    // Mengunduh dokumen dan meningkatkan jumlah unduhan
    public function download($id)
    {
        $document = Document::findOrFail($id);

        $document->increment('download_count'); // Meningkatkan jumlah unduhan

        return response()->download(storage_path('app/public/' . $document->file_path));
    }

    // Mengupdate dokumen yang sudah ada
    public function update(Request $request, $id)
    {
        $this->validateDocumentRequest($request, true); // Validasi request

        $document = Document::findOrFail($id);

        if ($request->hasFile('file')) {
            // Menghapus file lama jika ada dan menyimpan file baru
            $this->replaceFile($document, $request->file('file'));
        }

        // Update data dokumen
        $document->update([
            'document_name' => $request->document_name,
            'category' => $request->category,
            'year' => $this->determineYear($request->category, $request->year),
        ]);

        return redirect()->route('documents.upload')->with('success', 'Document updated successfully!');
    }

    // Menghapus dokumen
    public function destroy($id)
    {
        $document = Document::findOrFail($id);
        $this->deleteFile($document);

        $document->delete();

        return redirect()->route('documents.upload')->with('success', 'Document deleted successfully!');
    }

    // Menampilkan dokumen berdasarkan kategori 'Data Statistik'
    public function DataStatistik()
    {
        return $this->renderDocumentsByCategory('Data Statistik', 'InformasiPublik/DataStatistik');
    }

    // Menampilkan dokumen berdasarkan kategori 'Peraturan Bupati'
    public function PeraturanBupati()
    {
        return $this->renderDocumentsByCategory('Peraturan Bupati', 'InformasiPublik/PeraturanBupati');
    }

    // Menampilkan dokumen berdasarkan kategori 'Laporan Keuangan'
    public function LaporanKeuangan($year = null)
    {
        $query = Document::where('category', 'like', 'Laporan Keuangan%');

        if ($year) {
            $query->where('year', $year);
        }

        $documents = $query->orderBy('year', 'desc')->get();

        return Inertia::render('InformasiPublik/LaporanKeuangan', [
            'documents' => $documents,
            'selectedYear' => $year,
        ]);
    }

    // Fungsi tambahan untuk menentukan tahun pada kategori laporan keuangan
    private function determineYear($category, $year)
    {
        return str_contains(strtolower($category), 'laporan keuangan') ? $year : null;
    }

    // Fungsi tambahan untuk menggantikan file lama dengan file baru
    private function replaceFile(Document $document, $newFile)
    {
        if ($document->file_path) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->file_path = $newFile->store('documents', 'public');
    }

    // Fungsi tambahan untuk menghapus file dari storage
    private function deleteFile(Document $document)
    {
        if ($document->file_path && Storage::exists($document->file_path)) {
            Storage::delete($document->file_path);
        }
    }

    // Fungsi tambahan untuk merender dokumen berdasarkan kategori tertentu
    private function renderDocumentsByCategory($category, $view)
    {
        $documents = Document::where('category', $category)->get();
        return Inertia::render($view, ['documents' => $documents]);
    }
}
