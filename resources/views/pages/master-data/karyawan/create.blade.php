@extends('layouts.app')

@section('title', 'Tambah Karyawan')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center gap-4">
            <a href="{{ route('master-data.karyawan.index') }}"
                class="w-10 h-10 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-600 hover:bg-slate-50 transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Tambah Karyawan Baru</h1>
                <p class="text-gray-600 mt-1">Tambahkan data karyawan baru</p>
            </div>
        </div>

        <!-- Form Card -->
        <form id="formKaryawan" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Form -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Data Diri -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h2 class="font-semibold text-gray-900">Data Diri</h2>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- NIK -->
                                <div>
                                    <label for="nik" class="block text-sm font-medium text-gray-700 mb-2">
                                        NIK Karyawan <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="nik" id="nik" value="{{ $nik }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-orange focus:border-brand-orange"
                                        required>
                                </div>

                                <!-- Nama Lengkap -->
                                <div>
                                    <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nama Lengkap <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="nama_lengkap" id="nama_lengkap"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-orange focus:border-brand-orange"
                                        placeholder="Masukkan nama lengkap" required>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Tempat Lahir -->
                                <div>
                                    <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tempat Lahir
                                    </label>
                                    <input type="text" name="tempat_lahir" id="tempat_lahir"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-orange focus:border-brand-orange"
                                        placeholder="Contoh: Jakarta">
                                </div>

                                <!-- Tanggal Lahir -->
                                <div>
                                    <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tanggal Lahir
                                    </label>
                                    <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-orange focus:border-brand-orange">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Jenis Kelamin -->
                                <div>
                                    <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-2">
                                        Jenis Kelamin <span class="text-red-500">*</span>
                                    </label>
                                    <select name="jenis_kelamin" id="jenis_kelamin"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-orange focus:border-brand-orange"
                                        required>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                </div>

                                <!-- Agama -->
                                <div>
                                    <label for="agama" class="block text-sm font-medium text-gray-700 mb-2">
                                        Agama
                                    </label>
                                    <select name="agama" id="agama"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-orange focus:border-brand-orange">
                                        <option value="">Pilih Agama</option>
                                        <option value="Islam">Islam</option>
                                        <option value="Kristen">Kristen</option>
                                        <option value="Katolik">Katolik</option>
                                        <option value="Hindu">Hindu</option>
                                        <option value="Buddha">Buddha</option>
                                        <option value="Konghucu">Konghucu</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Status Pernikahan -->
                                <div>
                                    <label for="status_pernikahan" class="block text-sm font-medium text-gray-700 mb-2">
                                        Status Pernikahan
                                    </label>
                                    <select name="status_pernikahan" id="status_pernikahan"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-orange focus:border-brand-orange">
                                        <option value="">Pilih Status</option>
                                        <option value="Belum Menikah">Belum Menikah</option>
                                        <option value="Menikah">Menikah</option>
                                        <option value="Duda">Duda</option>
                                        <option value="Janda">Janda</option>
                                    </select>
                                </div>

                                <!-- No Telepon -->
                                <div>
                                    <label for="no_telepon" class="block text-sm font-medium text-gray-700 mb-2">
                                        No. Telepon
                                    </label>
                                    <input type="tel" name="no_telepon" id="no_telepon"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-orange focus:border-brand-orange"
                                        placeholder="Contoh: 08123456789">
                                </div>
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email
                                </label>
                                <input type="email" name="email" id="email"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-orange focus:border-brand-orange"
                                    placeholder="Contoh: karyawan@email.com">
                            </div>

                            <div>
                                <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">
                                    Alamat
                                </label>
                                <textarea name="alamat" id="alamat" rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-orange focus:border-brand-orange resize-none"
                                    placeholder="Masukkan alamat lengkap..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Data Pekerjaan -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h2 class="font-semibold text-gray-900">Data Pekerjaan</h2>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Cabang -->
                                <div>
                                    <label for="cabang_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Cabang <span class="text-red-500">*</span>
                                    </label>
                                    <select name="cabang_id" id="cabang_id"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-orange focus:border-brand-orange"
                                        required>
                                        <option value="">Pilih Cabang</option>
                                        @foreach ($cabangs as $cabang)
                                            <option value="{{ $cabang->hash_id }}"
                                                {{ isset($selectedCabangId) && $selectedCabangId == $cabang->id ? 'selected' : '' }}>
                                                {{ $cabang->nama_cabang }} ({{ $cabang->kode_cabang }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Tanggal Masuk -->
                                <div>
                                    <label for="tanggal_masuk" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tanggal Masuk <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="tanggal_masuk" id="tanggal_masuk"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-orange focus:border-brand-orange"
                                        required>
                                </div>
                            </div>

                            <!-- Status Aktif -->
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="is_active" id="is_active" value="1" checked
                                    class="w-5 h-5 text-brand-orange border-gray-300 rounded focus:ring-brand-orange">
                                <label for="is_active" class="text-sm font-medium text-gray-700">
                                    Karyawan Aktif
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Akun Login (Optional) -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                            <h2 class="font-semibold text-gray-900">Akun Login (Opsional)</h2>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="create_user_account" id="create_user_account"
                                    value="1"
                                    class="w-5 h-5 text-brand-orange border-gray-300 rounded focus:ring-brand-orange"
                                    onchange="toggleUserAccountFields()">
                                <span class="text-sm text-gray-600">Buat akun login</span>
                            </label>
                        </div>
                        <div id="userAccountFields" class="p-6 space-y-6" style="display: none;">
                            <p class="text-sm text-gray-500 mb-4">
                                <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                Buat akun login agar karyawan dapat mengakses sistem
                            </p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                                        Username <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="username" id="username"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-orange focus:border-brand-orange"
                                        placeholder="Masukkan username">
                                </div>
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                        Password <span class="text-red-500">*</span>
                                    </label>
                                    <input type="password" name="password" id="password"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-orange focus:border-brand-orange"
                                        placeholder="Minimal 6 karakter">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Foto -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h2 class="font-semibold text-gray-900">Foto Karyawan</h2>
                        </div>
                        <div class="p-6">
                            <div class="flex flex-col items-center">
                                <div id="fotoPreview"
                                    class="w-32 h-32 rounded-full bg-gray-100 flex items-center justify-center mb-4 overflow-hidden">
                                    <i class="fas fa-user text-gray-400 text-4xl"></i>
                                </div>
                                <label for="foto"
                                    class="cursor-pointer inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">
                                    <i class="fas fa-upload mr-2"></i>
                                    Pilih Foto
                                </label>
                                <input type="file" name="foto" id="foto" accept="image/*" class="hidden"
                                    onchange="previewFoto(this)">
                                <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG. Max 2MB</p>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <button type="submit" id="btnSubmit"
                            class="w-full px-6 py-2.5 bg-transparent text-orange-600 border border-orange-600 font-medium rounded-lg hover:bg-orange-600 hover:text-white transition-colors inline-flex items-center justify-center gap-2">
                            <i class="fas fa-save"></i>
                            <span>Simpan Karyawan</span>
                            <i class="fas fa-spinner fa-spin" id="spinner" style="display: none;"></i>
                        </button>
                        <a href="{{ route('master-data.karyawan.index') }}"
                            class="mt-3 w-full px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors inline-flex items-center justify-center">
                            Batal
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        function toggleUserAccountFields() {
            const checkbox = document.getElementById('create_user_account');
            const fields = document.getElementById('userAccountFields');
            fields.style.display = checkbox.checked ? 'block' : 'none';
        }

        function previewFoto(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('fotoPreview').innerHTML = `
                <img src="${e.target.result}" alt="Preview" class="w-full h-full object-cover">
            `;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        document.getElementById('formKaryawan').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = this;
            const btnSubmit = document.getElementById('btnSubmit');
            const spinner = document.getElementById('spinner');

            // Disable button and show spinner
            btnSubmit.disabled = true;
            spinner.style.display = 'inline-block';

            const formData = new FormData(form);

            fetch('{{ route('master-data.karyawan.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        SwalHelper.success('Berhasil!', data.message);
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, 1500);
                    } else {
                        SwalHelper.error('Gagal!', data.message || 'Terjadi kesalahan saat menyimpan data');
                        btnSubmit.disabled = false;
                        spinner.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    SwalHelper.error('Error!', 'Terjadi kesalahan saat menyimpan data');
                    btnSubmit.disabled = false;
                    spinner.style.display = 'none';
                });
        });
    </script>
@endpush
