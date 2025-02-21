<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use Illuminate\Http\Request;

class SejarahController extends Controller
{
    public function index()
    {
        return Inertia::render('Profil/SejarahDaerah');
    }
}
