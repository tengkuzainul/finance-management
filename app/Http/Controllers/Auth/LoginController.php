<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ], [
            'login.required' => 'Email atau username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $login = $request->input('login');
        $password = $request->input('password');
        $remember = $request->boolean('remember');

        // Find user by email or username
        $user = User::findByEmailOrUsername($login);

        if (!$user) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email/Username atau password salah.',
                    'errors' => ['login' => ['Email/Username atau password salah.']]
                ], 422);
            }

            return back()
                ->withInput($request->only('login', 'remember'))
                ->withErrors(['login' => 'Email/Username atau password salah.']);
        }

        // Check if user is active
        if (!$user->isActive()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun Anda telah dinonaktifkan. Silakan hubungi administrator.',
                    'errors' => ['login' => ['Akun Anda telah dinonaktifkan.']]
                ], 403);
            }

            return back()
                ->withInput($request->only('login', 'remember'))
                ->withErrors(['login' => 'Akun Anda telah dinonaktifkan. Silakan hubungi administrator.']);
        }

        // Verify password
        if (!Hash::check($password, $user->password)) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email/Username atau password salah.',
                    'errors' => ['login' => ['Email/Username atau password salah.']]
                ], 422);
            }

            return back()
                ->withInput($request->only('login', 'remember'))
                ->withErrors(['login' => 'Email/Username atau password salah.']);
        }

        // Login the user
        Auth::login($user, $remember);
        $request->session()->regenerate();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Login berhasil! Selamat datang, ' . $user->name,
                'redirect' => route('dashboard')
            ]);
        }

        return redirect()->intended(route('dashboard'))
            ->with([
                'success' => 'Login berhasil! Selamat datang, ' . $user->name,
                'alert_type' => 'success'
            ]);
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil!',
                'redirect' => route('login')
            ]);
        }

        return redirect()->route('login')
            ->with([
                'success' => 'Logout berhasil!',
                'alert_type' => 'success'
            ]);
    }
}
