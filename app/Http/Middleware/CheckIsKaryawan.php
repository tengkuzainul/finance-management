<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckIsKaryawan
{
   /**
    * Handle an incoming request.
    * Check if authenticated user is a karyawan (non-admin).
    *
    * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
    */
   public function handle(Request $request, Closure $next): Response
   {
      if (!Auth::check()) {
         if ($request->expectsJson()) {
            return response()->json([
               'success' => false,
               'message' => 'Silakan login terlebih dahulu.'
            ], 401);
         }

         return redirect()->route('login');
      }

      // Karyawan adalah user yang bukan admin
      if (Auth::user()->isAdmin()) {
         if ($request->expectsJson()) {
            return response()->json([
               'success' => false,
               'message' => 'Halaman ini khusus untuk karyawan.'
            ], 403);
         }

         return redirect()->route('dashboard')->with([
            'error' => 'Halaman ini khusus untuk karyawan.',
            'alert_type' => 'warning'
         ]);
      }

      return $next($request);
   }
}
