<?php
namespace App\Http\Controllers;

use App\Models\Visitor;
use App\Models\Layanan;
use App\Models\Popup;
use App\Models\Media;
use App\Models\Video;
use Inertia\Inertia;
use Carbon\Carbon;
use App\Http\Controllers\NewsHomeController;
use Illuminate\Support\Facades\Log;

class BerandaController extends Controller
{
    public function index()
    {
        try {
            // Simpan data pengunjung
            Visitor::create([
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            // Hitung jumlah pengunjung
            $visitorCount = Visitor::count();
            $todayVisitorCount = Visitor::whereDate('created_at', Carbon::today())->count();
            $monthlyVisitorCount = Visitor::whereMonth('created_at', Carbon::now()->month)
                                          ->whereYear('created_at', Carbon::now()->year)
                                          ->count();
            $yearlyVisitorCount = Visitor::whereYear('created_at', Carbon::now()->year)->count();

            // Ambil data berita dari NewsController
            $newsHomeController = new NewsHomeController();
            $data = $newsHomeController->getBerita();

            // Ambil semua gambar popup
            $popup = [
                'image_popup' => Popup::pluck('image_popup')->toArray()
            ];   

            // Ambil data media
            $media = Media::latest()->get();

            // Ambil video terbaru dari database
            $latestVideo = Video::latest()->first();
            $videoPath = $latestVideo ? asset('storage/' . $latestVideo->video_path) : null;

                // Kirim data ke halaman Beranda melalui Inertia
                return Inertia::render('Beranda/Beranda', [
                    'layanans' => Layanan::all(), // Kirim data layanan ke Vue
                    'visitorCount' => $visitorCount,
                    'todayVisitorCount' => $todayVisitorCount,
                    'monthlyVisitorCount' => $monthlyVisitorCount,
                    'yearlyVisitorCount' => $yearlyVisitorCount,
                    'mainNews' => $data['mainNews'],
                    'newsCards' => $data['newsCards'],
                    'popup' => $popup, // Data popup
                    'media' => $media, 
                    'latestVideo' => $videoPath, // ✅ Kirim video terbaru ke frontend

                ]);  
                
            } catch (\Exception $e) {
                // Log the error and return a response if an exception occurs
                Log::error('Error in BerandaController: ' . $e->getMessage());
    
                // Optionally, return an error page or a default response
                return Inertia::render('Error', [
                    'message' => 'Something went wrong. Please try again later.',
                ]);
            }
        }
    }
