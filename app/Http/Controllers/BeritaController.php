<?php

namespace App\Http\Controllers;

use App\Models\News;
use Inertia\Inertia;
use Illuminate\Support\Facades\Cache;

class BeritaController extends Controller
{
    public function index()
    {
        // Cache the news data for better performance
        $news = Cache::remember('public_news', 3600, function () {
            return News::latest()
                ->get()
                ->map(function ($item) {
                    // Process kategori
                    if (!is_array($item->kategori)) {
                        $decodedKategori = json_decode($item->kategori, true);
                        if (is_string($decodedKategori)) {
                            $decodedKategori = json_decode($decodedKategori, true);
                        }
                        $item->kategori = $decodedKategori;
                    }
                    
                    // Format date
                    $item->tanggal_terbit = $item->created_at->format('d M Y');
                    
                    // Process attachments
                    $item->gambar_lampiran = is_string($item->gambar_lampiran)
                        ? json_decode($item->gambar_lampiran, true)
                        : $item->gambar_lampiran;
                    
                    return $item;
                });
        });

        // Get categories with count
        $categoriesWithCount = Cache::remember('public_categories_count', 3600, function () {
            return News::all()
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
        });

        // Get total news count
        $totalNewsCount = Cache::remember('public_total_news', 3600, function () {
            return News::count();
        });

        return inertia('Berita/Index', [
            'newsItems' => $news,
            'categoriesWithCount' => $categoriesWithCount,
            'totalNewsCount' => $totalNewsCount,
        ]);
    }

    public function showByCategory($kategori)
    {
        $kategori = urldecode($kategori);
        
        $news = News::whereJsonContains('kategori', $kategori)
            ->latest()
            ->get()
            ->map(function ($item) {
                // Process kategori
                if (!is_array($item->kategori)) {
                    $decodedKategori = json_decode($item->kategori, true);
                    if (is_string($decodedKategori)) {
                        $decodedKategori = json_decode($decodedKategori, true);
                    }
                    $item->kategori = $decodedKategori;
                }
                
                // Format date
                $item->tanggal_terbit = $item->created_at->format('d M Y');
                
                // Process attachments
                $item->gambar_lampiran = is_string($item->gambar_lampiran)
                    ? json_decode($item->gambar_lampiran, true)
                    : $item->gambar_lampiran;
                
                return $item;
            });

        // Get categories with count
        $categoriesWithCount = Cache::remember('public_categories_count', 3600, function () {
            return News::all()
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
        });

        $totalNewsCount = Cache::remember('public_total_news', 3600, function () {
            return News::count();
        });

        return inertia('Berita/Index', [
            'newsItems' => $news,
            'categoriesWithCount' => $categoriesWithCount,
            'totalNewsCount' => $totalNewsCount,
            'activeCategory' => $kategori,
        ]);
    }

    public function show($id)
    {
        $news = News::findOrFail($id);

        // Increment view count
        $news->increment('views');

        // Process kategori
        $decodedKategori = json_decode($news->kategori, true);
        if (is_string($decodedKategori)) {
            $decodedKategori = json_decode($decodedKategori, true);
        }
        $news->kategori = $decodedKategori;
        $news->tanggal_terbit = $news->created_at->format('d M Y');

        // Process attachments
        if (is_string($news->gambar_lampiran)) {
            $news->gambar_lampiran = json_decode($news->gambar_lampiran, true);
        }
        if (is_string($news->gambar_lampiran_keterangan)) {
            $news->gambar_lampiran_keterangan = json_decode($news->gambar_lampiran_keterangan, true);
        }

        // Get related news based on categories
        $relatedNews = News::where(function ($query) use ($decodedKategori) {
                if (!empty($decodedKategori)) {
                    $query->where(function ($q) use ($decodedKategori) {
                        foreach ($decodedKategori as $kategori) {
                            $q->orWhere('kategori', 'like', '%' . $kategori . '%');
                        }
                    });
                }
            })
            ->where('id', '!=', $id)
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($related) {
                $related->kategori = json_decode($related->kategori, true);
                $related->tanggal_terbit = $related->created_at->format('d M Y');
                $related->gambar_lampiran = is_string($related->gambar_lampiran) 
                    ? json_decode($related->gambar_lampiran, true) 
                    : $related->gambar_lampiran;
                return $related;
            });

        // Get categories with count
        $categoriesWithCount = Cache::remember('public_categories_count', 3600, function () {
            return News::all()
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
        });

        return Inertia::render('Berita/Detail', [
            'news' => $news,
            'related' => $relatedNews,
            'categoriesWithCount' => $categoriesWithCount,
        ]);
    }
}