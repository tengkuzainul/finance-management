@extends('layouts.app')

@section('title', 'Data Penggajian')

@section('page-header')
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-800">Data Penggajian</h1>
            <p class="text-slate-500 mt-1">Kelola data gaji karyawan berdasarkan Pendapatan harian</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="openGenerateModal()"
                class="px-4 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-blue-600 hover:to-blue-700 shadow-lg shadow-blue-500/30 transition-all duration-200">
                <i class="fas fa-calculator mr-2"></i>
                Generate Gaji
            </button>
            <a href="{{ route('gaji.rekap-pdf', request()->query()) }}" target="_blank"
                class="px-4 py-2.5 bg-gradient-to-r from-red-500 to-red-600 text-white text-sm font-medium rounded-xl hover:from-red-600 hover:to-red-700 shadow-lg shadow-red-500/30 transition-all duration-200">
                <i class="fas fa-file-pdf mr-2"></i>
                Export Rekap PDF
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-file-invoice-dollar text-blue-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Total Record</p>
                        <p class="text-xl font-bold text-slate-800">{{ number_format($summary['count_records']) }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-money-bill-wave text-emerald-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Total Gaji</p>
                        <p class="text-xl font-bold text-slate-800">Rp
                            {{ number_format($summary['total_gaji'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Pending</p>
                        <p class="text-xl font-bold text-yellow-600">Rp
                            {{ number_format($summary['total_pending'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Dibayar</p>
                        <p class="text-xl font-bold text-green-600">Rp
                            {{ number_format($summary['total_paid'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <form method="GET" action="{{ route('gaji.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Cabang</label>
                    <select name="cabang"
                        class="w-full px-4 py-2.5 bg-slate-100 border-0 rounded-xl text-slate-700 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:bg-white">
                        <option value="">Semua Cabang</option>
                        @foreach ($cabangs as $cabang)
                            <option value="{{ $cabang->hashid }}"
                                {{ request('cabang') == $cabang->hashid ? 'selected' : '' }}>{{ $cabang->nama_cabang }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Karyawan</label>
                    <select name="karyawan"
                        class="w-full px-4 py-2.5 bg-slate-100 border-0 rounded-xl text-slate-700 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:bg-white">
                        <option value="">Semua Karyawan</option>
                        @foreach ($karyawans as $karyawan)
                            <option value="{{ $karyawan->hashid }}"
                                {{ request('karyawan') == $karyawan->hashid ? 'selected' : '' }}>
                                {{ $karyawan->nama_lengkap }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Status</label>
                    <select name="status"
                        class="w-full px-4 py-2.5 bg-slate-100 border-0 rounded-xl text-slate-700 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:bg-white">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Dibayar</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan
                        </option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Bulan</label>
                    <input type="month" name="bulan" value="{{ request('bulan') }}"
                        class="w-full px-4 py-2.5 bg-slate-100 border-0 rounded-xl text-slate-700 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:bg-white">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="flex-1 px-4 py-2.5 bg-orange-500 text-white rounded-xl hover:bg-orange-600 transition-colors">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                    <a href="{{ route('gaji.index') }}"
                        class="px-4 py-2.5 bg-slate-200 text-slate-700 rounded-xl hover:bg-slate-300 transition-colors">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Data Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                <input type="checkbox" id="select-all" class="rounded border-slate-300">
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Tanggal</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Karyawan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Cabang</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Total Pendapatan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Persen</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Nominal Gaji</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($gajis as $gaji)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <input type="checkbox" name="gaji_ids[]" value="{{ $gaji->hashid }}"
                                        class="gaji-checkbox rounded border-slate-300">
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-medium text-slate-800">{{ $gaji->tanggal->format('d M Y') }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                                            @if ($gaji->karyawan->user && $gaji->karyawan->user->avatar)
                                                <img src="{{ asset('storage/' . $gaji->karyawan->user->avatar) }}"
                                                    class="w-10 h-10 rounded-full object-cover">
                                            @else
                                                <span
                                                    class="text-orange-600 font-semibold">{{ substr($gaji->karyawan->nama_lengkap ?? 'K', 0, 1) }}</span>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-medium text-slate-800">
                                                {{ $gaji->karyawan->nama_lengkap ?? '-' }}</p>
                                            <p class="text-xs text-slate-500">{{ $gaji->jumlah_transaksi }} transaksi</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-slate-600">{{ $gaji->cabang->nama_cabang ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-slate-800 font-medium">Rp
                                        {{ number_format($gaji->total_pemasukan, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-slate-600">{{ $gaji->persen_gaji }}%</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-emerald-600 font-bold">Rp
                                        {{ number_format($gaji->nominal_gaji, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @php $badge = $gaji->status_badge; @endphp
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                        {{ $badge['color'] === 'green' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $badge['color'] === 'yellow' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                        {{ $badge['color'] === 'red' ? 'bg-red-100 text-red-700' : '' }}">
                                        {{ $badge['text'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('gaji.show', $gaji->hashid) }}"
                                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                            title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('gaji.slip-pdf', $gaji->hashid) }}" target="_blank"
                                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                            title="Download Slip">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                        @if ($gaji->status === 'pending')
                                            <form action="{{ route('gaji.mark-paid', $gaji->hashid) }}" method="POST"
                                                class="inline mark-paid-form">
                                                @csrf
                                                <button type="button"
                                                    class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors btn-mark-paid"
                                                    title="Tandai Dibayar">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                                            <i class="fas fa-money-bill-wave text-slate-400 text-2xl"></i>
                                        </div>
                                        <p class="text-slate-500 font-medium">Belum ada data gaji</p>
                                        <p class="text-sm text-slate-400 mt-1">Generate gaji untuk melihat data</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($gajis->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $gajis->links() }}
                </div>
            @endif
        </div>

        <!-- Batch Action -->
        <div id="batch-action"
            class="hidden fixed bottom-6 left-1/2 transform -translate-x-1/2 bg-slate-800 text-white px-6 py-3 rounded-xl shadow-xl flex items-center gap-4">
            <span><span id="selected-count">0</span> item dipilih</span>
            <form action="{{ route('gaji.batch-mark-paid') }}" method="POST" id="batch-form">
                @csrf
                <div id="batch-inputs"></div>
                <button type="submit" class="px-4 py-2 bg-green-500 rounded-lg hover:bg-green-600 transition-colors">
                    <i class="fas fa-check mr-2"></i>Tandai Dibayar
                </button>
            </form>
        </div>
    </div>

    <!-- Generate Modal -->
    <div id="generate-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4">
            <div class="p-6 border-b border-slate-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-slate-800">Generate Gaji</h3>
                    <button onclick="closeGenerateModal()" class="p-2 text-slate-400 hover:text-slate-600 rounded-lg">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <form action="{{ route('gaji.generate') }}" method="POST" id="generate-form" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Tanggal <span
                            class="text-red-500">*</span></label>
                    <input type="date" name="tanggal" value="{{ now()->format('Y-m-d') }}" required
                        class="w-full px-4 py-3 bg-slate-100 border-0 rounded-xl text-slate-700 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:bg-white">
                    <p class="text-xs text-slate-500 mt-2">Generate gaji untuk tanggal tersebut berdasarkan laporan yang
                        sudah di-approve</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Cabang <span
                            class="text-slate-400">(Opsional)</span></label>
                    <select name="cabang_id"
                        class="w-full px-4 py-3 bg-slate-100 border-0 rounded-xl text-slate-700 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:bg-white">
                        <option value="">Semua Cabang</option>
                        @foreach ($cabangs as $cabang)
                            <option value="{{ $cabang->hashid }}">{{ $cabang->nama_cabang }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="p-4 bg-blue-50 rounded-xl">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                        <div class="text-sm text-blue-700">
                            <p class="font-medium">Persentase gaji saat ini:
                                {{ \App\Models\Pengaturan::getValue('persen_gaji', 13) }}%</p>
                            <p class="mt-1 text-blue-600">Dapat diubah di menu Pengaturan → Konfigurasi Gaji</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" onclick="closeGenerateModal()"
                        class="px-6 py-2.5 bg-slate-100 text-slate-700 font-medium rounded-xl hover:bg-slate-200 transition-colors">
                        Batal
                    </button>
                    <button type="submit" id="btn-generate"
                        class="px-6 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium rounded-xl hover:from-blue-600 hover:to-blue-700 shadow-lg shadow-blue-500/30 transition-all duration-200">
                        <i class="fas fa-calculator mr-2" id="generate-icon"></i>
                        <span id="generate-text">Generate</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function openGenerateModal() {
            document.getElementById('generate-modal').classList.remove('hidden');
            document.getElementById('generate-modal').classList.add('flex');
        }

        function closeGenerateModal() {
            document.getElementById('generate-modal').classList.add('hidden');
            document.getElementById('generate-modal').classList.remove('flex');
        }

        // Generate form with loading
        document.getElementById('generate-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const btn = document.getElementById('btn-generate');
            const icon = document.getElementById('generate-icon');
            const text = document.getElementById('generate-text');

            Swal.fire({
                title: 'Generate Gaji?',
                text: 'Gaji akan dihitung berdasarkan laporan yang sudah di-approve',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Generate!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    btn.disabled = true;
                    icon.className = 'fas fa-spinner fa-spin mr-2';
                    text.textContent = 'Memproses...';

                    form.submit();
                }
            });
        });

        // Mark as paid with SweetAlert
        document.querySelectorAll('.btn-mark-paid').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const form = this.closest('form');

                Swal.fire({
                    title: 'Tandai Dibayar?',
                    text: 'Gaji ini akan ditandai sebagai sudah dibayar',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#22c55e',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Tandai!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // Batch selection
        document.addEventListener('DOMContentLoaded', function() {
            const selectAll = document.getElementById('select-all');
            const checkboxes = document.querySelectorAll('.gaji-checkbox');
            const batchAction = document.getElementById('batch-action');
            const selectedCount = document.getElementById('selected-count');
            const batchInputs = document.getElementById('batch-inputs');

            selectAll?.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = this.checked);
                updateBatchUI();
            });

            checkboxes.forEach(cb => {
                cb.addEventListener('change', updateBatchUI);
            });

            function updateBatchUI() {
                const checked = document.querySelectorAll('.gaji-checkbox:checked');
                selectedCount.textContent = checked.length;

                if (checked.length > 0) {
                    batchAction.classList.remove('hidden');
                    batchAction.classList.add('flex');

                    batchInputs.innerHTML = '';
                    checked.forEach(cb => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'gaji_ids[]';
                        input.value = cb.value;
                        batchInputs.appendChild(input);
                    });
                } else {
                    batchAction.classList.add('hidden');
                    batchAction.classList.remove('flex');
                }
            }

            // Show success alert if there's a session message
            @if (session('success'))
                Swal.fire({
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    icon: 'success',
                    confirmButtonColor: '#f97316'
                });
            @endif
        });

        // Close modal on escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeGenerateModal();
        });
    </script>
@endpush

