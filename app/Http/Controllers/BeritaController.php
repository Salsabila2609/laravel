<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use HTMLPurifier;
use HTMLPurifier_Config;

class BeritaController extends Controller
{
    public function index()
    {
        $news = News::orderBy('created_at', 'desc')->get()->map(function ($item) {
            if (!is_array($item->kategori)) {
                $decodedKategori = json_decode($item->kategori, true);
                if (is_string($decodedKategori)) {
                    $decodedKategori = json_decode($decodedKategori, true);
                }
                $item->kategori = $decodedKategori;
            }
            $item->tanggal_terbit = $item->created_at->format('d M Y'); 
            $item->gambar_lampiran = is_string($item->gambar_lampiran)
                ? json_decode($item->gambar_lampiran, true)
                : $item->gambar_lampiran;
    
            return $item;
        });
    
        // Hitung jumlah berita per kategori
        $categoriesWithCount = News::all()
            ->flatMap(function ($news) {
                $kategori = json_decode($news->kategori, true);
                return is_array($kategori) ? $kategori : [];
            })
            ->countBy()
            ->map(function ($count, $category) {
                return [
                    'category' => $category,
                    'count' => $count,
                ];
            })
            ->values();
    
        // Tambahkan jumlah total berita
        $totalNewsCount = News::count();
    
        return inertia('Berita/Berita', [
            'newsItems' => $news,
            'categoriesWithCount' => $categoriesWithCount,
            'totalNewsCount' => $totalNewsCount,
        ]);
    }    

    public function showByCategory($kategori)
    {
        // Decode kategori jika diperlukan
        $kategori = urldecode($kategori);
    
        // Debug: Log kategori yang diterima
        \Log::info('Kategori yang diterima:', ['kategori' => $kategori]);
    
        // Ambil berita berdasarkan kategori
        $news = News::whereJsonContains('kategori', $kategori)->get()->map(function ($item) {
            // Proses kategori jika diperlukan
            if (!is_array($item->kategori)) {
                $decodedKategori = json_decode($item->kategori, true);
                if (is_string($decodedKategori)) {
                    $decodedKategori = json_decode($decodedKategori, true);
                }
                $item->kategori = $decodedKategori;
            }
    
            // Format tanggal
            $item->tanggal_terbit = $item->created_at->format('d M Y');
            // Periksa dan decode gambar_lampiran jika ada
            $item->gambar_lampiran = is_string($item->gambar_lampiran)
                ? json_decode($item->gambar_lampiran, true)
                : $item->gambar_lampiran;
    
            return $item;
        });
    
        // Hitung jumlah berita per kategori
        $categoriesWithCount = News::all()
            ->flatMap(function ($news) {
                $kategori = json_decode($news->kategori, true);
                return is_array($kategori) ? $kategori : [];
            })
            ->countBy()
            ->map(function ($count, $category) {
                return [
                    'category' => $category,
                    'count' => $count,
                ];
            })
            ->values();
    
        // Tambahkan jumlah total berita
        $totalNewsCount = News::count();
    
        return inertia('Berita/Berita', [
            'newsItems' => $news,
            'categoriesWithCount' => $categoriesWithCount,
            'totalNewsCount' => $totalNewsCount,
            'activeCategory' => $kategori,
        ]);
    }
    
    public function show($id)
    {
        $news = News::findOrFail($id);

        $news->increment('views');
    
        // Decode kategori berita utama
        $decodedKategori = json_decode($news->kategori, true);
        if (is_string($decodedKategori)) {
            $decodedKategori = json_decode($decodedKategori, true);
        }
        $news->kategori = $decodedKategori;
        $news->tanggal_terbit = $news->created_at->format('d M Y');
    
        // Decode gambar lampiran dan keterangan gambar lampiran menjadi array
        if (is_string($news->gambar_lampiran)) {
            $news->gambar_lampiran = json_decode($news->gambar_lampiran, true);
        }
        if (is_string($news->gambar_lampiran_keterangan)) {
            $news->gambar_lampiran_keterangan = json_decode($news->gambar_lampiran_keterangan, true);
        }
    
        // Ambil berita terkait berdasarkan kategori
        $relatedNews = News::where(function ($query) use ($decodedKategori) {
                if (!empty($decodedKategori)) {
                    $query->where(function ($q) use ($decodedKategori) {
                        foreach ($decodedKategori as $kategori) {
                            $q->orWhere('kategori', 'like', '%' . $kategori . '%');
                        }
                    });
                }
            })
            ->where('id', '!=', $id) // Jangan tampilkan berita yang sedang dibuka
            ->limit(5) // Batasi jumlah berita terkait
            ->get()
            ->map(function ($related) {
                $related->kategori = json_decode($related->kategori, true);
                $related->tanggal_terbit = $related->created_at->format('d M Y');
                $related->gambar_lampiran = is_string($related->gambar_lampiran) 
                    ? json_decode($related->gambar_lampiran, true) 
                    : $related->gambar_lampiran;
                return $related;
            });
    
        // Hitung jumlah berita per kategori
        $uniqueCategoriesWithCount = News::all()
            ->flatMap(function ($news) {
                $kategori = json_decode($news->kategori, true);
                return is_array($kategori) ? $kategori : [];
            })
            ->countBy() // Hitung jumlah berita per kategori
            ->map(function ($count, $category) {
                return [
                    'category' => $category,
                    'count' => $count,
                ];
            })
            ->values();
    
        // Kirim data ke frontend
        return Inertia::render('Berita/DetailBerita', [
            'news' => $news,
            'related' => $relatedNews,
            'categoriesWithCount' => $uniqueCategoriesWithCount,
        ]);
    }   
}
