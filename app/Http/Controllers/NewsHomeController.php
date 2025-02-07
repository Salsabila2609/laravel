<?php

namespace App\Http\Controllers;

use App\Models\NewsHome;
use Inertia\Inertia;
use Carbon\Carbon;

class NewsHomeController extends Controller
{
    public function getBerita()
{
    // Ambil berita utama yang paling terbaru (berita pertama)
    $mainNews = NewsHome::orderBy('created_at', 'desc')->first();

    if ($mainNews) {
        // Ubah path gambar utama menjadi URL yang bisa diakses
        $mainNews->gambar_utama = url('storage/' . $mainNews->gambar_utama);

        // Decode dan ubah path gambar lampiran
        $mainNews->gambar_lampiran = collect(json_decode($mainNews->gambar_lampiran, true))->map(function ($lampiran) {
            return url('storage/' . $lampiran);
        });
    }

    // Ambil berita lainnya (6 berita setelah yang pertama)
    $newsCards = NewsHome::orderBy('created_at', 'desc')
        ->skip(1)
        ->take(6)
        ->get()
        ->each(function ($news) {
            // Ubah path gambar utama menjadi URL yang bisa diakses
            $news->gambar_utama = url('storage/' . $news->gambar_utama);

            // Decode dan ubah path gambar lampiran
            $news->gambar_lampiran = collect(json_decode($news->gambar_lampiran, true))->map(function ($lampiran) {
                return url('storage/' . $lampiran);
            });

            // Hapus isi_berita dari objek berita lainnya
            unset($news->isi_berita);
        });

    return [
        'mainNews' => $mainNews,
        'newsCards' => $newsCards,
    ];
}
}