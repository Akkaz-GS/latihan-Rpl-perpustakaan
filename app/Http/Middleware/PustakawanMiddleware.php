<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;

class PustakawanMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->isStaff()) {
            abort(403, 'Akses ditolak. Hanya Pustakawan atau Admin yang diizinkan.');
        }
        return $next($request);
    }
}