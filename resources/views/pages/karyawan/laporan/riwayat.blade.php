@extends('layouts.app')

@section('title', 'Riwayat Input')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Riwayat Input Saya</h1>
                <p class="text-gray-600 mt-1">
                    @if ($karyawan)
                        {{ $karyawan->nama_lengkap }} - {{ $karyawan->cabang->nama_cabang ?? '-' }}
                    @else
                        Semua input Anda
                    @endif
                </p>
            </div>
            <a href="{{ route('karyawan.pemasukan.create') }}"
                class="px-4 py-2 bg-brand-orange text-white rounded-lg hover:bg-orange-600 flex items-center gap-2 w-fit">
                <i class="fas fa-plus"></i>
                <span>Input Baru</span>
            </a>
        </div>

        <!-- Filter -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <form action="{{ route('karyawan.laporan.riwayat') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1 grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            <option value="">Semua Status</option>
                            @foreach (['Draft', 'Pending', 'Approved', 'Rejected'] as $s)
                                <option value="{{ $s }}" {{ $status == $s ? 'selected' : '' }}>
                                    {{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                        <input type="date" name="dari" value="{{ $dari }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                        <input type="date" name="sampai" value="{{ $sampai }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="px-6 py-2 bg-brand-orange text-white font-medium rounded-lg hover:bg-orange-600">
                        <i class="fas fa-filter mr-2"></i> Filter
                    </button>
                    <a href="{{ route('karyawan.laporan.riwayat') }}"
                        class="px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300">
                        <i class="fas fa-undo"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Status Summary -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Draft</p>
                        <p class="text-2xl font-bold text-gray-700">{{ $statusCounts['Draft'] ?? 0 }}</p>
                    </div>
                    <div class="p-3 rounded-lg bg-gray-100">
                        <i class="fas fa-edit text-gray-500 text-lg"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Pending</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $statusCounts['Pending'] ?? 0 }}</p>
                    </div>
                    <div class="p-3 rounded-lg bg-yellow-100">
                        <i class="fas fa-clock text-yellow-500 text-lg"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Approved</p>
                        <p class="text-2xl font-bold text-green-600">{{ $statusCounts['Approved'] ?? 0 }}</p>
                    </div>
                    <div class="p-3 rounded-lg bg-green-100">
                        <i class="fas fa-check-circle text-green-500 text-lg"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Rejected</p>
                        <p class="text-2xl font-bold text-red-600">{{ $statusCounts['Rejected'] ?? 0 }}</p>
                    </div>
                    <div class="p-3 rounded-lg bg-red-100">
                        <i class="fas fa-times-circle text-red-500 text-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaction List -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Daftar Input</h3>
            </div>
            @if ($laporans->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kategori</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Keterangan</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jumlah</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($laporans as $laporan)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($laporan->tanggal)->translatedFormat('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $laporan->kategori }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($laporan->keterangan, 40) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-green-600">
                                        Rp {{ number_format($laporan->jumlah, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @php
                                            $statusColors = [
                                                'Draft' => 'bg-gray-100 text-gray-700',
                                                'Pending' => 'bg-yellow-100 text-yellow-700',
                                                'Approved' => 'bg-green-100 text-green-700',
                                                'Rejected' => 'bg-red-100 text-red-700',
                                            ];
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$laporan->status] ?? '' }}">
                                            {{ $laporan->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button type="button" onclick="showDetail({{ json_encode($laporan) }})"
                                                class="text-blue-600 hover:text-blue-800" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if ($laporan->status === 'Draft')
                                                <a href="{{ route('karyawan.laporan.edit', $laporan->hash_id) }}"
                                                    class="text-yellow-600 hover:text-yellow-800" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button"
                                                    onclick="submitForApproval('{{ $laporan->hash_id }}')"
                                                    class="text-green-600 hover:text-green-800" title="Ajukan Approval">
                                                    <i class="fas fa-paper-plane"></i>
                                                </button>
                                                <button type="button" onclick="deleteDraft('{{ $laporan->hash_id }}')"
                                                    class="text-red-600 hover:text-red-800" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($laporans->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $laporans->links() }}
                    </div>
                @endif
            @else
                <div class="p-12 text-center">
                    <div class="p-4 rounded-full bg-gray-100 inline-flex mb-4">
                        <i class="fas fa-inbox text-gray-400 text-3xl"></i>
                    </div>
                    <p class="text-gray-500 font-medium">Belum ada input</p>
                    <p class="text-gray-400 text-sm mt-1">Anda belum membuat input pemasukan</p>
                    <a href="{{ route('karyawan.pemasukan.create') }}"
                        class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-brand-orange text-white rounded-lg hover:bg-orange-600">
                        <i class="fas fa-plus"></i>
                        <span>Buat Input Pertama</span>
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Detail Modal -->
    <div id="detailModal" class="fixed inset-0 z-50 items-center justify-center p-4" style="display: none;">
        <!-- Background Overlay with Pattern -->
        <div class="absolute inset-0 bg-linear-to-br from-orange-500/90 to-orange-700/90">
            <div class="absolute inset-0"
                style="background-image: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 200 100%22><text x=%2250%%22 y=%2250%%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 font-family=%22Inter, sans-serif%22 font-size=%2214%22 font-weight=%22700%22 fill=%22rgba(255,255,255,0.15)%22 transform=%22rotate(-15, 100, 50)%22>Kebab Ikhwan</text></svg>');
                background-size: 200px 100px;
                background-repeat: repeat;">
            </div>
        </div>

        <!-- Modal Content -->
        <div class="bg-white rounded-xl shadow-xl max-w-lg w-full max-h-[90vh] overflow-y-auto scrollbar-hide relative z-10"
            style="-ms-overflow-style: none; scrollbar-width: none;">
            <style>
                .scrollbar-hide::-webkit-scrollbar {
                    display: none;
                }
            </style>
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white z-10">
                <h3 class="font-semibold text-gray-900">Detail Input</h3>
                <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="detailContent" class="p-6 space-y-4">
                <!-- Content will be filled by JS -->
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function showDetail(laporan) {
                const statusColors = {
                    'Draft': 'bg-gray-100 text-gray-700',
                    'Pending': 'bg-yellow-100 text-yellow-700',
                    'Approved': 'bg-green-100 text-green-700',
                    'Rejected': 'bg-red-100 text-red-700',
                };

                const content = `
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Tanggal</p>
                        <p class="font-medium text-gray-900">${new Date(laporan.tanggal).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusColors[laporan.status]}">${laporan.status}</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Kategori</p>
                        <p class="font-medium text-gray-900">${laporan.kategori}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Jumlah</p>
                        <p class="font-medium text-green-600">Rp ${Number(laporan.jumlah).toLocaleString('id-ID')}</p>
                    </div>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Keterangan</p>
                    <p class="font-medium text-gray-900">${laporan.keterangan || '-'}</p>
                </div>
                ${laporan.catatan ? `
                                                                                                <div>
                                                                                                    <p class="text-sm text-gray-500">Catatan</p>
                                                                                                    <p class="font-medium text-gray-900">${laporan.catatan}</p>
                                                                                                </div>
                                                                                                ` : ''}
                ${laporan.catatan_admin ? `
                                                                                                <div class="p-3 rounded-lg bg-blue-50 border border-blue-100">
                                                                                                    <p class="text-sm text-blue-600 font-medium">Catatan Admin</p>
                                                                                                    <p class="text-blue-800">${laporan.catatan_admin}</p>
                                                                                                </div>
                                                                                                ` : ''}
                ${laporan.bukti_transaksi ? `
                                                                                                <div>
                                                                                                    <p class="text-sm text-gray-500 mb-2">Bukti Transaksi</p>
                                                                                                    <img src="/storage/${laporan.bukti_transaksi}" alt="Bukti" class="w-full rounded-lg border">
                                                                                                </div>
                                                                                                ` : ''}
            `;

                document.getElementById('detailContent').innerHTML = content;
                document.getElementById('detailModal').style.display = 'flex';
            }

            function closeDetailModal() {
                document.getElementById('detailModal').style.display = 'none';
            }

            // Close modal on outside click
            document.getElementById('detailModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeDetailModal();
                }
            });

            // Close on Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeDetailModal();
                }
            });

            // Submit for approval
            async function submitForApproval(hashId) {
                const result = await Swal.fire({
                    title: 'Ajukan Approval?',
                    text: 'Laporan akan dikirim ke admin untuk disetujui',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#16a34a',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Ajukan',
                    cancelButtonText: 'Batal'
                });

                if (result.isConfirmed) {
                    try {
                        const response = await fetch(`/karyawan/laporan/${hashId}/submit`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: data.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire('Gagal', data.message, 'error');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        Swal.fire('Gagal', 'Terjadi kesalahan saat mengajukan approval', 'error');
                    }
                }
            }

            // Delete draft
            async function deleteDraft(hashId) {
                const result = await Swal.fire({
                    title: 'Hapus Draft?',
                    text: 'Draft yang dihapus tidak dapat dikembalikan',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal'
                });

                if (result.isConfirmed) {
                    try {
                        const response = await fetch(`/karyawan/laporan/${hashId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: data.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire('Gagal', data.message, 'error');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        Swal.fire('Gagal', 'Terjadi kesalahan saat menghapus draft', 'error');
                    }
                }
            }
        </script>
    @endpush
@endsection
