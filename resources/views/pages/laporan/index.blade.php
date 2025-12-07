@extends('layouts.app')

@section('title', 'Laporan Keuangan')

@section('page-header')
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-800">Laporan Keuangan</h1>
            <p class="text-slate-500 mt-1">Ringkasan laporan keuangan usaha Anda</p>
        </div>
        <div class="flex items-center gap-3">
            <button
                class="flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-slate-600 text-sm font-medium rounded-xl hover:bg-slate-50 transition-all duration-200">
                <i class="fas fa-print"></i>
                <span>Cetak</span>
            </button>
            <button
                class="flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-blue-600 hover:to-blue-700 shadow-lg shadow-blue-500/30 transition-all duration-200">
                <i class="fas fa-download"></i>
                <span>Export PDF</span>
            </button>
        </div>
    </div>
@endsection

@section('content')
    <!-- Period Selector -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 mb-6">
        <div class="flex flex-col md:flex-row md:items-center gap-4">
            <div class="flex items-center gap-2">
                <span class="text-sm font-medium text-slate-600">Periode:</span>
                <div class="flex items-center gap-2">
                    <input type="date"
                        class="px-4 py-2.5 bg-slate-100 border-0 rounded-xl text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                        value="2025-12-01">
                    <span class="text-slate-400">-</span>
                    <input type="date"
                        class="px-4 py-2.5 bg-slate-100 border-0 rounded-xl text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                        value="2025-12-31">
                </div>
            </div>
            <button
                class="px-4 py-2.5 bg-slate-800 text-white text-sm font-medium rounded-xl hover:bg-slate-900 transition-colors">
                Tampilkan
            </button>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-lg shadow-green-500/30">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-arrow-down text-xl"></i>
                </div>
                <span class="px-2.5 py-1 bg-white/20 text-white text-xs font-medium rounded-lg">+12.5%</span>
            </div>
            <h3 class="text-white/80 text-sm font-medium mb-1">Total Pemasukan</h3>
            <p class="text-2xl font-bold">Rp 24.500.000</p>
        </div>

        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-2xl p-6 text-white shadow-lg shadow-red-500/30">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-arrow-up text-xl"></i>
                </div>
                <span class="px-2.5 py-1 bg-white/20 text-white text-xs font-medium rounded-lg">-3.2%</span>
            </div>
            <h3 class="text-white/80 text-sm font-medium mb-1">Total Pengeluaran</h3>
            <p class="text-2xl font-bold">Rp 8.750.000</p>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg shadow-blue-500/30">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-wallet text-xl"></i>
                </div>
                <span class="px-2.5 py-1 bg-white/20 text-white text-xs font-medium rounded-lg">+18.7%</span>
            </div>
            <h3 class="text-white/80 text-sm font-medium mb-1">Profit Bersih</h3>
            <p class="text-2xl font-bold">Rp 15.750.000</p>
        </div>

        <div
            class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl p-6 text-white shadow-lg shadow-orange-500/30">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-percentage text-xl"></i>
                </div>
            </div>
            <h3 class="text-white/80 text-sm font-medium mb-1">Margin Profit</h3>
            <p class="text-2xl font-bold">64.3%</p>
        </div>
    </div>

    <!-- Charts & Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Income vs Expense Chart -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <h2 class="text-lg font-bold text-slate-800 mb-4">Grafik Pemasukan vs Pengeluaran</h2>
            <div class="h-72 bg-gradient-to-br from-slate-50 to-slate-100 rounded-xl flex items-center justify-center">
                <div class="text-center">
                    <i class="fas fa-chart-bar text-4xl text-slate-300 mb-3"></i>
                    <p class="text-sm text-slate-400">Bar Chart Area</p>
                </div>
            </div>
        </div>

        <!-- Expense Categories -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <h2 class="text-lg font-bold text-slate-800 mb-4">Distribusi Pengeluaran</h2>
            <div class="h-48 bg-gradient-to-br from-slate-50 to-slate-100 rounded-xl flex items-center justify-center mb-4">
                <div class="text-center">
                    <i class="fas fa-chart-pie text-4xl text-slate-300 mb-2"></i>
                    <p class="text-xs text-slate-400">Pie Chart</p>
                </div>
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                        <span class="text-sm text-slate-600">Bahan Baku</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-semibold text-slate-700">Rp 3.937.500</span>
                        <span class="text-xs text-slate-500">(45%)</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-orange-500 rounded-full"></div>
                        <span class="text-sm text-slate-600">Operasional</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-semibold text-slate-700">Rp 2.187.500</span>
                        <span class="text-xs text-slate-500">(25%)</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <span class="text-sm text-slate-600">Gaji</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-semibold text-slate-700">Rp 1.750.000</span>
                        <span class="text-xs text-slate-500">(20%)</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-purple-500 rounded-full"></div>
                        <span class="text-sm text-slate-600">Lainnya</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-semibold text-slate-700">Rp 875.000</span>
                        <span class="text-xs text-slate-500">(10%)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Types -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('laporan.harian') }}"
            class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg hover:border-blue-200 transition-all duration-300 group">
            <div
                class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <i class="fas fa-calendar-day text-2xl text-blue-600"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-2">Laporan Harian</h3>
            <p class="text-sm text-slate-500 mb-4">Lihat detail transaksi dan ringkasan keuangan harian</p>
            <span class="text-blue-600 font-medium text-sm flex items-center gap-2 group-hover:gap-3 transition-all">
                Lihat Laporan
                <i class="fas fa-arrow-right"></i>
            </span>
        </a>

        <a href="{{ route('laporan.mingguan') }}"
            class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg hover:border-green-200 transition-all duration-300 group">
            <div
                class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <i class="fas fa-calendar-week text-2xl text-green-600"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-2">Laporan Mingguan</h3>
            <p class="text-sm text-slate-500 mb-4">Analisis tren keuangan per minggu</p>
            <span class="text-green-600 font-medium text-sm flex items-center gap-2 group-hover:gap-3 transition-all">
                Lihat Laporan
                <i class="fas fa-arrow-right"></i>
            </span>
        </a>

        <a href="{{ route('laporan.bulanan') }}"
            class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg hover:border-orange-200 transition-all duration-300 group">
            <div
                class="w-14 h-14 bg-orange-100 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <i class="fas fa-calendar-alt text-2xl text-orange-600"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-2">Laporan Bulanan</h3>
            <p class="text-sm text-slate-500 mb-4">Ringkasan lengkap keuangan bulanan</p>
            <span class="text-orange-600 font-medium text-sm flex items-center gap-2 group-hover:gap-3 transition-all">
                Lihat Laporan
                <i class="fas fa-arrow-right"></i>
            </span>
        </a>
    </div>
@endsection
