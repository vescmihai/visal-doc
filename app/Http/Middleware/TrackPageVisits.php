<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\PageVisit;

class TrackPageVisits
{
    public function handle(Request $request, Closure $next)
    {
        // Registra la URL actual
        $pageUrl = $request->path();

        // Encuentra o crea un registro para esta página
        $pageVisit = PageVisit::firstOrCreate(
            ['page_url' => $pageUrl],
            ['visits' => 0]
        );

        // Incrementa el contador de visitas
        $pageVisit->increment('visits');

        return $next($request);
    }
}
