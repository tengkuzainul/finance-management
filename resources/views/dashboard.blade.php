@extends('layouts.app')

@section('title', 'Dashboard')

@section('page-header')
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-800">Dashboard</h1>
            <p class="text-slate-500 mt-1">Selamat datang kembali, {{ auth()->user()->name ?? 'User' }}! 👋</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-600">
                <i class="fas fa-calendar-alt mr-2"></i>{{ now()->translatedFormat('F Y') }}
            </span>
            @if ($stats['pending_count'] > 0)
                <a href="{{ route('master-data.laporan-keuangan.index', ['status' => 'Pending']) }}"
                    class="flex items-center gap-2 px-4 py-2.5 bg-yellow-100 text-yellow-700 text-sm font-medium rounded-xl hover:bg-yellow-200 transition-colors">
                    <i class="fas fa-clock"></i>
                    <span>{{ $stats['pending_count'] }} Pending</span>
                </a>
            @endif
        </div>
    </div>
@endsection

@section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
        <!-- Total Pemasukan -->
        <div
            class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg hover:shadow-slate-200/50 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-arrow-down text-green-600 text-lg"></i>
                </div>
                @if ($stats['perubahan_pemasukan'] != 0)
                    <span
                        class="px-2.5 py-1 {{ $stats['perubahan_pemasukan'] >= 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} text-xs font-medium rounded-lg flex items-center gap-1">
                        <i
                            class="fas {{ $stats['perubahan_pemasukan'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} text-[10px]"></i>
                        {{ abs($stats['perubahan_pemasukan']) }}%
                    </span>
                @endif
            </div>
            <h3 class="text-slate-500 text-sm font-medium mb-1">Total Pemasukan</h3>
            <p class="text-2xl font-bold text-slate-800">Rp {{ number_format($stats['total_pemasukan'], 0, ',', '.') }}</p>
            <p class="text-xs text-slate-400 mt-2">Bulan ini</p>
        </div>

        <!-- Total Pengeluaran -->
        <div
            class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg hover:shadow-slate-200/50 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-arrow-up text-red-600 text-lg"></i>
                </div>
                @if ($stats['perubahan_pengeluaran'] != 0)
                    <span
                        class="px-2.5 py-1 {{ $stats['perubahan_pengeluaran'] <= 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} text-xs font-medium rounded-lg flex items-center gap-1">
                        <i
                            class="fas {{ $stats['perubahan_pengeluaran'] <= 0 ? 'fa-arrow-down' : 'fa-arrow-up' }} text-[10px]"></i>
                        {{ abs($stats['perubahan_pengeluaran']) }}%
                    </span>
                @endif
            </div>
            <h3 class="text-slate-500 text-sm font-medium mb-1">Total Pengeluaran</h3>
            <p class="text-2xl font-bold text-slate-800">Rp {{ number_format($stats['total_pengeluaran'], 0, ',', '.') }}
            </p>
            <p class="text-xs text-slate-400 mt-2">Termasuk gaji: Rp {{ number_format($stats['total_gaji'], 0, ',', '.') }}
            </p>
        </div>

        <!-- Saldo/Profit -->
        <div
            class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg hover:shadow-slate-200/50 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div
                    class="w-12 h-12 {{ $stats['profit_bersih'] >= 0 ? 'bg-blue-100' : 'bg-orange-100' }} rounded-xl flex items-center justify-center">
                    <i
                        class="fas fa-wallet {{ $stats['profit_bersih'] >= 0 ? 'text-blue-600' : 'text-orange-600' }} text-lg"></i>
                </div>
                @if ($stats['perubahan_profit'] != 0)
                    <span
                        class="px-2.5 py-1 {{ $stats['perubahan_profit'] >= 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} text-xs font-medium rounded-lg flex items-center gap-1">
                        <i
                            class="fas {{ $stats['perubahan_profit'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} text-[10px]"></i>
                        {{ abs($stats['perubahan_profit']) }}%
                    </span>
                @endif
            </div>
            <h3 class="text-slate-500 text-sm font-medium mb-1">Profit Bersih</h3>
            <p class="text-2xl font-bold {{ $stats['profit_bersih'] >= 0 ? 'text-slate-800' : 'text-red-600' }}">
                Rp {{ number_format($stats['profit_bersih'], 0, ',', '.') }}
            </p>
            <p class="text-xs text-slate-400 mt-2">Setelah dikurangi gaji</p>
        </div>

        <!-- Total Transaksi -->
        <div
            class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg hover:shadow-slate-200/50 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-receipt text-orange-600 text-lg"></i>
                </div>
            </div>
            <h3 class="text-slate-500 text-sm font-medium mb-1">Total Transaksi</h3>
            <p class="text-2xl font-bold text-slate-800">{{ number_format($stats['total_transaksi']) }}</p>
            <p class="text-xs text-slate-400 mt-2">{{ $stats['total_cabang'] }} cabang &bull;
                {{ $stats['total_karyawan'] }} karyawan</p>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Main Chart -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Grafik Keuangan</h2>
                    <p class="text-sm text-slate-500">Pemasukan vs Pengeluaran (7 hari terakhir)</p>
                </div>
            </div>
            <!-- Chart -->
            <div class="h-72">
                <canvas id="chartKeuangan"></canvas>
            </div>
            <!-- Legend -->
            <div class="flex items-center justify-center gap-6 mt-4">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                    <span class="text-sm text-slate-600">Pemasukan</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                    <span class="text-sm text-slate-600">Pengeluaran</span>
                </div>
            </div>
        </div>

        <!-- Stats per Cabang -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <div class="mb-6">
                <h2 class="text-lg font-bold text-slate-800">Performa Cabang</h2>
                <p class="text-sm text-slate-500">Bulan ini</p>
            </div>
            <div class="space-y-4 max-h-72 overflow-y-auto">
                @forelse($cabangStats as $cabang)
                    <div class="p-3 bg-slate-50 rounded-xl">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="font-medium text-slate-800 text-sm">{{ $cabang->nama_cabang }}</h3>
                            <span class="text-xs text-slate-500">{{ $cabang->transaksi_count ?? 0 }} transaksi</span>
                        </div>
                        <div class="flex items-center gap-4 text-xs">
                            <span class="text-green-600">
                                <i class="fas fa-arrow-down mr-1"></i>
                                Rp {{ number_format($cabang->pemasukan ?? 0, 0, ',', '.') }}
                            </span>
                            <span class="text-red-600">
                                <i class="fas fa-arrow-up mr-1"></i>
                                Rp {{ number_format($cabang->pengeluaran ?? 0, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i class="fas fa-store text-4xl text-slate-300 mb-3"></i>
                        <p class="text-sm text-slate-400">Belum ada data cabang</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Transactions & Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Transactions -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100">
            <div class="p-6 border-b border-slate-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">Transaksi Terbaru</h2>
                        <p class="text-sm text-slate-500">10 transaksi terakhir</p>
                    </div>
                    <a href="{{ route('master-data.laporan-keuangan.index') }}"
                        class="text-sm text-orange-500 hover:text-orange-600 font-medium flex items-center gap-1 transition-colors">
                        Lihat Semua
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>
            <div class="divide-y divide-slate-100 max-h-96 overflow-y-auto">
                @forelse($recentTransactions as $transaction)
                    <div class="p-4 md:p-6 flex items-center gap-4 hover:bg-slate-50 transition-colors">
                        <div
                            class="w-12 h-12 {{ $transaction['type'] == 'pemasukan' ? 'bg-green-100' : 'bg-red-100' }} rounded-xl flex items-center justify-center shrink-0">
                            <i
                                class="fas {{ $transaction['type'] == 'pemasukan' ? 'fa-arrow-down text-green-600' : 'fa-arrow-up text-red-600' }}"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-slate-800 truncate">{{ $transaction['title'] }}</h3>
                            <p class="text-sm text-slate-500">{{ $transaction['cabang'] }} &bull;
                                {{ $transaction['date'] }}</p>
                        </div>
                        <div class="text-right">
                            <p
                                class="font-semibold {{ $transaction['type'] == 'pemasukan' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $transaction['type'] == 'pemasukan' ? '+' : '-' }}Rp
                                {{ number_format($transaction['amount'], 0, ',', '.') }}
                            </p>
                            <span class="text-xs text-slate-400">{{ $transaction['kategori'] }}</span>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center">
                        <i class="fas fa-receipt text-4xl text-slate-300 mb-3"></i>
                        <p class="text-slate-400">Belum ada transaksi</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Quick Actions & Info -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                <h2 class="text-lg font-bold text-slate-800 mb-4">Aksi Cepat</h2>
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('master-data.laporan-keuangan.create', ['jenis' => Hashids::encode(1)]) }}"
                        class="flex flex-col items-center gap-2 p-4 bg-green-50 hover:bg-green-100 rounded-xl transition-colors group">
                        <div
                            class="w-10 h-10 bg-green-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-plus text-white"></i>
                        </div>
                        <span class="text-sm font-medium text-green-700">Pemasukan</span>
                    </a>
                    <a href="{{ route('master-data.laporan-keuangan.create', ['jenis' => Hashids::encode(2)]) }}"
                        class="flex flex-col items-center gap-2 p-4 bg-red-50 hover:bg-red-100 rounded-xl transition-colors group">
                        <div
                            class="w-10 h-10 bg-red-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-minus text-white"></i>
                        </div>
                        <span class="text-sm font-medium text-red-700">Pengeluaran</span>
                    </a>
                    <a href="{{ route('laporan.harian') }}"
                        class="flex flex-col items-center gap-2 p-4 bg-blue-50 hover:bg-blue-100 rounded-xl transition-colors group">
                        <div
                            class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-file-alt text-white"></i>
                        </div>
                        <span class="text-sm font-medium text-blue-700">Laporan</span>
                    </a>
                    <a href="{{ route('gaji.index') }}"
                        class="flex flex-col items-center gap-2 p-4 bg-purple-50 hover:bg-purple-100 rounded-xl transition-colors group">
                        <div
                            class="w-10 h-10 bg-purple-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-money-bill-wave text-white"></i>
                        </div>
                        <span class="text-sm font-medium text-purple-700">Gaji</span>
                    </a>
                </div>
            </div>

            <!-- Info Summary -->
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl p-6 text-white">
                <h2 class="font-bold mb-4">Ringkasan Bulan Ini</h2>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-orange-100">Total Pemasukan</span>
                        <span class="font-semibold">Rp {{ number_format($stats['total_pemasukan'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-orange-100">Total Pengeluaran</span>
                        <span class="font-semibold">Rp
                            {{ number_format($stats['total_pengeluaran'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-orange-100">Total Gaji</span>
                        <span class="font-semibold">Rp {{ number_format($stats['total_gaji'], 0, ',', '.') }}</span>
                    </div>
                    <hr class="border-orange-400">
                    <div class="flex justify-between text-lg">
                        <span class="font-medium">Profit Bersih</span>
                        <span class="font-bold">Rp {{ number_format($stats['profit_bersih'], 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('chartKeuangan').getContext('2d');
        const chartData = @json($chartData);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartData.map(d => d.day + '\n' + d.date),
                datasets: [{
                        label: 'Pemasukan',
                        data: chartData.map(d => d.pemasukan),
                        backgroundColor: 'rgba(34, 197, 94, 0.8)',
                        borderColor: 'rgb(34, 197, 94)',
                        borderWidth: 1,
                        borderRadius: 4,
                    },
                    {
                        label: 'Pengeluaran',
                        data: chartData.map(d => d.pengeluaran),
                        backgroundColor: 'rgba(239, 68, 68, 0.8)',
                        borderColor: 'rgb(239, 68, 68)',
                        borderWidth: 1,
                        borderRadius: 4,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': Rp ' + context.raw.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + (value / 1000000).toFixed(1) + 'jt';
                            }
                        }
                    }
                }
            }
        });
    </script>
@endpush
