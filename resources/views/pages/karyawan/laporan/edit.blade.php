@extends('layouts.app')

@section('title', 'Edit Laporan')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center gap-4">
            <a href="{{ route('karyawan.laporan.riwayat') }}"
                class="w-10 h-10 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-600 hover:bg-slate-50 transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Laporan</h1>
                <p class="text-gray-600 mt-1">Edit draft laporan sebelum diajukan</p>
            </div>
        </div>

        <!-- Info Karyawan -->
        <div class="bg-gradient-to-r from-yellow-50 to-amber-50 rounded-xl border border-yellow-200 p-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center">
                    <i class="fas fa-edit text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <p class="font-semibold text-yellow-800">{{ $karyawan->nama_lengkap }}</p>
                    <p class="text-sm text-yellow-600">{{ $cabang->nama_cabang }} ({{ $cabang->kode_cabang }}) - Draft</p>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <form id="formEdit" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-100 bg-green-50 rounded-t-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                            <i
                                class="fas fa-{{ $jenis === 'Pemasukan' ? 'arrow-up' : 'arrow-down' }} text-green-600 text-lg"></i>
                        </div>
                        <div>
                            <h2 class="font-semibold text-green-800">{{ $jenis }}</h2>
                            <p class="text-sm text-green-600">Edit data {{ strtolower($jenis) }}</p>
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
                            <input type="date" name="tanggal" id="tanggal"
                                value="{{ $laporan->tanggal->format('Y-m-d') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                required>
                        </div>

                        <!-- Kategori -->
                        <div>
                            <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">
                                Kategori <span class="text-red-500">*</span>
                            </label>
                            <select name="kategori" id="kategori"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategoriList as $kategori)
                                    <option value="{{ $kategori }}"
                                        {{ $laporan->kategori === $kategori ? 'selected' : '' }}>{{ $kategori }}
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
                                value="{{ $laporan->jumlah }}"
                                class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-xl font-semibold"
                                placeholder="0" required>
                        </div>
                    </div>

                    <!-- Keterangan -->
                    <div>
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                            Keterangan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="keterangan" id="keterangan" value="{{ $laporan->keterangan }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            placeholder="Contoh: Penjualan harian, Penjualan online, dll." required>
                    </div>

                    <!-- Catatan -->
                    <div>
                        <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan Tambahan
                        </label>
                        <textarea name="catatan" id="catatan" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 resize-none"
                            placeholder="Catatan tambahan jika diperlukan...">{{ $laporan->catatan }}</textarea>
                    </div>

                    <!-- Bukti Transaksi -->
                    <div>
                        <label for="bukti_transaksi" class="block text-sm font-medium text-gray-700 mb-2">
                            Bukti Transaksi
                        </label>
                        @if ($laporan->bukti_transaksi)
                            <div class="mb-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <p class="text-sm text-gray-600 mb-2">Bukti saat ini:</p>
                                <img src="{{ asset('storage/' . $laporan->bukti_transaksi) }}" alt="Bukti"
                                    class="max-h-32 rounded-lg">
                            </div>
                        @endif
                        <div
                            class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-green-500 transition-colors relative">
                            <div id="uploadPreview" class="hidden mb-4">
                                <img id="previewImage" class="max-h-40 mx-auto rounded-lg">
                            </div>
                            <div id="uploadPlaceholder">
                                <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl mb-2"></i>
                                <p class="text-gray-600">
                                    {{ $laporan->bukti_transaksi ? 'Klik untuk mengganti' : 'Klik untuk upload atau drag & drop' }}
                                </p>
                                <p class="text-sm text-gray-400">JPG, PNG, atau PDF (Max 5MB)</p>
                            </div>
                            <input type="file" name="bukti_transaksi" id="bukti_transaksi" accept="image/*,.pdf"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                onchange="previewFile(this)">
                        </div>
                    </div>

                    <!-- Info -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex gap-3">
                            <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                            <div class="text-sm text-blue-700">
                                <p class="font-medium">Informasi</p>
                                <p class="mt-1"><strong>Simpan Draft:</strong> Perubahan disimpan sebagai draft, bisa
                                    diedit lagi nanti.</p>
                                <p class="mt-1"><strong>Ajukan Approval:</strong> Laporan dikirim ke admin untuk
                                    disetujui.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div
                    class="px-6 py-4 bg-gray-50 border-t border-gray-100 rounded-b-xl flex flex-col sm:flex-row items-center justify-end gap-3">
                    <a href="{{ route('karyawan.laporan.riwayat') }}"
                        class="w-full sm:w-auto px-6 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-colors text-center">
                        Batal
                    </a>
                    <button type="button" onclick="submitForm('draft')" id="btnDraft"
                        class="w-full sm:w-auto px-6 py-2.5 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors inline-flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span class="icon"><i class="fas fa-save"></i></span>
                        <span class="spinner hidden"><i class="fas fa-spinner fa-spin"></i></span>
                        <span class="text">Simpan Draft</span>
                    </button>
                    <button type="button" onclick="submitForm('pending')" id="btnSubmit"
                        class="w-full sm:w-auto px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors inline-flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span class="icon"><i class="fas fa-paper-plane"></i></span>
                        <span class="spinner hidden"><i class="fas fa-spinner fa-spin"></i></span>
                        <span class="text">Ajukan Approval</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        function previewFile(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];

                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('previewImage').src = e.target.result;
                        document.getElementById('uploadPreview').classList.remove('hidden');
                        document.getElementById('uploadPlaceholder').classList.add('hidden');
                    }
                    reader.readAsDataURL(file);
                } else {
                    document.getElementById('uploadPlaceholder').innerHTML = `
                        <i class="fas fa-file-pdf text-red-500 text-3xl mb-2"></i>
                        <p class="text-gray-600">${file.name}</p>
                        <p class="text-sm text-gray-400">Klik untuk mengganti file</p>
                    `;
                }
            }
        }

        document.getElementById('formEdit').addEventListener('submit', function(e) {
            e.preventDefault();
        });

        function submitForm(status) {
            const form = document.getElementById('formEdit');
            const formData = new FormData(form);
            formData.append('submit_type', status);

            const btn = status === 'draft' ? document.getElementById('btnDraft') : document.getElementById('btnSubmit');
            const otherBtn = status === 'draft' ? document.getElementById('btnSubmit') : document.getElementById(
            'btnDraft');
            const spinner = btn.querySelector('.spinner');
            const icon = btn.querySelector('.icon');
            const text = btn.querySelector('.text');
            const originalText = text.textContent;

            // Show loading state
            btn.disabled = true;
            otherBtn.disabled = true;
            icon.classList.add('hidden');
            spinner.classList.remove('hidden');
            text.textContent = status === 'draft' ? 'Menyimpan...' : 'Mengirim...';

            fetch('{{ route('karyawan.laporan.update', $laporan->hash_id) }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        SwalHelper.success('Berhasil', data.message).then(() => {
                            window.location.href = '{{ route('karyawan.laporan.riwayat') }}' +
                                (status === 'draft' ? '?status=Draft' : '?status=Pending');
                        });
                    } else {
                        SwalHelper.error('Gagal', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    SwalHelper.error('Gagal', 'Terjadi kesalahan saat menyimpan data');
                })
                .finally(() => {
                    // Reset button state
                    btn.disabled = false;
                    otherBtn.disabled = false;
                    spinner.classList.add('hidden');
                    icon.classList.remove('hidden');
                    text.textContent = originalText;
                });
        }
    </script>
@endpush
