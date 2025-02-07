<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated and has the 'admin' role
        if (auth()->check() && auth()->user()->role !== 'admin') {
            return redirect('/')->with('error', 'Akses ditolak.');
        }

        return $next($request);
    }
}
