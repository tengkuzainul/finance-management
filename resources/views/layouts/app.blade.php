<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - Kebab Ikhwan</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body class="bg-slate-50 font-sans antialiased">
    <div id="app" class="min-h-screen flex">
        <!-- Sidebar -->
        @include('layouts.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 lg:ml-64">
            <!-- Top Navigation -->
            @include('layouts.partials.navbar')

            <!-- Page Content -->
            <main class="p-4 md:p-6 lg:p-8 mt-16">
                <!-- Page Header -->
                @hasSection('page-header')
                    <div class="mb-6">
                        @yield('page-header')
                    </div>
                @endif

                <!-- Main Content Area -->
                @yield('content')
            </main>

            <!-- Footer -->
            @include('layouts.partials.footer')
        </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-30 lg:hidden hidden" onclick="toggleSidebar()"></div>

    @stack('scripts')

    <script>
        // Sidebar Toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        // Dropdown Toggle
        function toggleDropdown(id) {
            const dropdown = document.getElementById(id);
            const icon = document.getElementById(id + '-icon');

            dropdown.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            const profileDropdown = document.getElementById('profile-dropdown');
            const profileButton = document.getElementById('profile-button');

            if (profileDropdown && !profileDropdown.contains(event.target) && !profileButton.contains(event
                    .target)) {
                profileDropdown.classList.add('hidden');
            }
        });

        // SweetAlert2 Session Notifications
        document.addEventListener('DOMContentLoaded', function() {
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

            @if (session('warning'))
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian!',
                    text: '{{ session('warning') }}',
                    confirmButtonColor: '#f97316'
                });
            @endif

            @if (session('info'))
                Swal.fire({
                    icon: 'info',
                    title: 'Informasi',
                    text: '{{ session('info') }}',
                    confirmButtonColor: '#f97316'
                });
            @endif
        });

        // SweetAlert2 Helper Functions
        window.SwalHelper = {
            // Success Alert
            success: function(message, title = 'Berhasil!') {
                return Swal.fire({
                    icon: 'success',
                    title: title,
                    text: message,
                    confirmButtonColor: '#f97316',
                    timer: 3000,
                    timerProgressBar: true
                });
            },

            // Error Alert
            error: function(message, title = 'Oops!') {
                return Swal.fire({
                    icon: 'error',
                    title: title,
                    text: message,
                    confirmButtonColor: '#f97316'
                });
            },

            // Warning Alert
            warning: function(message, title = 'Perhatian!') {
                return Swal.fire({
                    icon: 'warning',
                    title: title,
                    text: message,
                    confirmButtonColor: '#f97316'
                });
            },

            // Info Alert
            info: function(message, title = 'Informasi') {
                return Swal.fire({
                    icon: 'info',
                    title: title,
                    text: message,
                    confirmButtonColor: '#f97316'
                });
            },

            // Confirm Dialog
            confirm: function(message, title = 'Apakah Anda yakin?', confirmText = 'Ya, Lanjutkan', cancelText =
                'Batal') {
                return Swal.fire({
                    icon: 'question',
                    title: title,
                    text: message,
                    showCancelButton: true,
                    confirmButtonColor: '#f97316',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: confirmText,
                    cancelButtonText: cancelText,
                    reverseButtons: true
                });
            },

            // Delete Confirm Dialog
            confirmDelete: function(itemName = 'data ini') {
                return Swal.fire({
                    icon: 'warning',
                    title: 'Hapus Data?',
                    text: `Apakah Anda yakin ingin menghapus ${itemName}? Data yang dihapus tidak dapat dikembalikan.`,
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                });
            },

            // Loading
            loading: function(message = 'Memproses...') {
                return Swal.fire({
                    title: message,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },

            // Close Loading
            closeLoading: function() {
                Swal.close();
            },

            // Toast Notification
            toast: function(message, icon = 'success') {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });

                return Toast.fire({
                    icon: icon,
                    title: message
                });
            }
        };

        // Form Submission with SweetAlert2 Confirmation
        function submitFormWithConfirm(formId, message = 'Apakah Anda yakin ingin menyimpan data ini?') {
            SwalHelper.confirm(message).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }

        // Delete Form with Confirmation
        function deleteWithConfirm(formId, itemName = 'data ini') {
            SwalHelper.confirmDelete(itemName).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }

        // AJAX Form Handler with SweetAlert2
        async function ajaxFormSubmit(formElement, options = {}) {
            const defaults = {
                loadingMessage: 'Memproses...',
                successMessage: 'Data berhasil disimpan!',
                errorMessage: 'Terjadi kesalahan. Silakan coba lagi.',
                redirectUrl: null,
                resetForm: false
            };

            const settings = {
                ...defaults,
                ...options
            };
            const formData = new FormData(formElement);

            SwalHelper.loading(settings.loadingMessage);

            try {
                const response = await fetch(formElement.action, {
                    method: formElement.method || 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const data = await response.json();
                SwalHelper.closeLoading();

                if (data.success) {
                    await SwalHelper.success(data.message || settings.successMessage);

                    if (settings.resetForm) {
                        formElement.reset();
                    }

                    if (data.redirect || settings.redirectUrl) {
                        window.location.href = data.redirect || settings.redirectUrl;
                    }
                } else {
                    let errorMsg = data.message || settings.errorMessage;
                    if (data.errors) {
                        const firstError = Object.values(data.errors)[0];
                        if (Array.isArray(firstError)) {
                            errorMsg = firstError[0];
                        }
                    }
                    SwalHelper.error(errorMsg);
                }

                return data;
            } catch (error) {
                SwalHelper.closeLoading();
                SwalHelper.error(settings.errorMessage);
                console.error('Form submission error:', error);
                return {
                    success: false,
                    error: error
                };
            }
        }
    </script>
</body>

</html>
