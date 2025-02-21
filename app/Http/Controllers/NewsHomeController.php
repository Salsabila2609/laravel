<?php

namespace App\Http\Controllers;

use App\Models\NewsHome;
use Inertia\Inertia;
use Carbon\Carbon;

class NewsHomeController extends Controller
{
    public function getBerita()
{
     $mainNews = NewsHome::orderBy('created_at', 'desc')->first();

    if ($mainNews) {
        $mainNews->gambar_utama = url('storage/' . $mainNews->gambar_utama);
        $mainNews->gambar_lampiran = collect(json_decode($mainNews->gambar_lampiran, true))->map(function ($lampiran) {
            return url('storage/' . $lampiran);
        });
    }

   $newsCards = NewsHome::orderBy('created_at', 'desc')
        ->skip(1)
        ->take(6)
        ->get()
        ->each(function ($news) {
            $news->gambar_utama = url('storage/' . $news->gambar_utama);
 $news->gambar_lampiran = collect(json_decode($news->gambar_lampiran, true))->map(function ($lampiran) {
                return url('storage/' . $lampiran);
            });

            unset($news->isi_berita);
        });

    return [
        'mainNews' => $mainNews,
        'newsCards' => $newsCards,
    ];
}
}