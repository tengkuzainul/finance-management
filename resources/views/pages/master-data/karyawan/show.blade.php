@extends('layouts.app')

@section('title', 'Detail Karyawan')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('master-data.karyawan.index') }}"
                    class="w-10 h-10 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-600 hover:bg-slate-50 transition-colors">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Detail Karyawan</h1>
                    <p class="text-gray-600 mt-1">{{ $karyawan->nama_lengkap }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('master-data.karyawan.edit', $karyawan) }}"
                    class="inline-flex items-center px-4 py-2 bg-brand-orange text-white font-medium rounded-lg hover:bg-orange-600 transition-colors">
                    <i class="fas fa-edit mr-2"></i>
                    Edit
                </a>
                <button onclick="deleteKaryawan('{{ $karyawan->hash_id }}', '{{ $karyawan->nama_lengkap }}')"
                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-trash mr-2"></i>
                    Hapus
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Sidebar - Profile Card -->
            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-6 text-center">
                        <div
                            class="w-32 h-32 rounded-full bg-orange-600/10 flex items-center justify-center mx-auto mb-4 overflow-hidden">
                            @if ($karyawan->user && $karyawan->user->avatar)
                                <img src="{{ asset('storage/' . $karyawan->user->avatar) }}"
                                    alt="{{ $karyawan->nama_lengkap }}" class="w-full h-full object-cover">
                            @elseif ($karyawan->foto)
                                <img src="{{ Storage::url($karyawan->foto) }}" alt="{{ $karyawan->nama_lengkap }}"
                                    class="w-full h-full object-cover">
                            @else
                                <span
                                    class="text-orange-600 font-bold text-4xl">{{ substr($karyawan->nama_lengkap, 0, 1) }}</span>
                            @endif
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">{{ $karyawan->nama_lengkap }}</h2>
                        <p class="text-sm text-gray-500 mt-1">{{ $karyawan->nik }}</p>

                        <div class="flex items-center justify-center gap-2 mt-4">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                            {{ $karyawan->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                <span
                                    class="w-2 h-2 rounded-full mr-2 {{ $karyawan->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                {{ $karyawan->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 p-6">
                        <h3 class="font-semibold text-gray-900 mb-4">Informasi Kontak</h3>
                        <div class="space-y-3">
                            @if ($karyawan->no_telepon)
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center">
                                        <i class="fas fa-phone text-green-600 text-sm"></i>
                                    </div>
                                    <span class="text-gray-600">{{ $karyawan->no_telepon }}</span>
                                </div>
                            @endif
                            @if ($karyawan->email)
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-envelope text-blue-600 text-sm"></i>
                                    </div>
                                    <span class="text-gray-600">{{ $karyawan->email }}</span>
                                </div>
                            @endif
                            @if ($karyawan->alamat)
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-orange-100 flex items-center justify-center shrink-0">
                                        <i class="fas fa-map-marker-alt text-orange-600 text-sm"></i>
                                    </div>
                                    <span class="text-gray-600">{{ $karyawan->alamat }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if ($karyawan->user)
                        <div class="border-t border-gray-100 p-6">
                            <h3 class="font-semibold text-gray-900 mb-4">Akun Login</h3>
                            <div class="flex items-center gap-3 p-3 bg-green-50 rounded-lg">
                                <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center">
                                    <i class="fas fa-user-check text-green-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-green-700">{{ $karyawan->user->username }}</p>
                                    <p class="text-xs text-green-600">Memiliki akses login</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Data Diri -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="font-semibold text-gray-900">Data Diri</h2>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tempat, Tanggal Lahir</dt>
                                <dd class="mt-1 text-gray-900">
                                    {{ $karyawan->tempat_lahir ?: '-' }}{{ $karyawan->tanggal_lahir ? ', ' . $karyawan->tanggal_lahir->format('d M Y') : '' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Usia</dt>
                                <dd class="mt-1 text-gray-900">{{ $karyawan->age ? $karyawan->age . ' tahun' : '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Jenis Kelamin</dt>
                                <dd class="mt-1 text-gray-900">{{ $karyawan->jenis_kelamin }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Agama</dt>
                                <dd class="mt-1 text-gray-900">{{ $karyawan->agama ?: '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status Pernikahan</dt>
                                <dd class="mt-1 text-gray-900">{{ $karyawan->status_pernikahan ?: '-' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Data Pekerjaan -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="font-semibold text-gray-900">Data Pekerjaan</h2>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Cabang</dt>
                                <dd class="mt-1">
                                    @if ($karyawan->cabang)
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 text-brand-blue font-bold text-xs">
                                                {{ substr($karyawan->cabang->kode_cabang, -2) }}
                                            </span>
                                            <span class="text-gray-900">{{ $karyawan->cabang->nama_cabang }}</span>
                                        </div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tanggal Masuk</dt>
                                <dd class="mt-1 text-gray-900">{{ $karyawan->tanggal_masuk?->format('d M Y') ?: '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Masa Kerja</dt>
                                <dd class="mt-1 text-gray-900">{{ $karyawan->work_duration ?: '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="mt-1">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $karyawan->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $karyawan->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Riwayat Laporan Keuangan -->
                @if ($karyawan->laporanKeuangans && $karyawan->laporanKeuangans->count() > 0)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                            <h2 class="font-semibold text-gray-900">Laporan Keuangan Terbaru</h2>
                            <a href="{{ route('master-data.laporan-keuangan.index', ['karyawan_id' => $karyawan->hash_id]) }}"
                                class="text-sm text-brand-orange hover:text-orange-600 font-medium">
                                Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @foreach ($karyawan->laporanKeuangans as $laporan)
                                <div class="px-6 py-4 flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-10 h-10 rounded-lg flex items-center justify-center
                                {{ $laporan->jenis == 'Pendapatan' ? 'bg-green-100' : 'bg-red-100' }}">
                                            <i
                                                class="fas {{ $laporan->jenis == 'Pendapatan' ? 'fa-arrow-up text-green-600' : 'fa-arrow-down text-red-600' }}"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $laporan->keterangan }}</p>
                                            <p class="text-sm text-gray-500">{{ $laporan->formatted_tanggal }} ·
                                                {{ $laporan->kategori }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p
                                            class="font-semibold {{ $laporan->jenis == 'Pendapatan' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $laporan->jenis == 'Pendapatan' ? '+' : '-' }}
                                            {{ $laporan->formatted_jumlah }}
                                        </p>
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $laporan->status_badge_class }}">
                                            {{ $laporan->status }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Timestamps -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between text-sm text-gray-500">
                        <span>Dibuat: {{ $karyawan->created_at->format('d M Y H:i') }}</span>
                        <span>Diupdate: {{ $karyawan->updated_at->format('d M Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function deleteKaryawan(id, name) {
            SwalHelper.confirm(
                'Hapus Karyawan',
                `Apakah Anda yakin ingin menghapus karyawan "${name}"?`,
                'Ya, Hapus',
                'error'
            ).then((result) => {
                if (result.isConfirmed) {
                    fetch(`{{ url('master-data/karyawan') }}/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                SwalHelper.success('Berhasil!', data.message);
                                setTimeout(() => {
                                    window.location.href = '{{ route('master-data.karyawan.index') }}';
                                }, 1500);
                            } else {
                                SwalHelper.error('Gagal!', data.message);
                            }
                        })
                        .catch(error => {
                            SwalHelper.error('Error!', 'Terjadi kesalahan saat menghapus data');
                        });
                }
            });
        }
    </script>
@endpush

