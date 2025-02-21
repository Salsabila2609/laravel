<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class DocumentController extends Controller
{    public function index()
    {
        $documents = Document::all(); 
        return Inertia::render('DocumentsList', ['documents' => $documents]);
    }
     public function uploadPage()
    {
        $documents = Document::all(); 
        return Inertia::render('Admin/PublicInformation/Document', ['documents' => $documents]);
    }
     private function validateDocumentRequest(Request $request, $isUpdate = false)
    {
        $rules = [
            'document_name' => 'required|string|max:255',
            'file' => $isUpdate ? 'nullable|file|mimes:pdf,doc,docx,xls,xlsx' : 'required|file|mimes:pdf,doc,docx,xls,xlsx',
            'category' => 'required|string|max:100',
            'year' => 'nullable|integer|min:2000|max:' . date('Y'), 
        ];

        return $request->validate($rules);
    }
    public function store(Request $request)
    {
        $this->validateDocumentRequest($request); 

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
    public function download($id)
    {
        $document = Document::findOrFail($id);

        $document->increment('download_count'); 

        return response()->download(storage_path('app/public/' . $document->file_path));
    }
    public function update(Request $request, $id)
    {
        $this->validateDocumentRequest($request, true); 
        $document = Document::findOrFail($id);

        if ($request->hasFile('file')) {
            $this->replaceFile($document, $request->file('file'));
        }

        $document->update([
            'document_name' => $request->document_name,
            'category' => $request->category,
            'year' => $this->determineYear($request->category, $request->year),
        ]);

        return redirect()->route('documents.upload')->with('success', 'Document updated successfully!');
    }

    public function destroy($id)
    {
        $document = Document::findOrFail($id);
        $this->deleteFile($document);

        $document->delete();

        return redirect()->route('documents.upload')->with('success', 'Document deleted successfully!');
    }
     public function DataStatistik()
    {
        return $this->renderDocumentsByCategory('Data Statistik', 'InformasiPublik/DataStatistik');
    }

    public function PeraturanBupati()
    {
        return $this->renderDocumentsByCategory('Peraturan Bupati', 'InformasiPublik/PeraturanBupati');
    }

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

    private function determineYear($category, $year)
    {
        return str_contains(strtolower($category), 'laporan keuangan') ? $year : null;
    }
    private function replaceFile(Document $document, $newFile)
    {
        if ($document->file_path) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->file_path = $newFile->store('documents', 'public');
    }

    private function deleteFile(Document $document)
    {
        if ($document->file_path && Storage::exists($document->file_path)) {
            Storage::delete($document->file_path);
        }
    }

   private function renderDocumentsByCategory($category, $view)
    {
        $documents = Document::where('category', $category)->get();
        return Inertia::render($view, ['documents' => $documents]);
    }
}
