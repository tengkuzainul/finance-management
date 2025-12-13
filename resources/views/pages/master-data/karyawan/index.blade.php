@extends('layouts.app')

@section('title', 'Manajemen Karyawan')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Manajemen Karyawan</h1>
                <p class="text-gray-600 mt-1">Kelola data karyawan UMKM Kebab Ikhwan</p>
            </div>
            <a href="{{ route('master-data.karyawan.create') }}"
                class="inline-flex items-center justify-center px-4 py-2 bg-transparent text-orange-600 font-semibold rounded-lg hover:bg-orange-600 hover:text-white transition-colors border-orange-600 border">
                <i class="fas fa-plus mr-2"></i>
                Tambah Karyawan
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-blue-100">
                        <i class="fas fa-users text-brand-blue text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Karyawan</p>
                        <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Karyawan::count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-green-100">
                        <i class="fas fa-user-check text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Karyawan Aktif</p>
                        <p class="text-2xl font-bold text-green-600">{{ \App\Models\Karyawan::active()->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-red-100">
                        <i class="fas fa-user-slash text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Tidak Aktif</p>
                        <p class="text-2xl font-bold text-red-600">
                            {{ \App\Models\Karyawan::where('is_active', false)->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Search -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <form action="{{ route('master-data.karyawan.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama, NIK, atau email..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-orange focus:border-brand-orange">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>
                <div class="sm:w-60">
                    <select name="cabang_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-orange focus:border-brand-orange">
                        <option value="">Semua Cabang</option>
                        @foreach ($cabangs as $cabang)
                            <option value="{{ $cabang->hash_id }}"
                                {{ request('cabang_id') == $cabang->hash_id ? 'selected' : '' }}>
                                {{ $cabang->nama_cabang }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:w-60">
                    <select name="status"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-orange focus:border-brand-orange">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif
                        </option>
                    </select>
                </div>
                <button type="submit"
                    class="px-6 py-2 bg-transparent text-blue-700 border border-blue-700 hover:text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
                @if (request()->hasAny(['search', 'cabang_id', 'status']))
                    <a href="{{ route('master-data.karyawan.index') }}"
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
                                Karyawan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Cabang</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Kontak</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($karyawans as $karyawan)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-12 h-12 rounded-full bg-orange-600/10 flex items-center justify-center shrink-0 overflow-hidden">
                                            @if ($karyawan->user && $karyawan->user->avatar)
                                                <img src="{{ asset('storage/' . $karyawan->user->avatar) }}"
                                                    alt="{{ $karyawan->nama_lengkap }}"
                                                    class="w-12 h-12 rounded-full object-cover">
                                            @elseif ($karyawan->foto)
                                                <img src="{{ Storage::url($karyawan->foto) }}"
                                                    alt="{{ $karyawan->nama_lengkap }}"
                                                    class="w-12 h-12 rounded-full object-cover">
                                            @else
                                                <span
                                                    class="text-orange-600 font-bold text-lg">{{ substr($karyawan->nama_lengkap, 0, 1) }}</span>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $karyawan->nama_lengkap }}</p>
                                            <p class="text-sm text-gray-500">{{ $karyawan->nik }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
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
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        @if ($karyawan->no_telepon)
                                            <p class="text-sm text-gray-600">
                                                <i class="fas fa-phone text-gray-400 w-4"></i> {{ $karyawan->no_telepon }}
                                            </p>
                                        @endif
                                        @if ($karyawan->email)
                                            <p class="text-sm text-gray-600">
                                                <i class="fas fa-envelope text-gray-400 w-4"></i> {{ $karyawan->email }}
                                            </p>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button onclick="toggleStatus('{{ $karyawan->hash_id }}')"
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold cursor-pointer transition-colors
                                    {{ $karyawan->is_active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-red-100 text-red-700 hover:bg-red-200' }}">
                                        <span
                                            class="w-2 h-2 rounded-full mr-2 {{ $karyawan->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                        {{ $karyawan->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </button>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('master-data.karyawan.show', $karyawan) }}"
                                            class="p-2 text-gray-500 hover:text-brand-blue hover:bg-blue-50 rounded-lg transition-colors"
                                            title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('master-data.karyawan.edit', $karyawan) }}"
                                            class="p-2 text-gray-500 hover:text-brand-orange hover:bg-orange-50 rounded-lg transition-colors"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button
                                            onclick="deleteKaryawan('{{ $karyawan->hash_id }}', '{{ $karyawan->nama_lengkap }}')"
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
                                            <i class="fas fa-users text-gray-400 text-3xl"></i>
                                        </div>
                                        <p class="text-gray-500 font-medium">Belum ada data karyawan</p>
                                        <p class="text-gray-400 text-sm mt-1">Klik tombol "Tambah Karyawan" untuk
                                            menambahkan karyawan baru</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($karyawans->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $karyawans->links() }}
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
                'Apakah Anda yakin ingin mengubah status karyawan ini?',
                'Ya, Ubah'
            ).then((result) => {
                if (result.isConfirmed) {
                    fetch(`{{ url('master-data/karyawan') }}/${id}/toggle-status`, {
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

        function deleteKaryawan(id, name) {
            SwalHelper.confirmDelete(`karyawan "${name}"`).then((result) => {
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
