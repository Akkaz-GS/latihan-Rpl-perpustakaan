<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;

class AnggotaVerifikasiMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        if (!$user || !$user->isAnggota()) {
            abort(403, 'Hanya anggota yang dapat mengakses halaman ini.');
        }
        if (!$user->is_verified) {
            return redirect()->route('dashboard')
                ->with('warning', 'Akun Anda belum diverifikasi oleh pustakawan.');
        }
        return $next($request);
    }
}