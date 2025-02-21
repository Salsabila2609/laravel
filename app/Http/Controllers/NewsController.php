<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Support\Str;


class NewsController extends Controller
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
        $totalNewsCount = News::count();
    
        return inertia('Admin/News/News', [
            'newsItems' => $news,
            'categoriesWithCount' => $categoriesWithCount,
            'totalNewsCount' => $totalNewsCount,
        ]);
    }    
    public function showByCategory($kategori)
    {
        if (auth()->check()) {
            $kategori = urldecode($kategori); 
            \Log::info('Kategori yang diterima:', ['kategori' => $kategori]);
            $news = News::whereJsonContains('kategori', $kategori)->get()->map(function ($item) {
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
    
            $totalNewsCount = News::count();
    
            return inertia('Admin/News/News', [
                'newsItems' => $news,
                'categoriesWithCount' => $categoriesWithCount,
                'totalNewsCount' => $totalNewsCount,
                'activeCategory' => $kategori, 
            ]);
        } else {
            return redirect()->route('login');
        }
    }    
    public function create()
    {
        return Inertia::render('Admin/News/Create');
    }
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $image = $request->file('image');
        $imagePath = $image->store('images', 'public'); 

        return response()->json([
            'url' => asset('storage/' . $imagePath), 
        ]);
        
    }
    public function store(Request $request)
    {
        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.Allowed', 'p, b, i, u, a[href|target], iframe[src|width|height|frameborder|allowfullscreen], img[src|alt|title|width|height]');
        
        $config->set('HTML.Trusted', true);
        
        $purifier = new HTMLPurifier($config);
        
        $cleanContent = $purifier->purify($request->input('isi_berita'));
        
        $request->validate([
            'penulis' => 'required|string',
            'judul' => 'required|string',
            'isi_berita' => 'required|string',
            'gambar_utama' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'gambar_lampiran.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'gambar_utama_keterangan' => 'nullable|string|max:255',
            'gambar_lampiran_keterangan.*' => 'nullable|string|max:255',
        ]);
        
        $kategori = is_array($request->kategori) ? $request->kategori : json_decode($request->kategori, true);
        
        $news = new News();
        $news->uuid = Str::uuid(); 
        $news->slug = Str::slug($request->judul) . '-' . substr($news->uuid, 0, 8); // Slug unik
        $news->penulis = $request->penulis;
        $news->judul = $request->judul;
        $news->isi_berita = $cleanContent;
        $news->kategori = json_encode($kategori);
        
        if ($request->hasFile('gambar_utama') && $request->file('gambar_utama')->isValid()) {
            $path = $request->file('gambar_utama')->store('images');
            $news->gambar_utama = $path;
        }
        
        $news->gambar_utama_keterangan = $request->gambar_utama_keterangan;
        
        $gambarLampiranPaths = [];
        $gambarLampiranKeterangan = [];
        
        if ($request->hasFile('gambar_lampiran')) {
            foreach ($request->file('gambar_lampiran') as $index => $lampiran) {
                if ($lampiran->isValid()) {
                    $path = $lampiran->store('images');
                    $gambarLampiranPaths[] = $path;
                    $gambarLampiranKeterangan[] = $request->input('gambar_lampiran_keterangan.' . $index, '');
                }
            }
        }
        
        $isiBerita = $request->input('isi_berita');
        preg_match_all('/<img[^>]+src=["\'](.*?)["\'][^>]*alt=["\'](.*?)["\']/i', $isiBerita, $matches);
    
        $imagePaths = [];
$imageKeterangan = [];

foreach ($matches[1] as $index => $imageUrl) {
    $altText = $matches[2][$index] ?? ''; 

    if (strpos($imageUrl, 'data:image') === false) {
        $relativePath = str_replace(asset('storage') . '/', '', $imageUrl);
        $imagePaths[] = $relativePath;
    } else {
        $imageData = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $imageUrl));
        $imageName = uniqid() . '.png';
        $imagePath = 'images/' . $imageName;
        Storage::put('public/' . $imagePath, $imageData);
        $imagePaths[] = $imagePath;
    }

    $imageKeterangan[] = $altText;
}
    
        $allImagePaths = array_merge($gambarLampiranPaths, $imagePaths);
        $allImageKeterangan = array_merge($gambarLampiranKeterangan, $imageKeterangan);

