<?php

namespace App\Http\Middleware;

use App\Models\Member;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMemberRole
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->role !== 'member') {
            abort(403, 'Halaman ini khusus untuk member.');
        }

        $member = $request->user()->member ?? Member::where('email', $request->user()->email)->first();
        if ($member && $member->status !== 'aktif') {
            abort(403, 'Status keanggotaan tidak aktif. Hubungi petugas perpustakaan.');
        }

        return $next($request);
    }
}
