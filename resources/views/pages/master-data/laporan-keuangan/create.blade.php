@extends('layouts.app')

@section('title', 'Tambah Laporan ' . $jenis)

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center gap-4">
            <a href="{{ route('master-data.laporan-keuangan.index') }}"
                class="w-10 h-10 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-600 hover:bg-slate-50 transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Tambah {{ $jenis }}</h1>
                <p class="text-gray-600 mt-1">Catat {{ strtolower($jenis) }} baru</p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <form id="formLaporan" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="jenis" value="{{ $jenis }}">

                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <!-- Header dengan warna sesuai jenis -->
                    <div
                        class="px-6 py-4 border-b border-gray-100 {{ $jenis == 'Pemasukan' ? 'bg-green-50' : 'bg-red-50' }} rounded-t-xl">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-lg {{ $jenis == 'Pemasukan' ? 'bg-green-100' : 'bg-red-100' }} flex items-center justify-center">
                                <i
                                    class="fas {{ $jenis == 'Pemasukan' ? 'fa-arrow-up text-green-600' : 'fa-arrow-down text-red-600' }} text-lg"></i>
                            </div>
                            <div>
                                <h2 class="font-semibold {{ $jenis == 'Pemasukan' ? 'text-green-800' : 'text-red-800' }}">
                                    {{ $jenis }}</h2>
                                <p class="text-sm {{ $jenis == 'Pemasukan' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $jenis == 'Pemasukan' ? 'Catat semua pemasukan usaha' : 'Catat semua pengeluaran usaha' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Tanggal -->
                            <div>
                                <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="tanggal" id="tanggal" value="{{ date('Y-m-d') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-orange focus:border-brand-orange"
                                    required>
                            </div>

                            <!-- Cabang -->
                            <div>
                                <label for="cabang_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Cabang <span class="text-red-500">*</span>
                                </label>
                                <select name="cabang_id" id="cabang_id"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-orange focus:border-brand-orange"
                                    required onchange="loadKaryawan()">
                                    <option value="">Pilih Cabang</option>
                                    @foreach ($cabangs as $cabang)
                                        <option value="{{ $cabang->hash_id }}"
                                            {{ isset($selectedCabangId) && $selectedCabangId == $cabang->id ? 'selected' : '' }}>
                                            {{ $cabang->nama_cabang }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Kategori -->
                            <div>
                                <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">
                                    Kategori <span class="text-red-500">*</span>
                                </label>
                                <select name="kategori" id="kategori"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-orange focus:border-brand-orange"
                                    required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach ($kategoriList as $kategori)
                                        <option value="{{ $kategori }}">{{ $kategori }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Karyawan (optional) -->
                            <div>
                                <label for="karyawan_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Karyawan Terkait
                                </label>
                                <select name="karyawan_id" id="karyawan_id"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-orange focus:border-brand-orange">
                                    <option value="">Pilih Karyawan (Opsional)</option>
                                    @foreach ($karyawans as $karyawan)
                                        <option value="{{ $karyawan->hash_id }}"
                                            data-cabang="{{ $karyawan->cabang?->hash_id }}">
                                            {{ $karyawan->nama_lengkap }} ({{ $karyawan->nik }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Jumlah -->
                        <div>
                            <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-2">
                                Jumlah <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                                <input type="number" name="jumlah" id="jumlah" min="0" step="1"
                                    class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-orange focus:border-brand-orange text-xl font-semibold"
                                    placeholder="0" required>
                            </div>
                        </div>

                        <!-- Keterangan -->
                        <div>
                            <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                                Keterangan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="keterangan" id="keterangan"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-orange focus:border-brand-orange"
                                placeholder="Contoh: Penjualan harian, Pembelian bahan baku, dll." required>
                        </div>

                        <!-- Catatan -->
                        <div>
                            <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">
                                Catatan Tambahan
                            </label>
                            <textarea name="catatan" id="catatan" rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-orange focus:border-brand-orange resize-none"
                                placeholder="Catatan tambahan jika diperlukan..."></textarea>
                        </div>

                        <!-- Bukti Transaksi -->
                        <div>
                            <label for="bukti_transaksi" class="block text-sm font-medium text-gray-700 mb-2">
                                Bukti Transaksi
                            </label>
                            <div
                                class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-brand-orange transition-colors relative">
                                <div id="uploadPreview" class="hidden mb-4">
                                    <img id="previewImage" class="max-h-40 mx-auto rounded-lg">
                                </div>
                                <div id="uploadPlaceholder">
                                    <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl mb-2"></i>
                                    <p class="text-gray-600">Klik untuk upload atau drag & drop</p>
                                    <p class="text-sm text-gray-400">JPG, PNG, atau PDF (Max 5MB)</p>
                                </div>
                                <input type="file" name="bukti_transaksi" id="bukti_transaksi" accept="image/*,.pdf"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                    onchange="previewFile(this)">
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Status
                            </label>
                            @if ($jenis == 'Pengeluaran')
                                {{-- Untuk pengeluaran admin: default auto-approved, bisa pilih draft untuk rencana --}}
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" name="save_as_draft" id="save_as_draft" value="1"
                                        class="w-4 h-4 text-brand-orange border-gray-300 rounded focus:ring-brand-orange">
                                    <label for="save_as_draft" class="text-gray-700 cursor-pointer">Simpan sebagai
                                        Draft</label>
                                    <span class="text-xs text-gray-500">(Untuk rencana pengeluaran yang bisa diedit)</span>
                                </div>
                                <p class="text-sm text-green-600 mt-2">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Pengeluaran akan langsung disetujui jika tidak dicentang
                                </p>
                            @else
                                {{-- Untuk pemasukan: pilihan draft atau pending --}}
                                <div class="flex gap-4">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="status" value="Draft" checked
                                            class="w-4 h-4 text-brand-orange border-gray-300 focus:ring-brand-orange">
                                        <span class="text-gray-700">Draft</span>
                                        <span class="text-xs text-gray-500">(Bisa diedit lagi)</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="status" value="Pending"
                                            class="w-4 h-4 text-brand-orange border-gray-300 focus:ring-brand-orange">
                                        <span class="text-gray-700">Ajukan Approval</span>
                                        <span class="text-xs text-gray-500">(Menunggu persetujuan)</span>
                                    </label>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div
                        class="px-6 py-4 bg-gray-50 border-t border-gray-100 rounded-b-xl flex items-center justify-end gap-3">
                        <a href="{{ route('master-data.laporan-keuangan.index') }}"
                            class="px-6 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-colors">
                            Batal
                        </a>
                        <button type="submit" id="btnSubmit"
                            class="px-6 py-2.5 {{ $jenis == 'Pemasukan' ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700' }} text-white font-medium rounded-lg transition-colors inline-flex items-center gap-2">
                            <i class="fas fa-save"></i>
                            <span>Simpan</span>
                            <i class="fas fa-spinner fa-spin" id="spinner" style="display: none;"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function loadKaryawan() {
            const cabangId = document.getElementById('cabang_id').value;
            const karyawanSelect = document.getElementById('karyawan_id');
            const options = karyawanSelect.querySelectorAll('option');

            options.forEach(option => {
                if (option.value === '') {
                    option.style.display = 'block';
                } else if (cabangId === '' || option.dataset.cabang === cabangId) {
                    option.style.display = 'block';
                } else {
                    option.style.display = 'none';
                }
            });

            karyawanSelect.value = '';
        }

        function previewFile(input) {
            const preview = document.getElementById('uploadPreview');
            const placeholder = document.getElementById('uploadPlaceholder');
            const previewImage = document.getElementById('previewImage');

            if (input.files && input.files[0]) {
                const file = input.files[0];

                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        preview.classList.remove('hidden');
                        placeholder.classList.add('hidden');
                    }
                    reader.readAsDataURL(file);
                } else {
                    preview.innerHTML = `
                <div class="flex items-center justify-center gap-3 p-4 bg-gray-100 rounded-lg">
                    <i class="fas fa-file-pdf text-red-500 text-2xl"></i>
                    <span class="text-gray-700">${file.name}</span>
                </div>
            `;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                }
            }
        }

        document.getElementById('formLaporan').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = this;
            const btnSubmit = document.getElementById('btnSubmit');
            const spinner = document.getElementById('spinner');

            btnSubmit.disabled = true;
            spinner.style.display = 'inline-block';

            const formData = new FormData(form);

            fetch('{{ route('master-data.laporan-keuangan.store') }}', {
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

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadKaryawan();
        });
    </script>
@endpush
