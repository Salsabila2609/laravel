<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Pejabat;
use App\Models\OPD;
use App\Models\Kecamatan;
use App\Models\Popup;
use App\Models\Document;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            [
                'label' => 'Total News', 
                'value' => News::count(), 
                'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v12a2 2 0 01-2 2z'
            ], 
            [
                'label' => 'Local Officials', 
                'value' => Pejabat::count(), 
                'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'
            ], 
            [
                'label' => 'Total LGO', 
                'value' => OPD::count(), 
                'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'
            ], 
            [
                'label' => 'Districts', 
                'value' => Kecamatan::count(), 
                'icon' => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064'
            ],
            [
                'label' => 'Total Popups',
                'value' => Popup::count(),
                'icon' => 'M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9'
            ],
            [
                'label' => 'Total Documents',
                'value' => Document::count(),
                'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'
            ]
        ];

        return Inertia::render('Dashboard', [
            'stats' => $stats
        ]);
    }
}