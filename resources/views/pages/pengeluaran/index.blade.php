@extends('layouts.app')

@section('title', 'Pengeluaran')

@section('page-header')
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-800">Pengeluaran</h1>
            <p class="text-slate-500 mt-1">Kelola semua data pengeluaran usaha Anda</p>
        </div>
        <div class="flex items-center gap-3">
            <button
                class="flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-slate-600 text-sm font-medium rounded-xl hover:bg-slate-50 transition-all duration-200">
                <i class="fas fa-filter"></i>
                <span>Filter</span>
            </button>
            <a href="{{ route('pengeluaran.create') }}"
                class="flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-red-500 to-red-600 text-white text-sm font-medium rounded-xl hover:from-red-600 hover:to-red-700 shadow-lg shadow-red-500/30 transition-all duration-200">
                <i class="fas fa-plus"></i>
                <span>Tambah Pengeluaran</span>
            </a>
        </div>
    </div>
@endsection

@section('content')
    <!-- Stats Summary -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-day text-red-600"></i>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Hari Ini</p>
                    <p class="text-xl font-bold text-slate-800">Rp 450.000</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-week text-orange-600"></i>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Minggu Ini</p>
                    <p class="text-xl font-bold text-slate-800">Rp 2.850.000</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-purple-600"></i>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Bulan Ini</p>
                    <p class="text-xl font-bold text-slate-800">Rp 8.750.000</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <!-- Table Header -->
        <div class="p-4 md:p-6 border-b border-slate-100">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="relative">
                    <input type="text" placeholder="Cari transaksi..."
                        class="w-full md:w-80 pl-10 pr-4 py-2.5 bg-slate-100 border-0 rounded-xl text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:bg-white transition-all duration-200">
                    <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                </div>
                <div class="flex items-center gap-2">
                    <select
                        class="px-4 py-2.5 bg-slate-100 border-0 rounded-xl text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-red-500/20">
                        <option>Semua Kategori</option>
                        <option>Bahan Baku</option>
                        <option>Operasional</option>
                        <option>Gaji</option>
                        <option>Lainnya</option>
                    </select>
                    <select
                        class="px-4 py-2.5 bg-slate-100 border-0 rounded-xl text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-red-500/20">
                        <option>Terbaru</option>
                        <option>Terlama</option>
                        <option>Nominal Tertinggi</option>
                        <option>Nominal Terendah</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="text-left px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            Tanggal</th>
                        <th class="text-left px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            Deskripsi</th>
                        <th class="text-left px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            Kategori</th>
                        <th class="text-right px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            Jumlah</th>
                        <th class="text-center px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <!-- Sample Row 1 -->
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-slate-800">07 Des 2025</p>
                            <p class="text-xs text-slate-500">10:15</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-slate-800">Pembelian Daging Sapi</p>
                            <p class="text-xs text-slate-500">5 kg @ Rp 100.000</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full">Bahan
                                Baku</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <p class="text-sm font-semibold text-red-600">-Rp 500.000</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button
                                    class="p-2 text-slate-400 hover:text-blue-500 hover:bg-blue-50 rounded-lg transition-colors">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button
                                    class="p-2 text-slate-400 hover:text-orange-500 hover:bg-orange-50 rounded-lg transition-colors">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button
                                    class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Sample Row 2 -->
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-slate-800">06 Des 2025</p>
                            <p class="text-xs text-slate-500">09:00</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-slate-800">Biaya Listrik</p>
                            <p class="text-xs text-slate-500">Tagihan bulan Desember</p>
                        </td>
                        <td class="px-6 py-4">
                            <span
                                class="px-3 py-1 bg-orange-100 text-orange-700 text-xs font-medium rounded-full">Operasional</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <p class="text-sm font-semibold text-red-600">-Rp 350.000</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button
                                    class="p-2 text-slate-400 hover:text-blue-500 hover:bg-blue-50 rounded-lg transition-colors">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button
                                    class="p-2 text-slate-400 hover:text-orange-500 hover:bg-orange-50 rounded-lg transition-colors">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button
                                    class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Sample Row 3 -->
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-slate-800">05 Des 2025</p>
                            <p class="text-xs text-slate-500">14:30</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-slate-800">Pembelian Tortilla</p>
                            <p class="text-xs text-slate-500">100 lembar</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full">Bahan
                                Baku</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <p class="text-sm font-semibold text-red-600">-Rp 250.000</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button
                                    class="p-2 text-slate-400 hover:text-blue-500 hover:bg-blue-50 rounded-lg transition-colors">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button
                                    class="p-2 text-slate-400 hover:text-orange-500 hover:bg-orange-50 rounded-lg transition-colors">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button
                                    class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 md:p-6 border-t border-slate-100">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <p class="text-sm text-slate-500">Menampilkan 1-10 dari 85 data</p>
                <div class="flex items-center gap-2">
                    <button
                        class="px-3 py-2 text-sm text-slate-500 hover:bg-slate-100 rounded-lg transition-colors disabled:opacity-50"
                        disabled>
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="px-3 py-2 text-sm bg-orange-500 text-white rounded-lg">1</button>
                    <button
                        class="px-3 py-2 text-sm text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">2</button>
                    <button
                        class="px-3 py-2 text-sm text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">3</button>
                    <span class="px-2 text-slate-400">...</span>
                    <button
                        class="px-3 py-2 text-sm text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">9</button>
                    <button class="px-3 py-2 text-sm text-slate-500 hover:bg-slate-100 rounded-lg transition-colors">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
