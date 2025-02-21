<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use Illuminate\Http\Request;

class LambangController extends Controller
{
    public function index()
    {
        return Inertia::render('Profil/LambangDaerah');
    }
}