$news->gambar_lampiran = json_encode($allImagePaths);
$news->gambar_lampiran_keterangan = json_encode($allImageKeterangan);
        $news->save();

        return redirect()->route('news.index')->with('success', 'News created successfully!');
    }
    public function show($slug)
    {

        $news = News::where('slug', $slug)->firstOrFail();
        $news->increment('views');
    
        $decodedKategori = json_decode($news->kategori, true);
        if (is_string($decodedKategori)) {
            $decodedKategori = json_decode($decodedKategori, true);
        }
        $news->kategori = $decodedKategori;
        $news->tanggal_terbit = $news->created_at->format('d M Y');
    
        $news->gambar_lampiran = is_string($news->gambar_lampiran) 
            ? json_decode($news->gambar_lampiran, true) 
            : $news->gambar_lampiran;
    
        $news->gambar_lampiran_keterangan = is_string($news->gambar_lampiran_keterangan) 
            ? json_decode($news->gambar_lampiran_keterangan, true) 
            : $news->gambar_lampiran_keterangan;
    
        $relatedNews = News::where(function ($query) use ($decodedKategori) {
                if (!empty($decodedKategori)) {
                    $query->where(function ($q) use ($decodedKategori) {
                        foreach ($decodedKategori as $kategori) {
                            $q->orWhere('kategori', 'like', '%' . $kategori . '%');
                        }
                    });
                }
            })
            ->where('slug', '!=', $news->slug) // Hindari berita yang sedang ditampilkan
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
    
        $uniqueCategoriesWithCount = News::all()
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
    
        return Inertia::render('Admin/News/DetailNews', [
            'news' => $news,
            'related' => $relatedNews,
            'categoriesWithCount' => $uniqueCategoriesWithCount,
        ]);
    } 
    public function edit($slug)
    {
        $news = News::where('slug', $slug)->firstOrFail();
        
        if (!is_array($news->kategori)) {
            $decodedKategori = json_decode($news->kategori, true);
            if (is_string($decodedKategori)) {
                $decodedKategori = json_decode($decodedKategori, true);
            }
            $news->kategori = $decodedKategori;
        }
        
        $news->gambar_lampiran = is_string($news->gambar_lampiran) ? json_decode($news->gambar_lampiran, true) ?? [] : $news->gambar_lampiran;
        $news->gambar_lampiran_keterangan = is_string($news->gambar_lampiran_keterangan) ? json_decode($news->gambar_lampiran_keterangan, true) ?? [] : $news->gambar_lampiran_keterangan;
    
        return Inertia::render('Admin/News/Edit', [
            'news' => $news
        ]);
    }
    public function update(Request $request, $slug)
{
    $config = HTMLPurifier_Config::createDefault();
    $config->set('HTML.Allowed', 'p, b, i, u, a[href|target], iframe[src|width|height|frameborder|allowfullscreen], img[src|alt|title|width|height]');
    $config->set('HTML.Trusted', true);
    $purifier = new HTMLPurifier($config);
    
    $cleanContent = $purifier->purify($request->input('isi_berita'));

    $request->validate([
        'penulis' => 'required|string',
        'judul' => 'required|string',
        'isi_berita' => 'required',
        'gambar_utama' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'gambar_lampiran.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        'gambar_utama_keterangan' => 'nullable|string|max:255',
        'gambar_lampiran_keterangan.*' => 'nullable|string|max:255',
    ]);

    $news = News::where('slug', $slug)->firstOrFail();
    $news->penulis = $request->penulis;
    $news->judul = $request->judul;
    $news->isi_berita = $cleanContent;

    $news->slug = Str::slug($request->judul);

    $kategori = is_array($request->kategori) ? $request->kategori : json_decode($request->kategori, true);
    $news->kategori = json_encode($kategori);

    if ($request->hasFile('gambar_utama') && $request->file('gambar_utama')->isValid()) {
        if ($news->gambar_utama) {
            Storage::delete($news->gambar_utama);
        }
        $news->gambar_utama = $request->file('gambar_utama')->store('images');
    }
    $news->gambar_utama_keterangan = $request->gambar_utama_keterangan;

    preg_match_all('/<img[^>]+src=["\'](.*?)["\'][^>]*alt=["\'](.*?)["\']/i', $cleanContent, $matches);
    $gambarDariEditor = array_map(fn($url) => str_replace(asset('storage') . '/', '', $url), $matches[1] ?? []);
    
    $existingAttachments = json_decode($news->gambar_lampiran, true) ?? [];
    $existingCaptions = json_decode($news->gambar_lampiran_keterangan, true) ?? [];

    $keptAttachments = $request->input('kept_attachments', []);
    $keptCaptions = $request->input('kept_attachments_keterangan', []);

    $filteredAttachments = [];
    $filteredCaptions = [];

    foreach ($existingAttachments as $index => $path) {
        if (in_array($path, $gambarDariEditor)) {
            $filteredAttachments[] = $path;
            $filteredCaptions[] = $existingCaptions[$index] ?? '';
        } elseif (($key = array_search($path, $keptAttachments)) !== false) {
            $filteredAttachments[] = $path;
            $filteredCaptions[] = $keptCaptions[$key] ?? $existingCaptions[$index] ?? '';
        } else {
            Storage::delete($path);
        }
    }

    if ($request->hasFile('gambar_lampiran')) {
        foreach ($request->file('gambar_lampiran') as $index => $lampiran) {
            if ($lampiran->isValid()) {
                $path = $lampiran->store('images');
                if (!empty($path)) {
                    $filteredAttachments[] = $path;
                    $filteredCaptions[] = $request->input("gambar_lampiran_keterangan.$index", '');
                }
            }
        }
    }

    foreach ($gambarDariEditor as $index => $path) {
        $altText = $matches[2][$index] ?? '';
        if (($key = array_search($path, $filteredAttachments)) !== false) {
            $filteredCaptions[$key] = $altText;
        } else {
            $filteredAttachments[] = $path;
            $filteredCaptions[] = $altText;
        }
    }

    $news->gambar_lampiran = json_encode(array_values($filteredAttachments));
    $news->gambar_lampiran_keterangan = json_encode(array_values($filteredCaptions));

    $news->save();

    return redirect()->route('news.show', $news->slug)->with('success', 'News updated successfully!');
}
    public function destroy($slug)
    {
        try {
            $news = News::where('slug', $slug)->firstOrFail();
            if ($news->gambar_utama) {
                Storage::delete($news->gambar_utama);
            }
            if ($news->gambar_lampiran) {
                $attachments = json_decode($news->gambar_lampiran, true);
                foreach ($attachments as $attachment) {
                    Storage::delete($attachment);
                }
            }

            $news->delete();

            return redirect()->route('news.index')->with('success', 'News deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting news: ' . $e->getMessage());
        }
    }

}
