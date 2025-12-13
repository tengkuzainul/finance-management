@extends('layouts.app')

@section('title', 'Detail Cabang')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('master-data.cabang.index') }}"
                    class="w-10 h-10 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-600 hover:bg-slate-50 transition-colors">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Detail Cabang</h1>
                    <p class="text-gray-600 mt-1">{{ $cabang->nama_cabang }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('master-data.cabang.edit', $cabang) }}"
                    class="inline-flex items-center px-4 py-2 bg-brand-orange text-white font-medium rounded-lg hover:bg-orange-600 transition-colors">
                    <i class="fas fa-edit mr-2"></i>
                    Edit
                </a>
                <button onclick="deleteCabang('{{ $cabang->hash_id }}', '{{ $cabang->nama_cabang }}')"
                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-trash mr-2"></i>
                    Hapus
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Detail Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="font-semibold text-gray-900">Informasi Cabang</h2>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Kode Cabang</dt>
                                <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $cabang->kode_cabang }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nama Cabang</dt>
                                <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $cabang->nama_cabang }}</dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Alamat Lengkap</dt>
                                <dd class="mt-1 text-gray-900">{{ $cabang->alamat_lengkap }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">No. Telepon</dt>
                                <dd class="mt-1 text-gray-900">{{ $cabang->no_telepon ?: '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="mt-1 text-gray-900">{{ $cabang->email ?: '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Jumlah Karyawan</dt>
                                <dd class="mt-1">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-brand-blue font-semibold">
                                        {{ $cabang->jumlah_karyawan }} orang
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="mt-1">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                                    {{ $cabang->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        <span
                                            class="w-2 h-2 rounded-full mr-2 {{ $cabang->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                        {{ $cabang->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Dibuat Pada</dt>
                                <dd class="mt-1 text-gray-900">{{ $cabang->created_at->format('d M Y H:i') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Terakhir Diupdate</dt>
                                <dd class="mt-1 text-gray-900">{{ $cabang->updated_at->format('d M Y H:i') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Karyawan List -->
                @if ($cabang->karyawans->count() > 0)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                            <h2 class="font-semibold text-gray-900">Daftar Karyawan</h2>
                            <a href="{{ route('master-data.karyawan.index', ['cabang_id' => $cabang->hash_id]) }}"
                                class="text-sm text-brand-orange hover:text-orange-600 font-medium">
                                Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @foreach ($cabang->karyawans as $karyawan)
                                <div class="px-6 py-4 flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-10 h-10 rounded-full bg-brand-orange/10 flex items-center justify-center">
                                            @if ($karyawan->foto)
                                                <img src="{{ Storage::url($karyawan->foto) }}"
                                                    alt="{{ $karyawan->nama_lengkap }}"
                                                    class="w-10 h-10 rounded-full object-cover">
                                            @else
                                                <span
                                                    class="text-brand-orange font-semibold">{{ substr($karyawan->nama_lengkap, 0, 1) }}</span>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $karyawan->nama_lengkap }}</p>
                                            <p class="text-sm text-gray-500">{{ $karyawan->nik }}</p>
                                        </div>
                                    </div>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $karyawan->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $karyawan->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar Stats -->
            <div class="space-y-6">
                <!-- Quick Stats -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Statistik Cabang</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Total Karyawan</span>
                            <span class="font-semibold text-gray-900">{{ $cabang->jumlah_karyawan }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Karyawan Aktif</span>
                            <span
                                class="font-semibold text-green-600">{{ $cabang->karyawans->where('is_active', true)->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Karyawan Tidak Aktif</span>
                            <span
                                class="font-semibold text-red-600">{{ $cabang->karyawans->where('is_active', false)->count() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Aksi Cepat</h3>
                    <div class="space-y-3">
                        <a href="{{ route('master-data.karyawan.create', ['cabang_id' => $cabang->hash_id]) }}"
                            class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors group">
                            <div
                                class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                                <i class="fas fa-user-plus text-brand-blue"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Tambah Karyawan</p>
                                <p class="text-sm text-gray-500">Tambah karyawan baru ke cabang ini</p>
                            </div>
                        </a>
                        <a href="{{ route('master-data.laporan-keuangan.create', ['cabang_id' => $cabang->hash_id]) }}"
                            class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors group">
                            <div
                                class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center group-hover:bg-green-200 transition-colors">
                                <i class="fas fa-file-invoice-dollar text-green-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Buat Laporan</p>
                                <p class="text-sm text-gray-500">Buat laporan keuangan cabang ini</p>
                            </div>
                        </a>
                        <button onclick="toggleStatus('{{ $cabang->hash_id }}')"
                            class="w-full flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors group text-left">
                            <div
                                class="w-10 h-10 rounded-lg {{ $cabang->is_active ? 'bg-red-100 group-hover:bg-red-200' : 'bg-green-100 group-hover:bg-green-200' }} flex items-center justify-center transition-colors">
                                <i
                                    class="fas {{ $cabang->is_active ? 'fa-ban text-red-600' : 'fa-check text-green-600' }}"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">
                                    {{ $cabang->is_active ? 'Nonaktifkan Cabang' : 'Aktifkan Cabang' }}</p>
                                <p class="text-sm text-gray-500">
                                    {{ $cabang->is_active ? 'Ubah status menjadi tidak aktif' : 'Ubah status menjadi aktif' }}
                                </p>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function toggleStatus(id) {
            SwalHelper.confirm(
                'Ubah Status',
                'Apakah Anda yakin ingin mengubah status cabang ini?',
                'Ya, Ubah'
            ).then((result) => {
                if (result.isConfirmed) {
                    fetch(`{{ url('master-data/cabang') }}/${id}/toggle-status`, {
                            method: 'POST',
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
                                setTimeout(() => location.reload(), 1500);
                            } else {
                                SwalHelper.error('Gagal!', data.message);
                            }
                        })
                        .catch(error => {
                            SwalHelper.error('Error!', 'Terjadi kesalahan saat mengubah status');
                        });
                }
            });
        }

        function deleteCabang(id, name) {
            SwalHelper.confirmDelete(`cabang "${name}"`).then((result) => {
                if (result.isConfirmed) {
                    fetch(`{{ url('master-data/cabang') }}/${id}`, {
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
                                    window.location.href = '{{ route('master-data.cabang.index') }}';
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
