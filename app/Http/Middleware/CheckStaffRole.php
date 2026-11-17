<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckStaffRole
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! in_array($request->user()?->role, ['admin', 'petugas'], true)) {
            abort(403, 'Halaman ini hanya bisa diakses oleh admin atau petugas.');
        }

        return $next($request);
    }
}
