@extends('layouts.app')

@section('title', 'Tambah Pendapatan')

@section('page-header')
    <div class="flex items-center gap-4">
        <a href="{{ route('Pendapatan.index') }}"
            class="w-10 h-10 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-600 hover:bg-slate-50 transition-colors">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-800">Tambah Pendapatan</h1>
            <p class="text-slate-500 mt-1">Catat transaksi Pendapatan baru</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="max-w-3xl">
        <form id="pemasukanForm" action="{{ route('Pendapatan.store') }}" method="POST" enctype="multipart/form-data"
            class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            @csrf
            <div class="p-6 md:p-8 space-y-6">
                <!-- Tanggal & Waktu -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-slate-700 mb-2">Tanggal <span
                                class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="date" id="tanggal" name="tanggal" value="{{ date('Y-m-d') }}"
                                class="w-full pl-12 pr-4 py-3 bg-slate-100 border-0 rounded-xl text-slate-700 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:bg-white transition-all duration-200"
                                required>
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                <i class="fas fa-calendar"></i>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="waktu" class="block text-sm font-medium text-slate-700 mb-2">Waktu</label>
                        <div class="relative">
                            <input type="time" id="waktu" name="waktu" value="{{ date('H:i') }}"
                                class="w-full pl-12 pr-4 py-3 bg-slate-100 border-0 rounded-xl text-slate-700 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:bg-white transition-all duration-200">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kategori -->
                <div>
                    <label for="kategori" class="block text-sm font-medium text-slate-700 mb-2">Kategori <span
                            class="text-red-500">*</span></label>
                    <div class="relative">
                        <select id="kategori" name="kategori_id"
                            class="w-full pl-12 pr-4 py-3 bg-slate-100 border-0 rounded-xl text-slate-700 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:bg-white transition-all duration-200 appearance-none"
                            required>
                            <option value="">Pilih Kategori</option>
                            <option value="1">Penjualan Kebab</option>
                            <option value="2">Penjualan Minuman</option>
                            <option value="3">Penjualan Paket</option>
                            <option value="4">Lainnya</option>
                        </select>
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                            <i class="fas fa-folder"></i>
                        </div>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-slate-700 mb-2">Deskripsi <span
                            class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="text" id="deskripsi" name="deskripsi"
                            placeholder="Contoh: Penjualan Kebab Original 10 porsi"
                            class="w-full pl-12 pr-4 py-3 bg-slate-100 border-0 rounded-xl text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:bg-white transition-all duration-200"
                            required>
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                            <i class="fas fa-pen"></i>
                        </div>
                    </div>
                </div>

                <!-- Jumlah -->
                <div>
                    <label for="jumlah" class="block text-sm font-medium text-slate-700 mb-2">Jumlah <span
                            class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="text" id="jumlah" name="jumlah" placeholder="0"
                            class="w-full pl-20 pr-4 py-3 bg-slate-100 border-0 rounded-xl text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:bg-white transition-all duration-200 text-right text-xl font-semibold"
                            required>
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-medium">
                            Rp
                        </div>
                    </div>
                    <p class="mt-2 text-sm text-slate-500">Masukkan jumlah Pendapatan tanpa titik atau koma</p>
                </div>

                <!-- Catatan -->
                <div>
                    <label for="catatan" class="block text-sm font-medium text-slate-700 mb-2">Catatan (Opsional)</label>
                    <textarea id="catatan" name="catatan" rows="3" placeholder="Tambahkan catatan tambahan jika diperlukan..."
                        class="w-full px-4 py-3 bg-slate-100 border-0 rounded-xl text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:bg-white transition-all duration-200 resize-none"></textarea>
                </div>

                <!-- Bukti Transaksi -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Bukti Transaksi (Opsional)</label>
                    <div
                        class="border-2 border-dashed border-slate-200 rounded-xl p-8 text-center hover:border-green-500 hover:bg-green-50/30 transition-all duration-200 cursor-pointer">
                        <input type="file" id="bukti" name="bukti" class="hidden" accept="image/*">
                        <label for="bukti" class="cursor-pointer">
                            <div class="w-16 h-16 bg-slate-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-cloud-upload-alt text-2xl text-slate-400"></i>
                            </div>
                            <p class="text-sm font-medium text-slate-700">Klik untuk upload atau drag & drop</p>
                            <p class="text-xs text-slate-500 mt-1">PNG, JPG hingga 2MB</p>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 md:px-8 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="button" onclick="confirmCancel()"
                    class="px-6 py-2.5 text-slate-600 text-sm font-medium hover:bg-slate-200 rounded-xl transition-colors">
                    Batal
                </button>
                <button type="button" onclick="confirmSubmit()"
                    class="px-6 py-2.5 bg-linear-to-r from-green-500 to-green-600 text-white text-sm font-medium rounded-xl hover:from-green-600 hover:to-green-700 shadow-lg shadow-green-500/30 transition-all duration-200 flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    <span>Simpan Pendapatan</span>
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            // Format currency input
            document.getElementById('jumlah').addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                e.target.value = value;
            });

            // Confirm cancel
            function confirmCancel() {
                SwalHelper.confirm(
                    'Data yang sudah diisi akan hilang. Yakin ingin membatalkan?',
                    'Batalkan Pengisian?'
                ).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ route('Pendapatan.index') }}';
                    }
                });
            }

            // Confirm submit
            function confirmSubmit() {
                const form = document.getElementById('pemasukanForm');
                const kategori = document.getElementById('kategori').value;
                const deskripsi = document.getElementById('deskripsi').value.trim();
                const jumlah = document.getElementById('jumlah').value.trim();

                // Validation
                if (!kategori) {
                    SwalHelper.warning('Silakan pilih kategori terlebih dahulu.');
                    document.getElementById('kategori').focus();
                    return;
                }

                if (!deskripsi) {
                    SwalHelper.warning('Silakan isi deskripsi transaksi.');
                    document.getElementById('deskripsi').focus();
                    return;
                }

                if (!jumlah || parseInt(jumlah) <= 0) {
                    SwalHelper.warning('Silakan isi jumlah Pendapatan yang valid.');
                    document.getElementById('jumlah').focus();
                    return;
                }

                // Format jumlah for display
                const formattedJumlah = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(jumlah);

                SwalHelper.confirm(
                    `Simpan Pendapatan sebesar ${formattedJumlah}?`,
                    'Konfirmasi Simpan'
                ).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        SwalHelper.loading('Menyimpan data...');

                        // Submit form (for demo, you can use AJAX)
                        // For now, just show success and redirect
                        setTimeout(() => {
                            SwalHelper.closeLoading();
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Data Pendapatan berhasil disimpan.',
                                confirmButtonColor: '#22c55e',
                                timer: 2000,
                                timerProgressBar: true,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = '{{ route('Pendapatan.index') }}';
                            });
                        }, 1000);
                    }
                });
            }
        </script>
    @endpush
@endsection

