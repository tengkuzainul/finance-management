<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckIsAdmin
{
   /**
    * Handle an incoming request.
    * Check if authenticated user is an admin.
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

      if (!Auth::user()->isAdmin()) {
         if ($request->expectsJson()) {
            return response()->json([
               'success' => false,
               'message' => 'Akses ditolak! Halaman ini hanya dapat diakses oleh Administrator/Owner.'
            ], 403);
         }

         return redirect()->route('dashboard')->with('error', 'Akses ditolak! Halaman ini hanya dapat diakses oleh Administrator/Owner.');
      }

      return $next($request);
   }
}
