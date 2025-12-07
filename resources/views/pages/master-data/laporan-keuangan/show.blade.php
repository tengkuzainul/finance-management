@extends('layouts.app')

@section('title', 'Detail Laporan Keuangan')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('master-data.laporan-keuangan.index') }}"
                    class="w-10 h-10 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-600 hover:bg-slate-50 transition-colors">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Detail Laporan</h1>
                    <p class="text-gray-600 mt-1">{{ $laporanKeuangan->formatted_tanggal }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                @if ($laporanKeuangan->canEdit())
                    <a href="{{ route('master-data.laporan-keuangan.edit', $laporanKeuangan) }}"
                        class="inline-flex items-center px-4 py-2 bg-brand-orange text-white font-medium rounded-lg hover:bg-orange-600 transition-colors">
                        <i class="fas fa-edit mr-2"></i>
                        Edit
                    </a>
                @endif
                @if ($laporanKeuangan->canApprove() && auth()->user()->isAdmin())
                    <button onclick="approveLaporan('{{ $laporanKeuangan->hash_id }}')"
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-check mr-2"></i>
                        Approve
                    </button>
                    <button onclick="rejectLaporan('{{ $laporanKeuangan->hash_id }}')"
                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        Reject
                    </button>
                @endif
                @if ($laporanKeuangan->status == 'Draft')
                    <button onclick="submitForApproval('{{ $laporanKeuangan->hash_id }}')"
                        class="inline-flex items-center px-4 py-2 bg-brand-blue text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Ajukan Approval
                    </button>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Laporan Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 {{ $laporanKeuangan->jenis == 'Pemasukan' ? 'bg-green-50' : 'bg-red-50' }}">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-12 h-12 rounded-xl {{ $laporanKeuangan->jenis == 'Pemasukan' ? 'bg-green-100' : 'bg-red-100' }} flex items-center justify-center">
                                    <i
                                        class="fas {{ $laporanKeuangan->jenis == 'Pemasukan' ? 'fa-arrow-up text-green-600' : 'fa-arrow-down text-red-600' }} text-xl"></i>
                                </div>
                                <div>
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $laporanKeuangan->jenis_badge_class }}">
                                        {{ $laporanKeuangan->jenis }}
                                    </span>
                                    <p
                                        class="text-sm {{ $laporanKeuangan->jenis == 'Pemasukan' ? 'text-green-600' : 'text-red-600' }} mt-1">
                                        {{ $laporanKeuangan->kategori }}
                                    </p>
                                </div>
                            </div>
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $laporanKeuangan->status_badge_class }}">
                                {{ $laporanKeuangan->status }}
                            </span>
                        </div>
                    </div>

                    <div class="p-6">
                        <!-- Jumlah -->
                        <div class="text-center py-6 border-b border-gray-100">
                            <p class="text-sm text-gray-500 mb-2">Jumlah</p>
                            <p
                                class="text-4xl font-bold {{ $laporanKeuangan->jenis == 'Pemasukan' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $laporanKeuangan->jenis == 'Pemasukan' ? '+' : '-' }}
                                {{ $laporanKeuangan->formatted_jumlah }}
                            </p>
                        </div>

                        <!-- Details -->
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6 py-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tanggal Transaksi</dt>
                                <dd class="mt-1 text-gray-900 font-medium">{{ $laporanKeuangan->tanggal->format('d F Y') }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Cabang</dt>
                                <dd class="mt-1 text-gray-900">{{ $laporanKeuangan->cabang->nama_cabang ?? '-' }}</dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Keterangan</dt>
                                <dd class="mt-1 text-gray-900">{{ $laporanKeuangan->keterangan }}</dd>
                            </div>
                            @if ($laporanKeuangan->karyawan)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Karyawan Terkait</dt>
                                    <dd class="mt-1 text-gray-900">{{ $laporanKeuangan->karyawan->nama_lengkap }}</dd>
                                </div>
                            @endif
                            @if ($laporanKeuangan->catatan)
                                <div class="sm:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Catatan</dt>
                                    <dd class="mt-1 text-gray-900">{{ $laporanKeuangan->catatan }}</dd>
                                </div>
                            @endif
                        </dl>

                        @if ($laporanKeuangan->bukti_transaksi)
                            <div class="pt-6 border-t border-gray-100">
                                <h3 class="text-sm font-medium text-gray-500 mb-3">Bukti Transaksi</h3>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    @if (Str::endsWith($laporanKeuangan->bukti_transaksi, ['.jpg', '.jpeg', '.png', '.gif']))
                                        <img src="{{ Storage::url($laporanKeuangan->bukti_transaksi) }}"
                                            alt="Bukti Transaksi" class="max-w-full max-h-96 rounded-lg mx-auto">
                                    @else
                                        <div class="flex items-center justify-center gap-4 py-4">
                                            <i class="fas fa-file-pdf text-red-500 text-4xl"></i>
                                            <div>
                                                <p class="font-medium text-gray-900">Dokumen PDF</p>
                                                <a href="{{ Storage::url($laporanKeuangan->bukti_transaksi) }}"
                                                    target="_blank" class="text-brand-orange hover:text-orange-600">
                                                    Lihat Dokumen <i class="fas fa-external-link-alt ml-1"></i>
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Info Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="font-semibold text-gray-900">Informasi</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <p class="text-sm text-gray-500">Dibuat Oleh</p>
                            <p class="font-medium text-gray-900">{{ $laporanKeuangan->creator->name ?? '-' }}</p>
                            <p class="text-sm text-gray-500">{{ $laporanKeuangan->created_at->format('d M Y H:i') }}</p>
                        </div>
                        @if ($laporanKeuangan->approved_at)
                            <div>
                                <p class="text-sm text-gray-500">
                                    {{ $laporanKeuangan->status == 'Approved' ? 'Disetujui' : 'Ditolak' }} Oleh
                                </p>
                                <p class="font-medium text-gray-900">{{ $laporanKeuangan->approver->name ?? '-' }}</p>
                                <p class="text-sm text-gray-500">{{ $laporanKeuangan->approved_at->format('d M Y H:i') }}
                                </p>
                            </div>
                        @endif
                        <div>
                            <p class="text-sm text-gray-500">Terakhir Diupdate</p>
                            <p class="text-sm text-gray-900">{{ $laporanKeuangan->updated_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Status Timeline -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="font-semibold text-gray-900">Status</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @php
                                $statuses = ['Draft', 'Pending', 'Approved'];
                                $currentIndex = array_search($laporanKeuangan->status, $statuses);
                                if ($laporanKeuangan->status == 'Rejected') {
                                    $currentIndex = 1; // Rejected comes after Pending
                                }
                            @endphp
                            @foreach ($statuses as $index => $status)
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-full flex items-center justify-center 
                                {{ $index <= $currentIndex ? ($laporanKeuangan->status == 'Rejected' && $index == 2 ? 'bg-red-100' : 'bg-green-100') : 'bg-gray-100' }}">
                                        @if ($laporanKeuangan->status == 'Rejected' && $status == 'Approved')
                                            <i class="fas fa-times text-red-600"></i>
                                        @elseif($index <= $currentIndex)
                                            <i class="fas fa-check text-green-600"></i>
                                        @else
                                            <span class="text-gray-400">{{ $index + 1 }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <p
                                            class="font-medium {{ $index <= $currentIndex ? 'text-gray-900' : 'text-gray-400' }}">
                                            @if ($laporanKeuangan->status == 'Rejected' && $status == 'Approved')
                                                Rejected
                                            @else
                                                {{ $status }}
                                            @endif
                                        </p>
                                        @if ($status == 'Draft')
                                            <p class="text-xs text-gray-500">Laporan dibuat</p>
                                        @elseif($status == 'Pending')
                                            <p class="text-xs text-gray-500">Menunggu persetujuan</p>
                                        @else
                                            <p class="text-xs text-gray-500">
                                                {{ $laporanKeuangan->status == 'Rejected' ? 'Laporan ditolak' : 'Laporan disetujui' }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                @if ($index < count($statuses) - 1)
                                    <div
                                        class="ml-4 w-0.5 h-4 {{ $index < $currentIndex ? 'bg-green-200' : 'bg-gray-200' }}">
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function approveLaporan(id) {
            SwalHelper.confirm(
                'Approve Laporan',
                'Apakah Anda yakin ingin menyetujui laporan ini?',
                'Ya, Approve',
                'success'
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

        function submitForApproval(id) {
            SwalHelper.confirm(
                'Ajukan Approval',
                'Apakah Anda yakin ingin mengajukan laporan ini untuk di-approve?',
                'Ya, Ajukan'
            ).then((result) => {
                if (result.isConfirmed) {
                    fetch(`{{ url('master-data/laporan-keuangan') }}/${id}/submit-approval`, {
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
    </script>
@endpush
