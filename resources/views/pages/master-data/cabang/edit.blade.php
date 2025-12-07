@extends('layouts.app')

@section('title', 'Edit Cabang')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center gap-4">
            <a href="{{ route('master-data.cabang.index') }}"
                class="w-10 h-10 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-600 hover:bg-slate-50 transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Cabang</h1>
                <p class="text-gray-600 mt-1">Edit data cabang {{ $cabang->nama_cabang }}</p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <form id="formCabang" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kode Cabang -->
                    <div>
                        <label for="kode_cabang" class="block text-sm font-medium text-gray-700 mb-2">
                            Kode Cabang <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="kode_cabang" id="kode_cabang" value="{{ $cabang->kode_cabang }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-orange focus:border-brand-orange"
                            placeholder="Contoh: CBG001" required>
                        <p class="text-xs text-gray-500 mt-1">Kode cabang akan digunakan sebagai identitas unik</p>
                    </div>

                    <!-- Nama Cabang -->
                    <div>
                        <label for="nama_cabang" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Cabang <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_cabang" id="nama_cabang" value="{{ $cabang->nama_cabang }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-orange focus:border-brand-orange"
                            placeholder="Contoh: Cabang Pusat Jakarta" required>
                    </div>
                </div>

                <!-- Alamat Lengkap -->
                <div>
                    <label for="alamat_lengkap" class="block text-sm font-medium text-gray-700 mb-2">
                        Alamat Lengkap <span class="text-red-500">*</span>
                    </label>
                    <textarea name="alamat_lengkap" id="alamat_lengkap" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-orange focus:border-brand-orange resize-none"
                        placeholder="Masukkan alamat lengkap cabang..." required>{{ $cabang->alamat_lengkap }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- No Telepon -->
                    <div>
                        <label for="no_telepon" class="block text-sm font-medium text-gray-700 mb-2">
                            No. Telepon
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="fas fa-phone"></i>
                            </span>
                            <input type="tel" name="no_telepon" id="no_telepon" value="{{ $cabang->no_telepon }}"
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-orange focus:border-brand-orange"
                                placeholder="Contoh: 021-1234567">
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" name="email" id="email" value="{{ $cabang->email }}"
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-orange focus:border-brand-orange"
                                placeholder="Contoh: cabang@kebabikhwan.com">
                        </div>
                    </div>
                </div>

                <!-- Status Aktif -->
                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_active" id="is_active" value="1"
                        {{ $cabang->is_active ? 'checked' : '' }}
                        class="w-5 h-5 text-brand-orange border-gray-300 rounded focus:ring-brand-orange">
                    <label for="is_active" class="text-sm font-medium text-gray-700">
                        Cabang Aktif
                    </label>
                    <span class="text-xs text-gray-500">(Cabang yang tidak aktif tidak akan muncul dalam pilihan)</span>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('master-data.cabang.index') }}"
                        class="px-6 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" id="btnSubmit"
                        class="px-6 py-2.5 bg-transparent text-orange-600 border border-orange-600 font-medium rounded-lg hover:bg-orange-600 hover:text-white transition-colors inline-flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        <span>Update</span>
                        <i class="fas fa-spinner fa-spin" id="spinner" style="display: none;"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('formCabang').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = this;
            const btnSubmit = document.getElementById('btnSubmit');
            const spinner = document.getElementById('spinner');

            // Disable button and show spinner
            btnSubmit.disabled = true;
            spinner.style.display = 'inline-block';

            const formData = new FormData(form);

            fetch('{{ route('master-data.cabang.update', $cabang) }}', {
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
