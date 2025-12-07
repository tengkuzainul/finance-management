@extends('layouts.app')

@section('title', 'Manajemen Cabang')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Manajemen Cabang</h1>
                <p class="text-gray-600 mt-1">Kelola data cabang/lokasi UMKM Kebab Ikhwan</p>
            </div>
            <a href="{{ route('master-data.cabang.create') }}"
                class="inline-flex items-center justify-center px-4 py-2 bg-transparent text-orange-600 font-semibold rounded-lg hover:bg-orange-600 hover:text-white transition-colors border-orange-600 border">
                <i class="fas fa-plus mr-2"></i>
                Tambah Cabang
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-blue-100">
                        <i class="fas fa-store text-brand-blue text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Cabang</p>
                        <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Cabang::count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-green-100">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Cabang Aktif</p>
                        <p class="text-2xl font-bold text-green-600">{{ \App\Models\Cabang::active()->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-orange-100">
                        <i class="fas fa-users text-brand-orange text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Karyawan</p>
                        <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Cabang::sum('jumlah_karyawan') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Search -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <form action="{{ route('master-data.cabang.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama cabang, kode, atau alamat..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-orange focus:border-brand-orange">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>
                <div class="sm:w-48">
                    <select name="status"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-orange focus:border-brand-orange">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif
                        </option>
                    </select>
                </div>
                <button type="submit"
                    class="px-6 py-2 bg-brand-blue text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
                @if (request('search') || request('status'))
                    <a href="{{ route('master-data.cabang.index') }}"
                        class="px-6 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors text-center">
                        <i class="fas fa-times mr-2"></i>Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Cabang</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Kontak</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Karyawan</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($cabangs as $cabang)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-orange-600/10 text-orange-600 font-bold text-sm">
                                                {{ substr($cabang->kode_cabang, -2) }}
                                            </span>
                                            <div>
                                                <p class="font-semibold text-gray-900">{{ $cabang->nama_cabang }}</p>
                                                <p class="text-sm text-gray-500">{{ $cabang->kode_cabang }}</p>
                                            </div>
                                        </div>
                                        <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $cabang->alamat_lengkap }}
                                        </p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        @if ($cabang->no_telepon)
                                            <p class="text-sm text-gray-600">
                                                <i class="fas fa-phone text-gray-400 w-4"></i> {{ $cabang->no_telepon }}
                                            </p>
                                        @endif
                                        @if ($cabang->email)
                                            <p class="text-sm text-gray-600">
                                                <i class="fas fa-envelope text-gray-400 w-4"></i> {{ $cabang->email }}
                                            </p>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center justify-center min-w-8 h-8 px-3 rounded-full bg-blue-100 text-brand-blue font-semibold">
                                        {{ $cabang->jumlah_karyawan }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button onclick="toggleStatus('{{ $cabang->hash_id }}')"
                                        class="status-badge-{{ $cabang->id }} inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold cursor-pointer transition-colors
                                    {{ $cabang->is_active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-red-100 text-red-700 hover:bg-red-200' }}">
                                        <span
                                            class="w-2 h-2 rounded-full mr-2 {{ $cabang->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                        {{ $cabang->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </button>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('master-data.cabang.show', $cabang) }}"
                                            class="p-2 text-gray-500 hover:text-brand-blue hover:bg-blue-50 rounded-lg transition-colors"
                                            title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('master-data.cabang.edit', $cabang) }}"
                                            class="p-2 text-gray-500 hover:text-brand-orange hover:bg-orange-50 rounded-lg transition-colors"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button
                                            onclick="deleteCabang('{{ $cabang->hash_id }}', '{{ $cabang->nama_cabang }}')"
                                            class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                            title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="p-4 rounded-full bg-gray-100 mb-4">
                                            <i class="fas fa-store text-gray-400 text-3xl"></i>
                                        </div>
                                        <p class="text-gray-500 font-medium">Belum ada data cabang</p>
                                        <p class="text-gray-400 text-sm mt-1">Klik tombol "Tambah Cabang" untuk menambahkan
                                            cabang baru</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($cabangs->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $cabangs->links() }}
                </div>
            @endif
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
                                setTimeout(() => location.reload(), 1500);
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
