<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class PageController extends Controller
{
    public function Beranda()
    {
        return Inertia::render('Beranda/Beranda');
    }

    public function Kecamatan()
    {
        return Inertia::render('Profil/Kecamatan');
    }

    public function LambangDaerah()
    {
        return Inertia::render('Profil/LambangDaerah');
    }

    public function OPD()
    {
        return Inertia::render('Profil/OPD');
    }

    public function PejabatDaerah()
    {
        return Inertia::render('Profil/PejabatDaerah');
    }

    public function Sejarah()
    {
        return Inertia::render('Profil/Sejarah');
    }

    public function VisiMisi()
    {
        return Inertia::render('Profil/VisiMisi');
    }

    public function BeritaPemerintah()
    {
        return Inertia::render('Berita/BeritaPemerintah');
    }

    public function BeritaDaerah()
    {
        return Inertia::render('Berita/BeritaDaerah');
    }

    public function DataStatistik()
    {
        return Inertia::render('InformasiPublik/DataStatistik');
    }

    public function LapKeu2020()
    {
        return Inertia::render('InformasiPublik/LapKeu2020');
    }

    public function LapKeu2021()
    {
        return Inertia::render('InformasiPublik/LapKeu2021');
    }

    public function LapKeu2022()
    {
        return Inertia::render('InformasiPublik/LapKeu2022');
    }

    public function LapKeu2023()
    {
        return Inertia::render('InformasiPublik/LapKeu2023');
    }

    public function LapKeu2024()
    {
        return Inertia::render('InformasiPublik/LapKeu2024');
    }

    public function PeraturanBupati()
    {
        return Inertia::render('InformasiPublik/PeraturanBupati');
    }

    public function PPID()
    {
        return Inertia::render('PPID/PPID');
    }

    public function Kontak()
    {
        return Inertia::render('Kontak/Kontak');
    }
}
