@extends('layouts.app')

@section('title', 'Pengaturan')

@section('page-header')
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-slate-800">Pengaturan</h1>
        <p class="text-slate-500 mt-1">Konfigurasi sistem dan manajemen informasi</p>
    </div>
@endsection

@section('content')
    <div class="max-w-full mx-auto">
        <!-- Tab Navigation -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-6">
            <div class="border-b border-slate-100">
                <nav class="flex gap-2 p-2" aria-label="Tabs">
                    <a href="{{ route('settings.index', ['tab' => 'konfigurasi']) }}"
                        class="flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ $tab === 'konfigurasi' ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'text-slate-600 hover:bg-slate-100' }}">
                        <i class="fas fa-cog"></i>
                        <span>Konfigurasi Gaji</span>
                    </a>
                    <a href="{{ route('settings.index', ['tab' => 'informasi']) }}"
                        class="flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ $tab === 'informasi' ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'text-slate-600 hover:bg-slate-100' }}">
                        <i class="fas fa-bullhorn"></i>
                        <span>Informasi Manajemen</span>
                    </a>
                </nav>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-check text-green-600"></i>
                    </div>
                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center shrink-0">
                        <i class="fas fa-exclamation-circle text-red-600"></i>
                    </div>
                    <div>
                        <p class="text-red-700 font-medium">Terjadi kesalahan:</p>
                        <ul class="text-red-600 text-sm mt-1 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Tab Content -->
        @if ($tab === 'konfigurasi')
            <!-- Konfigurasi Gaji Tab -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-gradient-to-r from-orange-50 to-amber-50">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-14 h-14 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center shadow-lg shadow-orange-500/30">
                            <i class="fas fa-percent text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Konfigurasi Persentase Gaji</h3>
                            <p class="text-sm text-slate-500">Atur persentase gaji harian karyawan dari total pemasukan</p>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <form action="{{ route('settings.persen-gaji.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Info Box -->
                        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                                <div class="text-sm text-blue-700">
                                    <p class="font-medium mb-1">Cara Perhitungan Gaji Karyawan:</p>
                                    <ul class="list-disc list-inside space-y-1 text-blue-600">
                                        <li>Gaji karyawan dihitung per hari berdasarkan laporan yang sudah diapprove</li>
                                        <li>Rumus: <span class="font-mono bg-blue-100 px-2 py-0.5 rounded">Gaji = Total
                                                Pemasukan × (Persentase / 100)</span></li>
                                        <li>Contoh: Jika pemasukan Rp 1.000.000 dan persentase 13%, maka gaji = Rp 130.000
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Persen Gaji Input -->
                        <div class="mb-6">
                            <label for="persen_gaji" class="block text-sm font-medium text-slate-700 mb-2">
                                Persentase Gaji Karyawan <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="persen_gaji" id="persen_gaji"
                                    value="{{ old('persen_gaji', $persenGaji->nilai ?? 13) }}" min="0" max="100"
                                    step="0.01"
                                    class="w-full px-4 py-3 pr-12 bg-slate-100 border-0 rounded-xl text-slate-700 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:bg-white transition-all duration-200 text-lg font-semibold"
                                    required>
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 font-medium">%</span>
                            </div>
                            <p class="text-sm text-slate-500 mt-2">Masukkan angka antara 0 - 100</p>
                        </div>

                        <!-- Current Setting Info -->
                        <div class="mb-6 p-4 bg-orange-50 border border-orange-200 rounded-xl">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-orange-700">Pengaturan saat ini:</p>
                                    <p class="text-2xl font-bold text-orange-600">{{ $persenGaji->nilai ?? 13 }}%</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-orange-700">Terakhir diubah:</p>
                                    <p class="text-sm font-medium text-orange-600">
                                        {{ $persenGaji ? $persenGaji->updated_at->format('d M Y, H:i') : 'Default' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit"
                                class="px-6 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-medium rounded-xl hover:from-orange-600 hover:to-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500/50 shadow-lg shadow-orange-500/30 transition-all duration-200">
                                <i class="fas fa-save mr-2"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <!-- Informasi Manajemen Tab -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Form Tambah Informasi -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                        <div class="p-6 border-b border-slate-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                                    <i class="fas fa-plus text-white"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-800">Tambah Informasi</h3>
                                    <p class="text-xs text-slate-500">Kirim ke semua karyawan</p>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('settings.informasi.store') }}" method="POST" enctype="multipart/form-data"
                            class="p-6 space-y-4">
                            @csrf

                            <!-- Judul -->
                            <div>
                                <label for="judul" class="block text-sm font-medium text-slate-700 mb-2">
                                    Judul Informasi <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="judul" id="judul" value="{{ old('judul') }}"
                                    class="w-full px-4 py-3 bg-slate-100 border-0 rounded-xl text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all duration-200"
                                    placeholder="Masukkan judul informasi" required>
                            </div>

                            <!-- Deskripsi -->
                            <div>
                                <label for="deskripsi" class="block text-sm font-medium text-slate-700 mb-2">
                                    Deskripsi <span class="text-red-500">*</span>
                                </label>
                                <textarea name="deskripsi" id="deskripsi" rows="4"
                                    class="w-full px-4 py-3 bg-slate-100 border-0 rounded-xl text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all duration-200 resize-none"
                                    placeholder="Tulis deskripsi informasi..." required>{{ old('deskripsi') }}</textarea>
                            </div>

                            <!-- Lampiran -->
                            <div>
                                <label for="lampiran" class="block text-sm font-medium text-slate-700 mb-2">
                                    Lampiran <span class="text-slate-400">(Opsional)</span>
                                </label>
                                <div class="relative">
                                    <input type="file" name="lampiran" id="lampiran"
                                        accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png" class="hidden"
                                        onchange="updateFileName(this)">
                                    <label for="lampiran"
                                        class="flex items-center gap-3 px-4 py-3 bg-slate-100 border-2 border-dashed border-slate-300 rounded-xl cursor-pointer hover:bg-slate-50 hover:border-blue-400 transition-all duration-200">
                                        <i class="fas fa-cloud-upload-alt text-slate-400"></i>
                                        <span id="file-name" class="text-sm text-slate-500">Pilih file atau drag &
                                            drop</span>
                                    </label>
                                </div>
                                <p class="text-xs text-slate-500 mt-2">PDF, DOC, XLS, JPG, PNG (Max: 5MB)</p>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit"
                                class="w-full px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-medium rounded-xl hover:from-blue-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500/50 shadow-lg shadow-blue-500/30 transition-all duration-200">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Kirim Informasi
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Daftar Informasi -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                        <div class="p-6 border-b border-slate-100">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-bold text-slate-800">Daftar Informasi</h3>
                                    <p class="text-sm text-slate-500">{{ $informasis->total() }} informasi ditemukan</p>
                                </div>
                            </div>
                        </div>

                        <div class="divide-y divide-slate-100">
                            @forelse($informasis as $informasi)
                                <div class="p-6 hover:bg-slate-50 transition-colors">
                                    <div class="flex items-start gap-4">
                                        <div
                                            class="w-12 h-12 bg-gradient-to-br from-orange-100 to-amber-100 rounded-xl flex items-center justify-center shrink-0">
                                            <i class="fas fa-bullhorn text-orange-600"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between gap-4">
                                                <div>
                                                    <p class="text-xs text-orange-600 font-mono mb-1">
                                                        {{ $informasi->kode_informasi }}</p>
                                                    <h4 class="font-semibold text-slate-800">{{ $informasi->judul }}</h4>
                                                    <p class="text-sm text-slate-500 mt-1 line-clamp-2">
                                                        {{ Str::limit($informasi->deskripsi, 150) }}</p>
                                                    <div class="flex items-center gap-4 mt-3 text-xs text-slate-400">
                                                        <span>
                                                            <i class="fas fa-user mr-1"></i>
                                                            {{ $informasi->creator->name ?? 'Admin' }}
                                                        </span>
                                                        <span>
                                                            <i class="fas fa-calendar mr-1"></i>
                                                            {{ $informasi->created_at->format('d M Y, H:i') }}
                                                        </span>
                                                        @if ($informasi->lampiran)
                                                            <span class="text-blue-500">
                                                                <i class="fas fa-paperclip mr-1"></i>
                                                                Lampiran
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <a href="{{ route('settings.informasi.show', $informasi->hashid) }}"
                                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                                        title="Lihat Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <form
                                                        action="{{ route('settings.informasi.destroy', $informasi->hashid) }}"
                                                        method="POST" class="inline delete-informasi-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button"
                                                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors btn-delete-informasi"
                                                            title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-12 text-center">
                                    <div
                                        class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-bullhorn text-slate-400 text-xl"></i>
                                    </div>
                                    <h4 class="font-semibold text-slate-700">Belum ada informasi</h4>
                                    <p class="text-sm text-slate-500 mt-1">Tambahkan informasi baru untuk karyawan</p>
                                </div>
                            @endforelse
                        </div>

                        @if ($informasis->hasPages())
                            <div class="p-4 border-t border-slate-100">
                                {{ $informasis->appends(['tab' => 'informasi'])->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function updateFileName(input) {
            const fileName = input.files[0]?.name || 'Pilih file atau drag & drop';
            document.getElementById('file-name').textContent = fileName;
        }

        // Delete informasi with SweetAlert
        document.querySelectorAll('.btn-delete-informasi').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const form = this.closest('form');

                Swal.fire({
                    title: 'Hapus Informasi?',
                    text: 'Informasi ini akan dihapus secara permanen dan tidak dapat dikembalikan',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // Show success/error alerts
        @if (session('success'))
            Swal.fire({
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonColor: '#f97316'
            });
        @endif

        @if (session('error'))
            Swal.fire({
                title: 'Gagal!',
                text: '{{ session('error') }}',
                icon: 'error',
                confirmButtonColor: '#f97316'
            });
        @endif
    </script>
@endpush
