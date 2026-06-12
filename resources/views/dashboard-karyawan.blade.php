@extends('layouts.app')

@section('title', 'Dashboard Karyawan')

@section('page-header')
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-800">Dashboard Saya</h1>
            <p class="text-slate-500 mt-1">Selamat datang, {{ auth()->user()->name ?? 'Karyawan' }}! 👋</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-600">
                <i class="fas fa-calendar-alt mr-2"></i>{{ now()->translatedFormat('F Y') }}
            </span>
            @if ($stats['pending_count'] > 0)
                <span
                    class="flex items-center gap-2 px-4 py-2.5 bg-yellow-100 text-yellow-700 text-sm font-medium rounded-xl">
                    <i class="fas fa-clock"></i>
                    <span>{{ $stats['pending_count'] }} Pending</span>
                </span>
            @endif
        </div>
    </div>
@endsection

@section('content')
    <!-- Profile Card -->
    @if ($karyawan)
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 mb-8">
            <div class="flex flex-col md:flex-row md:items-center gap-4">
                <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center">
                    @if (auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar"
                            class="w-16 h-16 rounded-full object-cover">
                    @else
                        <i class="fas fa-user text-orange-600 text-2xl"></i>
                    @endif
                </div>
                <div class="flex-1">
                    <h2 class="text-xl font-bold text-slate-800">{{ $karyawan->nama_lengkap }}</h2>
                    <p class="text-slate-500">{{ $karyawan->cabang->nama_cabang ?? 'Cabang tidak ditemukan' }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="px-4 py-2 bg-orange-50 rounded-xl text-center">
                        <p class="text-xs text-slate-500">No. HP</p>
                        <p class="font-semibold text-slate-800">{{ $karyawan->no_telepon }}</p>
                    </div>
                    <div class="px-4 py-2 bg-green-50 rounded-xl text-center">
                        <p class="text-xs text-slate-500">Bergabung</p>
                        <p class="font-semibold text-slate-800">
                            {{ $karyawan->tanggal_masuk?->translatedFormat('d M Y') ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
        <!-- Total Pendapatan Saya -->
        <div
            class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg hover:shadow-slate-200/50 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-arrow-down text-green-600 text-lg"></i>
                </div>
            </div>
            <h3 class="text-slate-500 text-sm font-medium mb-1">Pendapatan Saya</h3>
            <p class="text-2xl font-bold text-slate-800">Rp {{ number_format($stats['total_pemasukan'], 0, ',', '.') }}</p>
            <p class="text-xs text-slate-400 mt-2">Bulan ini</p>
        </div>

        <!-- Total Pengeluaran Saya -->
        <div
            class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg hover:shadow-slate-200/50 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-arrow-up text-red-600 text-lg"></i>
                </div>
            </div>
            <h3 class="text-slate-500 text-sm font-medium mb-1">Pengeluaran Saya</h3>
            <p class="text-2xl font-bold text-slate-800">Rp {{ number_format($stats['total_pengeluaran'], 0, ',', '.') }}
            </p>
            <p class="text-xs text-slate-400 mt-2">Bulan ini</p>
        </div>

        <!-- Transaksi Pending -->
        <div
            class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg hover:shadow-slate-200/50 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600 text-lg"></i>
                </div>
            </div>
            <h3 class="text-slate-500 text-sm font-medium mb-1">Pending</h3>
            <p class="text-2xl font-bold text-slate-800">{{ $stats['pending_count'] }}</p>
            <p class="text-xs text-slate-400 mt-2">Menunggu persetujuan</p>
        </div>

        <!-- Total Transaksi -->
        <div
            class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg hover:shadow-slate-200/50 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-receipt text-blue-600 text-lg"></i>
                </div>
            </div>
            <h3 class="text-slate-500 text-sm font-medium mb-1">Total Transaksi</h3>
            <p class="text-2xl font-bold text-slate-800">{{ $stats['total_transaksi'] }}</p>
            <p class="text-xs text-slate-400 mt-2">{{ $stats['draft_count'] }} draft</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- My Recent Transactions -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100">
            <div class="p-6 border-b border-slate-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">Transaksi Saya</h2>
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
                            class="w-12 h-12 {{ $transaction['type'] == 'Pendapatan' ? 'bg-green-100' : 'bg-red-100' }} rounded-xl flex items-center justify-center shrink-0">
                            <i
                                class="fas {{ $transaction['type'] == 'Pendapatan' ? 'fa-arrow-down text-green-600' : 'fa-arrow-up text-red-600' }}"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-slate-800 truncate">{{ $transaction['title'] }}</h3>
                            <p class="text-sm text-slate-500">{{ $transaction['date'] }}</p>
                        </div>
                        <div class="text-right">
                            <p
                                class="font-semibold {{ $transaction['type'] == 'Pendapatan' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $transaction['type'] == 'Pendapatan' ? '+' : '-' }}Rp
                                {{ number_format($transaction['amount'], 0, ',', '.') }}
                            </p>
                            <span
                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                {{ $transaction['status'] == 'Approved'
                                    ? 'bg-green-100 text-green-700'
                                    : ($transaction['status'] == 'Pending'
                                        ? 'bg-yellow-100 text-yellow-700'
                                        : ($transaction['status'] == 'Rejected'
                                            ? 'bg-red-100 text-red-700'
                                            : 'bg-slate-100 text-slate-700')) }}">
                                {{ $transaction['status'] }}
                            </span>
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

        <!-- Right Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                <h2 class="text-lg font-bold text-slate-800 mb-4">Aksi Cepat</h2>
                <div class="space-y-3">
                    <a href="{{ route('master-data.laporan-keuangan.create', ['jenis' => Hashids::encode(1)]) }}"
                        class="flex items-center gap-3 p-3 bg-green-50 hover:bg-green-100 rounded-xl transition-colors">
                        <div class="w-10 h-10 bg-green-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-plus text-white"></i>
                        </div>
                        <span class="font-medium text-green-700">Input Pendapatan</span>
                    </a>
                    <a href="{{ route('karyawan.gaji.index') }}"
                        class="flex items-center gap-3 p-3 bg-purple-50 hover:bg-purple-100 rounded-xl transition-colors">
                        <div class="w-10 h-10 bg-purple-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-money-bill-wave text-white"></i>
                        </div>
                        <span class="font-medium text-purple-700">Gaji Saya</span>
                    </a>
                </div>
            </div>

            <!-- Gaji History -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                <h2 class="text-lg font-bold text-slate-800 mb-4">Riwayat Gaji</h2>
                <div class="space-y-3 max-h-72 overflow-y-auto">
                    @forelse($gajiHistory as $gaji)
                        <div class="p-3 bg-slate-50 rounded-xl">
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-medium text-slate-800">
                                    {{ $gaji->tanggal->translatedFormat('d F Y') }}
                                </span>
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                    {{ $gaji->status == 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ $gaji->status == 'paid' ? 'Dibayar' : 'Pending' }}
                                </span>
                            </div>
                            <p class="text-lg font-bold text-green-600">Rp
                                {{ number_format($gaji->nominal_gaji, 0, ',', '.') }}
                            </p>
                            @if ($gaji->paid_at)
                                <p class="text-xs text-slate-400 mt-1">
                                    {{ \Carbon\Carbon::parse($gaji->paid_at)->translatedFormat('d M Y') }}
                                </p>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="fas fa-money-bill-wave text-4xl text-slate-300 mb-3"></i>
                            <p class="text-sm text-slate-400">Belum ada riwayat gaji</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Manajemen Section -->
    <div class="mt-8">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
            <div class="p-6 border-b border-slate-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-bullhorn text-orange-600"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-slate-800">Informasi Manajemen</h2>
                            <p class="text-sm text-slate-500">Pengumuman dan informasi penting dari manajemen</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="divide-y divide-slate-100 max-h-[500px] overflow-y-auto">
                @forelse($informasiList as $informasi)
                    <div class="p-5 hover:bg-slate-50 transition-colors">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center shrink-0 mt-1">
                                <i class="fas fa-info-circle text-orange-500"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-4 mb-2">
                                    <div>
                                        <span
                                            class="text-xs font-mono text-orange-600 bg-orange-50 px-2 py-0.5 rounded">{{ $informasi->kode_informasi }}</span>
                                        <h3 class="font-semibold text-slate-800 mt-1">{{ $informasi->judul }}</h3>
                                    </div>
                                    <span
                                        class="text-xs text-slate-400 whitespace-nowrap">{{ $informasi->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-slate-600 line-clamp-2 mb-3">{{ $informasi->deskripsi }}</p>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2 text-xs text-slate-400">
                                        <i class="fas fa-user"></i>
                                        <span>{{ $informasi->creator->name ?? 'Admin' }}</span>
                                        <span class="mx-1">•</span>
                                        <i class="fas fa-calendar"></i>
                                        <span>{{ $informasi->created_at->format('d M Y') }}</span>
                                    </div>
                                    <a href="{{ route('informasi.show', $informasi->hashid) }}"
                                        class="text-sm text-orange-500 hover:text-orange-600 font-medium flex items-center gap-1 transition-colors">
                                        Baca Selengkapnya
                                        <i class="fas fa-arrow-right text-xs"></i>
                                    </a>
                                </div>
                                @if ($informasi->lampiran)
                                    <div
                                        class="mt-3 flex items-center gap-2 text-xs text-slate-500 bg-slate-100 px-3 py-2 rounded-lg w-fit">
                                        @php
                                            $ext = strtolower(pathinfo($informasi->lampiran, PATHINFO_EXTENSION));
                                            $iconClass = match ($ext) {
                                                'pdf' => 'fa-file-pdf text-red-500',
                                                'doc', 'docx' => 'fa-file-word text-blue-500',
                                                'xls', 'xlsx' => 'fa-file-excel text-green-500',
                                                'jpg', 'jpeg', 'png', 'gif' => 'fa-file-image text-purple-500',
                                                default => 'fa-file text-slate-500',
                                            };
                                        @endphp
                                        <i class="fas {{ $iconClass }}"></i>
                                        <span>Lampiran tersedia</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center">
                        <i class="fas fa-bullhorn text-4xl text-slate-300 mb-3"></i>
                        <p class="text-slate-400">Belum ada informasi dari manajemen</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

