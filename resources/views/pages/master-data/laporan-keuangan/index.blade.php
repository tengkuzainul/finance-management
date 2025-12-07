@extends('layouts.app')

@section('title', 'Manajemen Laporan Keuangan')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Laporan Keuangan</h1>
                <p class="text-gray-600 mt-1">Kelola laporan pemasukan dan pengeluaran</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('master-data.laporan-keuangan.create', ['jenis' => Hashids::encode(1)]) }}"
                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors shadow-md">
                    <i class="fas fa-plus mr-2"></i>
                    Pemasukan
                </a>
                <a href="{{ route('master-data.laporan-keuangan.create', ['jenis' => Hashids::encode(2)]) }}"
                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-colors shadow-md">
                    <i class="fas fa-minus mr-2"></i>
                    Pengeluaran
                </a>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-green-100">
                        <i class="fas fa-arrow-up text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Pemasukan</p>
                        <p class="text-xl font-bold text-green-600">Rp
                            {{ number_format($summary['total_pemasukan'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-red-100">
                        <i class="fas fa-arrow-down text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Pengeluaran</p>
                        <p class="text-xl font-bold text-red-600">Rp
                            {{ number_format($summary['total_pengeluaran'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg {{ $summary['saldo'] >= 0 ? 'bg-blue-100' : 'bg-orange-100' }}">
                        <i
                            class="fas fa-wallet {{ $summary['saldo'] >= 0 ? 'text-brand-blue' : 'text-brand-orange' }} text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Saldo</p>
                        <p class="text-xl font-bold {{ $summary['saldo'] >= 0 ? 'text-brand-blue' : 'text-brand-orange' }}">
                            Rp {{ number_format($summary['saldo'], 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-yellow-100">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Menunggu Approval</p>
                        <p class="text-xl font-bold text-yellow-600">{{ $summary['pending_count'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Search -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <form action="{{ route('master-data.laporan-keuangan.index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Keterangan atau kategori..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-orange focus:border-brand-orange">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cabang</label>
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
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis</label>
                        <select name="jenis"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-orange focus:border-brand-orange">
                            <option value="">Semua Jenis</option>
                            <option value="Pemasukan" {{ request('jenis') == 'Pemasukan' ? 'selected' : '' }}>Pemasukan
                            </option>
                            <option value="Pengeluaran" {{ request('jenis') == 'Pengeluaran' ? 'selected' : '' }}>
                                Pengeluaran</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-orange focus:border-brand-orange">
                            <option value="">Semua Status</option>
                            <option value="Draft" {{ request('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved
                            </option>
                            <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected
                            </option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-orange focus:border-brand-orange">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                        <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-orange focus:border-brand-orange">
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="flex-1 px-6 py-2 bg-brand-blue text-blue-700 border border-blue-700 hover:text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-filter mr-2"></i>Filter
                        </button>
                        @if (request()->hasAny(['search', 'cabang_id', 'jenis', 'status', 'tanggal_mulai', 'tanggal_akhir']))
                            <a href="{{ route('master-data.laporan-keuangan.index') }}"
                                class="px-6 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                        <a href="{{ route('master-data.laporan-keuangan.export-pdf', request()->query()) }}"
                            class="px-6 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors"
                            title="Export PDF">
                            <i class="fas fa-file-pdf"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Tanggal</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Keterangan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Cabang</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Jenis</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Jumlah</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($laporans as $laporan)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $laporan->tanggal->format('d M Y') }}</p>
                                        <p class="text-sm text-gray-500">{{ $laporan->tanggal->format('l') }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-medium text-gray-900 line-clamp-1">{{ $laporan->keterangan }}</p>
                                        <p class="text-sm text-gray-500">{{ $laporan->kategori }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($laporan->cabang)
                                        <span class="text-gray-900">{{ $laporan->cabang->nama_cabang }}</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $laporan->jenis_badge_class }}">
                                        <i
                                            class="fas {{ $laporan->jenis == 'Pemasukan' ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                                        {{ $laporan->jenis }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span
                                        class="font-semibold {{ $laporan->jenis == 'Pemasukan' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $laporan->jenis == 'Pemasukan' ? '+' : '-' }} {{ $laporan->formatted_jumlah }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $laporan->status_badge_class }}">
                                        {{ $laporan->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('master-data.laporan-keuangan.show', $laporan) }}"
                                            class="p-2 text-gray-500 hover:text-brand-blue hover:bg-blue-50 rounded-lg transition-colors"
                                            title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if ($laporan->canEdit())
                                            <a href="{{ route('master-data.laporan-keuangan.edit', $laporan) }}"
                                                class="p-2 text-gray-500 hover:text-brand-orange hover:bg-orange-50 rounded-lg transition-colors"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                        @if ($laporan->canApprove() && auth()->user()->isAdmin())
                                            <button onclick="approveLaporan('{{ $laporan->hash_id }}')"
                                                class="p-2 text-gray-500 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors"
                                                title="Approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button onclick="rejectLaporan('{{ $laporan->hash_id }}')"
                                                class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                title="Reject">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                        @if ($laporan->status != 'Approved')
                                            <button onclick="deleteLaporan('{{ $laporan->hash_id }}')"
                                                class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="p-4 rounded-full bg-gray-100 mb-4">
                                            <i class="fas fa-file-invoice-dollar text-gray-400 text-3xl"></i>
                                        </div>
                                        <p class="text-gray-500 font-medium">Belum ada laporan keuangan</p>
                                        <p class="text-gray-400 text-sm mt-1">Mulai catat pemasukan dan pengeluaran Anda
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($laporans->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $laporans->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function approveLaporan(id) {
            SwalHelper.confirm(
                'Approve Laporan',
                'Apakah Anda yakin ingin menyetujui laporan ini?',
                'Ya, Approve'
            ).then((result) => {
                if (result.isConfirmed) {
                    fetch(`{{ url('master-data/laporan-keuangan') }}/${id}/approve`, {
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
                            SwalHelper.error('Error!', 'Terjadi kesalahan');
                        });
                }
            });
        }

        function rejectLaporan(id) {
            Swal.fire({
                title: 'Reject Laporan',
                html: `
            <p class="mb-4">Masukkan alasan penolakan:</p>
            <textarea id="rejectReason" class="w-full p-3 border border-gray-300 rounded-lg" rows="3" placeholder="Alasan penolakan..."></textarea>
        `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Reject',
                cancelButtonText: 'Batal',
                preConfirm: () => {
                    return document.getElementById('rejectReason').value;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`{{ url('master-data/laporan-keuangan') }}/${id}/reject`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                catatan: result.value
                            })
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
                            SwalHelper.error('Error!', 'Terjadi kesalahan');
                        });
                }
            });
        }

        function deleteLaporan(id) {
            SwalHelper.confirmDelete('laporan ini').then((result) => {
                if (result.isConfirmed) {
                    fetch(`{{ url('master-data/laporan-keuangan') }}/${id}`, {
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
