@extends('layouts.app')

@section('title', 'Kategori')

@section('page-header')
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-800">Kategori</h1>
            <p class="text-slate-500 mt-1">Kelola kategori produk dan transaksi</p>
        </div>
        <button
            class="flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white text-sm font-medium rounded-xl hover:from-indigo-600 hover:to-indigo-700 shadow-lg shadow-indigo-500/30 transition-all duration-200">
            <i class="fas fa-plus"></i>
            <span>Tambah Kategori</span>
        </button>
    </div>
@endsection

@section('content')
    <!-- Category Tabs -->
    <div class="flex items-center gap-2 mb-6 overflow-x-auto pb-2">
        <button class="px-4 py-2 bg-indigo-500 text-white text-sm font-medium rounded-xl whitespace-nowrap">Semua</button>
        <button
            class="px-4 py-2 bg-white border border-slate-200 text-slate-600 text-sm font-medium rounded-xl hover:bg-slate-50 whitespace-nowrap transition-colors">Pendapatan</button>
        <button
            class="px-4 py-2 bg-white border border-slate-200 text-slate-600 text-sm font-medium rounded-xl hover:bg-slate-50 whitespace-nowrap transition-colors">Pengeluaran</button>
        <button
            class="px-4 py-2 bg-white border border-slate-200 text-slate-600 text-sm font-medium rounded-xl hover:bg-slate-50 whitespace-nowrap transition-colors">Produk</button>
    </div>

    <!-- Categories Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <!-- Category Card 1 -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-green-600 text-lg"></i>
                </div>
                <div class="flex items-center gap-2">
                    <button class="p-2 text-slate-400 hover:text-blue-500 hover:bg-blue-50 rounded-lg transition-colors">
                        <i class="fas fa-edit text-sm"></i>
                    </button>
                    <button class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                        <i class="fas fa-trash text-sm"></i>
                    </button>
                </div>
            </div>
            <h3 class="font-bold text-slate-800 mb-1">Penjualan</h3>
            <p class="text-sm text-slate-500 mb-3">Kategori untuk semua transaksi penjualan</p>
            <div class="flex items-center justify-between">
                <span class="px-2.5 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">Pendapatan</span>
                <span class="text-sm text-slate-500">150 transaksi</span>
            </div>
        </div>

        <!-- Category Card 2 -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-box text-blue-600 text-lg"></i>
                </div>
                <div class="flex items-center gap-2">
                    <button class="p-2 text-slate-400 hover:text-blue-500 hover:bg-blue-50 rounded-lg transition-colors">
                        <i class="fas fa-edit text-sm"></i>
                    </button>
                    <button class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                        <i class="fas fa-trash text-sm"></i>
                    </button>
                </div>
            </div>
            <h3 class="font-bold text-slate-800 mb-1">Bahan Baku</h3>
            <p class="text-sm text-slate-500 mb-3">Pengeluaran untuk pembelian bahan baku</p>
            <div class="flex items-center justify-between">
                <span class="px-2.5 py-1 bg-red-100 text-red-700 text-xs font-medium rounded-full">Pengeluaran</span>
                <span class="text-sm text-slate-500">45 transaksi</span>
            </div>
        </div>

        <!-- Category Card 3 -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-tools text-orange-600 text-lg"></i>
                </div>
                <div class="flex items-center gap-2">
                    <button class="p-2 text-slate-400 hover:text-blue-500 hover:bg-blue-50 rounded-lg transition-colors">
                        <i class="fas fa-edit text-sm"></i>
                    </button>
                    <button class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                        <i class="fas fa-trash text-sm"></i>
                    </button>
                </div>
            </div>
            <h3 class="font-bold text-slate-800 mb-1">Operasional</h3>
            <p class="text-sm text-slate-500 mb-3">Biaya operasional seperti listrik, air, gas</p>
            <div class="flex items-center justify-between">
                <span class="px-2.5 py-1 bg-red-100 text-red-700 text-xs font-medium rounded-full">Pengeluaran</span>
                <span class="text-sm text-slate-500">28 transaksi</span>
            </div>
        </div>

        <!-- Category Card 4 -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-purple-600 text-lg"></i>
                </div>
                <div class="flex items-center gap-2">
                    <button class="p-2 text-slate-400 hover:text-blue-500 hover:bg-blue-50 rounded-lg transition-colors">
                        <i class="fas fa-edit text-sm"></i>
                    </button>
                    <button class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                        <i class="fas fa-trash text-sm"></i>
                    </button>
                </div>
            </div>
            <h3 class="font-bold text-slate-800 mb-1">Gaji Karyawan</h3>
            <p class="text-sm text-slate-500 mb-3">Pembayaran gaji dan bonus karyawan</p>
            <div class="flex items-center justify-between">
                <span class="px-2.5 py-1 bg-red-100 text-red-700 text-xs font-medium rounded-full">Pengeluaran</span>
                <span class="text-sm text-slate-500">12 transaksi</span>
            </div>
        </div>

        <!-- Category Card 5 -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-utensils text-amber-600 text-lg"></i>
                </div>
                <div class="flex items-center gap-2">
                    <button class="p-2 text-slate-400 hover:text-blue-500 hover:bg-blue-50 rounded-lg transition-colors">
                        <i class="fas fa-edit text-sm"></i>
                    </button>
                    <button class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                        <i class="fas fa-trash text-sm"></i>
                    </button>
                </div>
            </div>
            <h3 class="font-bold text-slate-800 mb-1">Kebab</h3>
            <p class="text-sm text-slate-500 mb-3">Kategori produk kebab semua varian</p>
            <div class="flex items-center justify-between">
                <span class="px-2.5 py-1 bg-purple-100 text-purple-700 text-xs font-medium rounded-full">Produk</span>
                <span class="text-sm text-slate-500">8 produk</span>
            </div>
        </div>

        <!-- Category Card 6 -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-cyan-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-coffee text-cyan-600 text-lg"></i>
                </div>
                <div class="flex items-center gap-2">
                    <button class="p-2 text-slate-400 hover:text-blue-500 hover:bg-blue-50 rounded-lg transition-colors">
                        <i class="fas fa-edit text-sm"></i>
                    </button>
                    <button class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                        <i class="fas fa-trash text-sm"></i>
                    </button>
                </div>
            </div>
            <h3 class="font-bold text-slate-800 mb-1">Minuman</h3>
            <p class="text-sm text-slate-500 mb-3">Kategori produk minuman</p>
            <div class="flex items-center justify-between">
                <span class="px-2.5 py-1 bg-purple-100 text-purple-700 text-xs font-medium rounded-full">Produk</span>
                <span class="text-sm text-slate-500">6 produk</span>
            </div>
        </div>

        <!-- Add New Category Card -->
        <div
            class="bg-slate-50 rounded-2xl p-6 border-2 border-dashed border-slate-200 hover:border-indigo-400 hover:bg-indigo-50/30 transition-all duration-300 cursor-pointer flex flex-col items-center justify-center min-h-[200px]">
            <div class="w-12 h-12 bg-slate-200 rounded-xl flex items-center justify-center mb-3">
                <i class="fas fa-plus text-slate-400 text-lg"></i>
            </div>
            <p class="font-medium text-slate-500">Tambah Kategori Baru</p>
        </div>
    </div>
@endsection

