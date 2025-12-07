@extends('layouts.app')

@section('title', 'Produk')

@section('page-header')
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-800">Produk</h1>
            <p class="text-slate-500 mt-1">Kelola daftar produk usaha Anda</p>
        </div>
        <div class="flex items-center gap-3">
            <button
                class="flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-slate-600 text-sm font-medium rounded-xl hover:bg-slate-50 transition-all duration-200">
                <i class="fas fa-filter"></i>
                <span>Filter</span>
            </button>
            <button
                class="flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-purple-500 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-purple-600 hover:to-purple-700 shadow-lg shadow-purple-500/30 transition-all duration-200">
                <i class="fas fa-plus"></i>
                <span>Tambah Produk</span>
            </button>
        </div>
    </div>
@endsection

@section('content')
    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-box text-purple-600"></i>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Total Produk</p>
                    <p class="text-xl font-bold text-slate-800">24</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Aktif</p>
                    <p class="text-xl font-bold text-green-600">20</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-times-circle text-red-600"></i>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Nonaktif</p>
                    <p class="text-xl font-bold text-red-600">4</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-fire text-orange-600"></i>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Terlaris</p>
                    <p class="text-xl font-bold text-orange-600">5</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-4 md:p-6 border-b border-slate-100">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="relative">
                    <input type="text" placeholder="Cari produk..."
                        class="w-full md:w-80 pl-10 pr-4 py-2.5 bg-slate-100 border-0 rounded-xl text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:bg-white transition-all duration-200">
                    <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                </div>
                <div class="flex items-center gap-2">
                    <button class="p-2.5 bg-slate-100 rounded-lg text-slate-600 hover:bg-slate-200 transition-colors">
                        <i class="fas fa-th-large"></i>
                    </button>
                    <button class="p-2.5 bg-purple-100 rounded-lg text-purple-600">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Products Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="text-left px-6 py-4 text-xs font-semibold text-slate-500 uppercase">Produk</th>
                        <th class="text-left px-6 py-4 text-xs font-semibold text-slate-500 uppercase">Kategori</th>
                        <th class="text-right px-6 py-4 text-xs font-semibold text-slate-500 uppercase">Harga</th>
                        <th class="text-center px-6 py-4 text-xs font-semibold text-slate-500 uppercase">Terjual</th>
                        <th class="text-center px-6 py-4 text-xs font-semibold text-slate-500 uppercase">Status</th>
                        <th class="text-center px-6 py-4 text-xs font-semibold text-slate-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center text-2xl">🥙
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-800">Kebab Original</p>
                                    <p class="text-xs text-slate-500">SKU: KBB-001</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4"><span
                                class="px-3 py-1 bg-orange-100 text-orange-700 text-xs font-medium rounded-full">Kebab</span>
                        </td>
                        <td class="px-6 py-4 text-right font-semibold text-slate-800">Rp 18.000</td>
                        <td class="px-6 py-4 text-center text-slate-600">450</td>
                        <td class="px-6 py-4 text-center"><span
                                class="px-3 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">Aktif</span>
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
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center text-2xl">🌯
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-800">Kebab Jumbo</p>
                                    <p class="text-xs text-slate-500">SKU: KBB-002</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4"><span
                                class="px-3 py-1 bg-orange-100 text-orange-700 text-xs font-medium rounded-full">Kebab</span>
                        </td>
                        <td class="px-6 py-4 text-right font-semibold text-slate-800">Rp 25.000</td>
                        <td class="px-6 py-4 text-center text-slate-600">320</td>
                        <td class="px-6 py-4 text-center"><span
                                class="px-3 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">Aktif</span>
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
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-2xl">🧋
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-800">Teh Tarik</p>
                                    <p class="text-xs text-slate-500">SKU: MNM-001</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4"><span
                                class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full">Minuman</span>
                        </td>
                        <td class="px-6 py-4 text-right font-semibold text-slate-800">Rp 10.000</td>
                        <td class="px-6 py-4 text-center text-slate-600">280</td>
                        <td class="px-6 py-4 text-center"><span
                                class="px-3 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">Aktif</span>
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
                <p class="text-sm text-slate-500">Menampilkan 1-10 dari 24 produk</p>
                <div class="flex items-center gap-2">
                    <button
                        class="px-3 py-2 text-sm text-slate-500 hover:bg-slate-100 rounded-lg transition-colors disabled:opacity-50"
                        disabled>
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="px-3 py-2 text-sm bg-purple-500 text-white rounded-lg">1</button>
                    <button
                        class="px-3 py-2 text-sm text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">2</button>
                    <button
                        class="px-3 py-2 text-sm text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">3</button>
                    <button class="px-3 py-2 text-sm text-slate-500 hover:bg-slate-100 rounded-lg transition-colors">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
