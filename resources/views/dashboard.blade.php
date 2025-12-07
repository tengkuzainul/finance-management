@extends('layouts.app')

@section('title', 'Dashboard')

@section('page-header')
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-800">Dashboard</h1>
            <p class="text-slate-500 mt-1">Selamat datang kembali, {{ auth()->user()->name ?? 'User' }}! 👋</p>
        </div>
        <div class="flex items-center gap-3">
            <select
                class="px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
                <option>Bulan Ini</option>
                <option>Minggu Ini</option>
                <option>Hari Ini</option>
                <option>Tahun Ini</option>
            </select>
            <button
                class="flex items-center gap-2 px-4 py-2.5 bg-linear-to-r from-orange-500 to-orange-600 text-white text-sm font-medium rounded-xl hover:from-orange-600 hover:to-orange-700 shadow-lg shadow-orange-500/30 transition-all duration-200">
                <i class="fas fa-download"></i>
                <span class="hidden md:inline">Export</span>
            </button>
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
                <span
                    class="px-2.5 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-lg flex items-center gap-1">
                    <i class="fas fa-arrow-up text-[10px]"></i>
                    12.5%
                </span>
            </div>
            <h3 class="text-slate-500 text-sm font-medium mb-1">Total Pemasukan</h3>
            <p class="text-2xl font-bold text-slate-800">Rp 24.500.000</p>
            <p class="text-xs text-slate-400 mt-2">+Rp 2.500.000 dari bulan lalu</p>
        </div>

        <!-- Total Pengeluaran -->
        <div
            class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg hover:shadow-slate-200/50 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-arrow-up text-red-600 text-lg"></i>
                </div>
                <span class="px-2.5 py-1 bg-red-100 text-red-700 text-xs font-medium rounded-lg flex items-center gap-1">
                    <i class="fas fa-arrow-down text-[10px]"></i>
                    3.2%
                </span>
            </div>
            <h3 class="text-slate-500 text-sm font-medium mb-1">Total Pengeluaran</h3>
            <p class="text-2xl font-bold text-slate-800">Rp 8.750.000</p>
            <p class="text-xs text-slate-400 mt-2">-Rp 350.000 dari bulan lalu</p>
        </div>

        <!-- Saldo/Profit -->
        <div
            class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg hover:shadow-slate-200/50 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-wallet text-blue-600 text-lg"></i>
                </div>
                <span class="px-2.5 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-lg flex items-center gap-1">
                    <i class="fas fa-arrow-up text-[10px]"></i>
                    18.7%
                </span>
            </div>
            <h3 class="text-slate-500 text-sm font-medium mb-1">Profit Bersih</h3>
            <p class="text-2xl font-bold text-slate-800">Rp 15.750.000</p>
            <p class="text-xs text-slate-400 mt-2">+Rp 2.850.000 dari bulan lalu</p>
        </div>

        <!-- Total Transaksi -->
        <div
            class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg hover:shadow-slate-200/50 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-receipt text-orange-600 text-lg"></i>
                </div>
                <span
                    class="px-2.5 py-1 bg-orange-100 text-orange-700 text-xs font-medium rounded-lg flex items-center gap-1">
                    <i class="fas fa-arrow-up text-[10px]"></i>
                    8.3%
                </span>
            </div>
            <h3 class="text-slate-500 text-sm font-medium mb-1">Total Transaksi</h3>
            <p class="text-2xl font-bold text-slate-800">1,254</p>
            <p class="text-xs text-slate-400 mt-2">+96 transaksi dari bulan lalu</p>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Main Chart -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Grafik Keuangan</h2>
                    <p class="text-sm text-slate-500">Pemasukan vs Pengeluaran</p>
                </div>
                <div class="flex items-center gap-2">
                    <button
                        class="px-3 py-1.5 text-sm font-medium text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">7
                        Hari</button>
                    <button class="px-3 py-1.5 text-sm font-medium bg-orange-100 text-orange-600 rounded-lg">30
                        Hari</button>
                    <button
                        class="px-3 py-1.5 text-sm font-medium text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">90
                        Hari</button>
                </div>
            </div>
            <!-- Chart Placeholder -->
            <div class="h-72 bg-linear-to-br from-slate-50 to-slate-100 rounded-xl flex items-center justify-center">
                <div class="text-center">
                    <i class="fas fa-chart-area text-4xl text-slate-300 mb-3"></i>
                    <p class="text-sm text-slate-400">Chart Area</p>
                    <p class="text-xs text-slate-400">Integrasikan dengan Chart.js atau ApexCharts</p>
                </div>
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

        <!-- Pie Chart -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <div class="mb-6">
                <h2 class="text-lg font-bold text-slate-800">Kategori Pengeluaran</h2>
                <p class="text-sm text-slate-500">Distribusi bulan ini</p>
            </div>
            <!-- Pie Chart Placeholder -->
            <div class="h-48 bg-linear-to-br from-slate-50 to-slate-100 rounded-xl flex items-center justify-center mb-4">
                <div class="text-center">
                    <i class="fas fa-chart-pie text-4xl text-slate-300 mb-2"></i>
                    <p class="text-xs text-slate-400">Pie Chart</p>
                </div>
            </div>
            <!-- Categories -->
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                        <span class="text-sm text-slate-600">Bahan Baku</span>
                    </div>
                    <span class="text-sm font-semibold text-slate-700">45%</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-orange-500 rounded-full"></div>
                        <span class="text-sm text-slate-600">Operasional</span>
                    </div>
                    <span class="text-sm font-semibold text-slate-700">25%</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <span class="text-sm text-slate-600">Gaji</span>
                    </div>
                    <span class="text-sm font-semibold text-slate-700">20%</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-purple-500 rounded-full"></div>
                        <span class="text-sm text-slate-600">Lainnya</span>
                    </div>
                    <span class="text-sm font-semibold text-slate-700">10%</span>
                </div>
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
                        <p class="text-sm text-slate-500">5 transaksi terakhir</p>
                    </div>
                    <a href="{{ url('/transaksi') }}"
                        class="text-sm text-orange-500 hover:text-orange-600 font-medium flex items-center gap-1 transition-colors">
                        Lihat Semua
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>
            <div class="divide-y divide-slate-100">
                <!-- Transaction Item 1 -->
                <div class="p-4 md:p-6 flex items-center gap-4 hover:bg-slate-50 transition-colors">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center shrink-0">
                        <i class="fas fa-arrow-down text-green-600"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-slate-800 truncate">Penjualan Kebab Original</h3>
                        <p class="text-sm text-slate-500">Hari ini, 14:30</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-green-600">+Rp 150.000</p>
                        <span class="text-xs text-slate-400">Pemasukan</span>
                    </div>
                </div>

                <!-- Transaction Item 2 -->
                <div class="p-4 md:p-6 flex items-center gap-4 hover:bg-slate-50 transition-colors">
                    <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center shrink-0">
                        <i class="fas fa-arrow-up text-red-600"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-slate-800 truncate">Pembelian Daging Sapi</h3>
                        <p class="text-sm text-slate-500">Hari ini, 10:15</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-red-600">-Rp 500.000</p>
                        <span class="text-xs text-slate-400">Pengeluaran</span>
                    </div>
                </div>

                <!-- Transaction Item 3 -->
                <div class="p-4 md:p-6 flex items-center gap-4 hover:bg-slate-50 transition-colors">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center shrink-0">
                        <i class="fas fa-arrow-down text-green-600"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-slate-800 truncate">Penjualan Kebab Jumbo</h3>
                        <p class="text-sm text-slate-500">Kemarin, 19:45</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-green-600">+Rp 275.000</p>
                        <span class="text-xs text-slate-400">Pemasukan</span>
                    </div>
                </div>

                <!-- Transaction Item 4 -->
                <div class="p-4 md:p-6 flex items-center gap-4 hover:bg-slate-50 transition-colors">
                    <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center shrink-0">
                        <i class="fas fa-arrow-up text-red-600"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-slate-800 truncate">Biaya Listrik</h3>
                        <p class="text-sm text-slate-500">Kemarin, 09:00</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-red-600">-Rp 350.000</p>
                        <span class="text-xs text-slate-400">Pengeluaran</span>
                    </div>
                </div>

                <!-- Transaction Item 5 -->
                <div class="p-4 md:p-6 flex items-center gap-4 hover:bg-slate-50 transition-colors">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center shrink-0">
                        <i class="fas fa-arrow-down text-green-600"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-slate-800 truncate">Penjualan Minuman</h3>
                        <p class="text-sm text-slate-500">2 hari lalu, 16:20</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-green-600">+Rp 85.000</p>
                        <span class="text-xs text-slate-400">Pemasukan</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Top Products -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                <h2 class="text-lg font-bold text-slate-800 mb-4">Aksi Cepat</h2>
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ url('/pemasukan/create') }}"
                        class="flex flex-col items-center gap-2 p-4 bg-green-50 hover:bg-green-100 rounded-xl transition-colors group">
                        <div
                            class="w-10 h-10 bg-green-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-plus text-white"></i>
                        </div>
                        <span class="text-sm font-medium text-green-700">Pemasukan</span>
                    </a>
                    <a href="{{ url('/pengeluaran/create') }}"
                        class="flex flex-col items-center gap-2 p-4 bg-red-50 hover:bg-red-100 rounded-xl transition-colors group">
                        <div
                            class="w-10 h-10 bg-red-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-minus text-white"></i>
                        </div>
                        <span class="text-sm font-medium text-red-700">Pengeluaran</span>
                    </a>
                    <a href="{{ url('/laporan') }}"
                        class="flex flex-col items-center gap-2 p-4 bg-blue-50 hover:bg-blue-100 rounded-xl transition-colors group">
                        <div
                            class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-file-alt text-white"></i>
                        </div>
                        <span class="text-sm font-medium text-blue-700">Laporan</span>
                    </a>
                    <a href="{{ url('/produk') }}"
                        class="flex flex-col items-center gap-2 p-4 bg-purple-50 hover:bg-purple-100 rounded-xl transition-colors group">
                        <div
                            class="w-10 h-10 bg-purple-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-box text-white"></i>
                        </div>
                        <span class="text-sm font-medium text-purple-700">Produk</span>
                    </a>
                </div>
            </div>

            <!-- Top Products -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-slate-800">Produk Terlaris</h2>
                    <a href="{{ url('/produk') }}" class="text-sm text-orange-500 hover:text-orange-600 font-medium">
                        Lihat
                    </a>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center text-lg">
                            🥙
                        </div>
                        <div class="flex-1">
                            <h3 class="font-medium text-slate-800 text-sm">Kebab Original</h3>
                            <p class="text-xs text-slate-500">450 terjual</p>
                        </div>
                        <span class="text-sm font-semibold text-slate-700">Rp 8.1jt</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center text-lg">
                            🌯
                        </div>
                        <div class="flex-1">
                            <h3 class="font-medium text-slate-800 text-sm">Kebab Jumbo</h3>
                            <p class="text-xs text-slate-500">320 terjual</p>
                        </div>
                        <span class="text-sm font-semibold text-slate-700">Rp 7.2jt</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center text-lg">
                            🧋
                        </div>
                        <div class="flex-1">
                            <h3 class="font-medium text-slate-800 text-sm">Teh Tarik</h3>
                            <p class="text-xs text-slate-500">280 terjual</p>
                        </div>
                        <span class="text-sm font-semibold text-slate-700">Rp 2.8jt</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
