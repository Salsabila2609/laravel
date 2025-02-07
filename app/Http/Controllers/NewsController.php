<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use HTMLPurifier;
use HTMLPurifier_Config;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::all()->map(function ($item) {
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
    
        return inertia('Admin/News/Index', [
            'newsItems' => $news,
            'categoriesWithCount' => $categoriesWithCount,
            'totalNewsCount' => $totalNewsCount,
        ]);
    }    

    public function showByCategory($kategori)
    {
        // Cek apakah pengguna sudah login
        if (auth()->check()) {
            // Decode kategori jika diperlukan
            $kategori = urldecode($kategori); // Decode URL-encoded string
    
            // Debug: Log kategori yang diterima
            \Log::info('Kategori yang diterima:', ['kategori' => $kategori]);
    
            // Ambil berita berdasarkan kategori
            $news = News::whereJsonContains('kategori', $kategori)->get()->map(function ($item) {
                // Proses kategori jika diperlukan (misalnya kategori disimpan dalam format JSON)
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
    
            // Debug: Log berita yang ditemukan
            \Log::info('Berita yang ditemukan:', ['news' => $news]);
    
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
    
            return inertia('News/Index', [
                'newsItems' => $news,
                'categoriesWithCount' => $categoriesWithCount,
                'totalNewsCount' => $totalNewsCount,
                'activeCategory' => $kategori, // Tambahkan kategori aktif
            ]);
        } else {
            // Jika pengguna tidak login, redirect ke halaman login
            return redirect()->route('login');
        }
    }    

    public function create()
    {
        return Inertia::render('Admin/News/Create');
    }

    public function uploadImage(Request $request)
    {
        // Validasi gambar
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Simpan gambar ke storage
        $image = $request->file('image');
        $imagePath = $image->store('images', 'public'); // Simpan ke storage/app/public/images

        // Kembalikan URL gambar yang disimpan
        return response()->json([
            'url' => asset('storage/' . $imagePath), // URL yang dinamis sesuai dengan domain aplikasi
        ]);
        
    }

    public function store(Request $request)
    {
     
        $config = HTMLPurifier_Config::createDefault();

        // Mengizinkan tag iframe dan img dengan atribut src, width, height, frameborder, allowfullscreen, alt, title
        $config->set('HTML.Allowed', 'p, b, i, u, a[href|target], iframe[src|width|height|frameborder|allowfullscreen], img[src|alt|title|width|height]');
        
        // Mengaktifkan konfigurasi HTML.Trusted untuk memperbolehkan atribut tambahan
        $config->set('HTML.Trusted', true);
        
        // Membuat objek HTMLPurifier dengan konfigurasi
        $purifier = new HTMLPurifier($config);
        
        // Memurnikan konten yang dimasukkan
        $cleanContent = $purifier->purify($request->input('isi_berita'));
        
        // Validasi input form
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
        $news->penulis = $request->penulis;
        $news->judul = $request->judul;
        $news->isi_berita = $cleanContent;
        $news->kategori = json_encode($kategori);
    
        // Proses upload gambar utama
        if ($request->hasFile('gambar_utama') && $request->file('gambar_utama')->isValid()) {
            $path = $request->file('gambar_utama')->store('images');
            $news->gambar_utama = $path;
        }
    
        $news->gambar_utama_keterangan = $request->gambar_utama_keterangan;
    
        // Proses upload multiple gambar lampiran
        $gambarLampiranPaths = [];
        $gambarLampiranKeterangan = [];
        if ($request->hasFile('gambar_lampiran')) {
            foreach ($request->file('gambar_lampiran') as $index => $lampiran) {
                if ($lampiran->isValid()) {
                    $path = $lampiran->store('images');
                    $gambarLampiranPaths[] = $path;
                    $gambarLampiranKeterangan[] = $request->input('gambar_lampiran_keterangan.' . $index);
                }
            }
        }
    
        // Ekstraksi gambar dari isi berita
        $isiBerita = $request->input('isi_berita');
        preg_match_all('/<img[^>]+src=["\'](.*?)["\']/i', $isiBerita, $matches);
        $imagePaths = [];
    
        foreach ($matches[1] as $imageUrl) {
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
        }
    
        $allImagePaths = array_merge($gambarLampiranPaths, $imagePaths);
    
        $news->gambar_lampiran = json_encode($allImagePaths);
        $news->gambar_lampiran_keterangan = json_encode($gambarLampiranKeterangan);
    
        $news->save();
    
        return redirect()->route('news.index')->with('success', 'News created successfully!');
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
        return Inertia::render('Admin/News/Show', [
            'news' => $news,
            'related' => $relatedNews,
            'categoriesWithCount' => $uniqueCategoriesWithCount,
        ]);
    }
    
    public function edit($id)
    {
        $news = News::findOrFail($id);
        
        if (!is_array($news->kategori)) {
            $decodedKategori = json_decode($news->kategori, true);
            if (is_string($decodedKategori)) {
                $decodedKategori = json_decode($decodedKategori, true);
            }
            $news->kategori = $decodedKategori;
        }
        
        // Decode gambar lampiran and keterangan if they're stored as JSON strings
        if (is_string($news->gambar_lampiran)) {
            $news->gambar_lampiran = json_decode($news->gambar_lampiran, true) ?? [];
        }
        if (is_string($news->gambar_lampiran_keterangan)) {
            $news->gambar_lampiran_keterangan = json_decode($news->gambar_lampiran_keterangan, true) ?? [];
        }
    
        return Inertia::render('Admin/News/Edit', [
            'news' => $news
        ]);
    }
    
    public function update(Request $request, $id)
    {
        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.Allowed', 'p, b, i, u, a[href|target], iframe[src|width|height|frameborder|allowfullscreen], img[src|alt|title|width|height]');
        $config->set('HTML.Trusted', true);
        $purifier = new HTMLPurifier($config);
        
        // Purify the news content
        $cleanContent = $purifier->purify($request->input('isi_berita'));
    
        // Validate input
        $request->validate([
            'penulis' => 'required|string',
            'judul' => 'required|string',
            'isi_berita' => 'required',
            'gambar_utama' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'gambar_lampiran.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'gambar_utama_keterangan' => 'nullable|string|max:255',
            'gambar_lampiran_keterangan.*' => 'nullable|string|max:255',
        ]);
    
        $news = News::findOrFail($id);
        
        $news->penulis = $request->penulis;
        $news->judul = $request->judul;
        $news->isi_berita = $cleanContent;
        
        $kategori = is_array($request->kategori) ? $request->kategori : json_decode($request->kategori, true);
        $news->kategori = json_encode($kategori);
    
        // Handle main image update
        if ($request->hasFile('gambar_utama') && $request->file('gambar_utama')->isValid()) {
            if ($news->gambar_utama) {
                Storage::delete($news->gambar_utama);
            }
            $news->gambar_utama = $request->file('gambar_utama')->store('images');
        }
        
        $news->gambar_utama_keterangan = $request->gambar_utama_keterangan;
    
        // Handle attachment images
        $existingAttachments = json_decode($news->gambar_lampiran, true) ?? [];
        $existingCaptions = json_decode($news->gambar_lampiran_keterangan, true) ?? [];
    
        // Handle deleted attachments
        $keptAttachments = $request->input('kept_attachments', []);
        $filteredAttachments = [];
        $filteredCaptions = [];
    
        foreach ($existingAttachments as $index => $path) {
            if (in_array($path, $keptAttachments)) {
                $filteredAttachments[] = $path;
                $filteredCaptions[] = $existingCaptions[$index] ?? '';
            } else {
                Storage::delete($path);
            }
        }
    
        // Add new attachments
        if ($request->hasFile('gambar_lampiran')) {
            foreach ($request->file('gambar_lampiran') as $index => $lampiran) {
                if ($lampiran->isValid()) {
                    $path = $lampiran->store('images');
                    $filteredAttachments[] = $path;
                    $filteredCaptions[] = $request->input('gambar_lampiran_keterangan.' . $index);
                }
            }
        }
    
        // Extract images from isi_berita
        preg_match_all('/<img[^>]+src=["\'](.*?)["\']/i', $cleanContent, $matches);
        foreach ($matches[1] as $imageUrl) {
            if (strpos($imageUrl, 'data:image') === false) {
                $relativePath = str_replace(asset('storage') . '/', '', $imageUrl);
                if (!in_array($relativePath, $filteredAttachments)) {
                    $filteredAttachments[] = $relativePath;
                }
            } else {
                $imageData = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $imageUrl));
                $imageName = uniqid() . '.png';
                $imagePath = 'images/' . $imageName;
                Storage::put('public/' . $imagePath, $imageData);
                $filteredAttachments[] = $imagePath;
            }
        }
    
        $news->gambar_lampiran = json_encode(array_values($filteredAttachments));
        $news->gambar_lampiran_keterangan = json_encode(array_values($filteredCaptions));
    
        $news->save();
    
        return redirect()->route('news.show', $news->id)->with('success', 'News updated successfully!');
    }

    public function destroy($id)
    {
        try {
            // Find the news
            $news = News::findOrFail($id);

            // Delete the main image if exists
            if ($news->gambar_utama) {
                Storage::delete($news->gambar_utama);
            }

            // Delete attachment images if exist
            if ($news->gambar_lampiran) {
                $attachments = json_decode($news->gambar_lampiran, true);
                foreach ($attachments as $attachment) {
                    Storage::delete($attachment);
                }
            }

            // Delete the news record
            $news->delete();

            return redirect()->route('news.index')->with('success', 'News deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting news: ' . $e->getMessage());
        }
    }
}
