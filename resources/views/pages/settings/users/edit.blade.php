@extends('layouts.app')

@section('title', 'Edit Pengguna')

@section('page-header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-800">Edit Pengguna</h1>
            <p class="text-slate-500 mt-1">Perbarui data pengguna {{ $user->name }}</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <form id="userForm" action="{{ route('users.update', $user->hash_id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-6">
                    <!-- Nama Pengguna -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">
                            Nama Pengguna <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                            placeholder="Masukkan nama lengkap"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('name') border-red-500 @enderror"
                            required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Username -->
                    <div>
                        <label for="username" class="block text-sm font-semibold text-slate-700 mb-2">
                            Username <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}"
                            placeholder="Masukkan username (tanpa spasi)"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('username') border-red-500 @enderror"
                            required>
                        @error('username')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                            placeholder="Masukkan email"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('email') border-red-500 @enderror"
                            required>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">
                                Password Baru
                            </label>
                            <input type="password" id="password" name="password"
                                placeholder="Kosongkan jika tidak ingin mengubah"
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('password') border-red-500 @enderror">
                            <p class="text-sm text-slate-500 mt-2">Minimal 8 karakter jika ingin mengganti password</p>
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-2">
                                Konfirmasi Password
                            </label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                placeholder="Ulangi password baru"
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('password_confirmation') border-red-500 @enderror">
                            @error('password_confirmation')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Role -->
                    <div>
                        <label for="is_admin" class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" id="is_admin" name="is_admin" value="1"
                                class="w-4 h-4 text-orange-600 border-slate-300 rounded focus:ring-2 focus:ring-orange-500"
                                {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}>
                            <span class="text-sm font-semibold text-slate-700">Jadikan Admin</span>
                        </label>
                        <p class="text-sm text-slate-500 mt-2 ml-7">Admin memiliki akses penuh ke semua fitur sistem</p>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="is_active" class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" id="is_active" name="is_active" value="1"
                                class="w-4 h-4 text-orange-600 border-slate-300 rounded focus:ring-2 focus:ring-orange-500"
                                {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                            <span class="text-sm font-semibold text-slate-700">Aktifkan Pengguna</span>
                        </label>
                        <p class="text-sm text-slate-500 mt-2 ml-7">Pengguna yang tidak aktif tidak dapat login</p>
                    </div>

                    <!-- Info -->
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex gap-3">
                            <i class="fas fa-info-circle text-blue-600 mt-0.5"></i>
                            <div class="text-sm text-blue-700">
                                <p class="font-medium mb-1">Informasi Pengguna:</p>
                                <p>Dibuat: {{ $user->created_at->format('d M Y H:i') }}</p>
                                @if ($user->updated_at && $user->updated_at->ne($user->created_at))
                                    <p>Diperbarui: {{ $user->updated_at->format('d M Y H:i') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a href="{{ route('users.index') }}"
                        class="px-6 py-2 bg-slate-200 text-slate-700 font-medium rounded-lg hover:bg-slate-300 transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-orange-600 text-white font-medium rounded-lg hover:bg-orange-700 transition-colors inline-flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        <span>Simpan Perubahan</span>
                        <i class="fas fa-spinner fa-spin" style="display: none;"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('userForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const form = this;
                const btnSubmit = form.querySelector('button[type="submit"]');
                const spinner = form.querySelector('i.fa-spinner');

                // Disable button
                btnSubmit.disabled = true;
                if (spinner) spinner.style.display = 'inline-block';

                const formData = new FormData(form);

                fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(data => {
                                throw data;
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            SwalHelper.success(data.message || 'Pengguna berhasil diperbarui!');
                            setTimeout(() => {
                                window.location.href = data.redirect;
                            }, 1500);
                        }
                    })
                    .catch(error => {
                        if (error.errors) {
                            let errorMsg = 'Validasi gagal:<br>';
                            Object.keys(error.errors).forEach(field => {
                                errorMsg += '• ' + error.errors[field].join(', ') + '<br>';
                            });
                            SwalHelper.error(errorMsg, 'Validasi Gagal!');
                        } else {
                            SwalHelper.error(error.message || 'Terjadi kesalahan saat menyimpan data');
                        }
                        btnSubmit.disabled = false;
                        if (spinner) spinner.style.display = 'none';
                    });
            });
        </script>
    @endpush
@endsection
