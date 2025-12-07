<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserActive
{
   /**
    * Handle an incoming request.
    * Check if authenticated user is active, if not, logout and redirect.
    *
    * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
    */
   public function handle(Request $request, Closure $next): Response
   {
      if (Auth::check() && !Auth::user()->isActive()) {
         Auth::logout();
         $request->session()->invalidate();
         $request->session()->regenerateToken();

         if ($request->expectsJson()) {
            return response()->json([
               'success' => false,
               'message' => 'Akun Anda telah dinonaktifkan. Silakan hubungi administrator.'
            ], 403);
         }

         return redirect()->route('login')->with([
            'error' => 'Akun Anda telah dinonaktifkan. Silakan hubungi administrator.',
            'alert_type' => 'error'
         ]);
      }

      return $next($request);
   }
}
