@extends('layouts.guest')

@section('title', 'Login - Kebab Ikhwan')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-linear-to-br from-slate-100 via-slate-50 to-orange-50 p-4">
        <!-- Login Card -->
        <div class="w-full max-w-md">
            <!-- Logo & Header -->
            <div class="text-center mb-8">
                <div
                    class="w-24 h-24 bg-linear-to-br from-slate-500 to-slate-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-xl shadow-slate-700/30">
                    <img src="{{ URL::asset('logo.png') }}" alt="{{ config('app.name') }}" class="w-20 h-20 object-contain">
                </div>
                <h1 class="text-2xl font-bold text-slate-800">Kebab Ikhwan</h1>
                <p class="text-slate-500 text-sm mt-1">Sistem Manajemen Keuangan</p>
            </div>

            <!-- Login Card -->
            <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                <!-- Card Header -->
                <div class="px-8 pt-8 pb-4">
                    <h2 class="text-xl font-bold text-slate-800 text-center">Selamat Datang! 👋</h2>
                    <p class="text-slate-500 text-sm text-center mt-1">Silakan login untuk melanjutkan</p>
                </div>

                <!-- Login Form -->
                <form id="loginForm" method="POST" action="{{ route('login') }}" class="px-8 pb-8 space-y-5">
                    @csrf

                    <!-- Email/Username Field -->
                    <div>
                        <label for="login" class="block text-sm font-medium text-slate-700 mb-2">Email atau
                            Username</label>
                        <div class="relative">
                            <input type="text" id="login" name="login" value="{{ old('login') }}"
                                class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 focus:bg-white transition-all duration-200 @error('login') border-red-500 ring-2 ring-red-500/20 @enderror"
                                placeholder="email@example.com atau username" required autofocus>
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                <i class="fas fa-user text-sm"></i>
                            </div>
                        </div>
                        @error('login')
                            <p class="mt-2 text-sm text-red-500 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle text-xs"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-2">Password</label>
                        <div class="relative">
                            <input type="password" id="password" name="password"
                                class="w-full pl-11 pr-11 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 focus:bg-white transition-all duration-200 @error('password') border-red-500 ring-2 ring-red-500/20 @enderror"
                                placeholder="••••••••" required>
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                <i class="fas fa-lock text-sm"></i>
                            </div>
                            <button type="button" onclick="togglePassword()"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                                <i id="password-icon" class="fas fa-eye text-sm"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-500 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle text-xs"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember" id="remember"
                                class="w-4 h-4 text-orange-500 bg-slate-50 border-slate-300 rounded focus:ring-2 focus:ring-orange-500/20">
                            <span class="text-sm text-slate-600">Ingat saya</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                                class="text-sm text-orange-500 hover:text-orange-600 font-medium transition-colors">
                                Lupa password?
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="loginBtn"
                        class="w-full py-3 bg-linear-to-r from-orange-500 to-orange-600 text-white font-semibold rounded-xl hover:from-orange-600 hover:to-orange-700 shadow-lg shadow-orange-500/30 transition-all duration-200 flex items-center justify-center gap-2">
                        <span id="loginBtnText">Masuk</span>
                        <i id="loginBtnIcon" class="fas fa-arrow-right text-sm"></i>
                        <i id="loginSpinner" class="fas fa-spinner fa-spin text-sm" style="display: none;"></i>
                    </button>
                </form>
            </div>

            <!-- Footer -->
            <p class="text-center mt-6 text-xs text-slate-400">
                © {{ date('Y') }} Kebab Ikhwan. All rights reserved.
            </p>
        </div>
    </div>

    @push('scripts')
        <script>
            function togglePassword() {
                const passwordInput = document.getElementById('password');
                const passwordIcon = document.getElementById('password-icon');

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    passwordIcon.classList.remove('fa-eye');
                    passwordIcon.classList.add('fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    passwordIcon.classList.remove('fa-eye-slash');
                    passwordIcon.classList.add('fa-eye');
                }
            }

            // SweetAlert2 Form Submission
            document.addEventListener('DOMContentLoaded', function() {
                const loginForm = document.getElementById('loginForm');
                const loginBtn = document.getElementById('loginBtn');
                const loginBtnText = document.getElementById('loginBtnText');
                const loginBtnIcon = document.getElementById('loginBtnIcon');
                const loginSpinner = document.getElementById('loginSpinner');

                // Check for session alerts
                @if (session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: '{{ session('success') }}',
                        confirmButtonColor: '#f97316',
                        timer: 3000,
                        timerProgressBar: true
                    });
                @endif

                @if (session('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops!',
                        text: '{{ session('error') }}',
                        confirmButtonColor: '#f97316'
                    });
                @endif

                loginForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const login = document.getElementById('login').value.trim();
                    const password = document.getElementById('password').value;

                    // Validation
                    if (!login) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Perhatian!',
                            text: 'Email atau username wajib diisi.',
                            confirmButtonColor: '#f97316'
                        });
                        document.getElementById('login').focus();
                        return;
                    }

                    if (!password) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Perhatian!',
                            text: 'Password wajib diisi.',
                            confirmButtonColor: '#f97316'
                        });
                        document.getElementById('password').focus();
                        return;
                    }

                    // Show loading state
                    loginBtn.disabled = true;
                    loginBtnText.textContent = 'Memproses...';
                    loginBtnIcon.style.display = 'none';
                    loginSpinner.style.display = 'inline-block';

                    // AJAX Request
                    fetch('{{ route('login') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                login: login,
                                password: password,
                                remember: document.getElementById('remember').checked
                            })
                        })
                        .then(response => response.json().then(data => ({
                            status: response.status,
                            data
                        })))
                        .then(({
                            status,
                            data
                        }) => {
                            // Reset button state
                            loginBtn.disabled = false;
                            loginBtnText.textContent = 'Masuk';
                            loginBtnIcon.style.display = 'inline-block';
                            loginSpinner.style.display = 'none';

                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Login Berhasil!',
                                    text: data.message,
                                    confirmButtonColor: '#f97316',
                                    timer: 2000,
                                    timerProgressBar: true,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.href = data.redirect ||
                                        '{{ route('dashboard') }}';
                                });
                            } else {
                                let errorMessage = data.message || 'Terjadi kesalahan. Silakan coba lagi.';

                                if (data.errors && data.errors.login) {
                                    errorMessage = data.errors.login[0];
                                }

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Login Gagal!',
                                    text: errorMessage,
                                    confirmButtonColor: '#f97316'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            // Reset button state
                            loginBtn.disabled = false;
                            loginBtnText.textContent = 'Masuk';
                            loginBtnIcon.style.display = 'inline-block';
                            loginSpinner.style.display = 'none';

                            Swal.fire({
                                icon: 'error',
                                title: 'Oops!',
                                text: 'Terjadi kesalahan koneksi. Silakan coba lagi.',
                                confirmButtonColor: '#f97316'
                            });
                        });
                });
            });
        </script>
    @endpush
@endsection
