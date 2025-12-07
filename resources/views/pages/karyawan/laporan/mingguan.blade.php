@extends('layouts.app')

@section('title', 'Laporan Mingguan')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Laporan Mingguan</h1>
                <p class="text-gray-600 mt-1">
                    @if ($karyawan)
                        {{ $karyawan->nama_lengkap }} - {{ $karyawan->cabang->nama_cabang ?? '-' }}
                    @else
                        Laporan Anda
                    @endif
                </p>
            </div>
        </div>

        <!-- Filter -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <form action="{{ route('karyawan.laporan.mingguan') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1 grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Minggu Ke</label>
                        <select name="minggu" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            @for ($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ $mingguKe == $i ? 'selected' : '' }}>Minggu
                                    {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                        <select name="bulan" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            @foreach (range(1, 12) as $m)
                                <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                        <select name="tahun" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            @foreach (range(date('Y') - 2, date('Y')) as $y)
                                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>
                                    {{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex items-end">
                    <button type="submit"
                        class="px-6 py-2 bg-transparent text-orange-600 hover:text-white font-medium rounded-lg hover:bg-orange-600 border border-orange-600 transition-colors">
                        <i class="fas fa-filter mr-2"></i> Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Period Info -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
            <div class="flex items-center gap-3">
                <i class="fas fa-calendar-week text-blue-500"></i>
                <p class="text-blue-700">
                    <span class="font-medium">Periode:</span>
                    {{ $startOfWeek->translatedFormat('d M Y') }} - {{ $endOfWeek->translatedFormat('d M Y') }}
                </p>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-green-100">
                        <i class="fas fa-arrow-up text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Pemasukan</p>
                        <p class="text-xl font-bold text-green-600">Rp
                            {{ number_format($summary['total_pemasukan'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-red-100">
                        <i class="fas fa-arrow-down text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Pengeluaran</p>
                        <p class="text-xl font-bold text-red-600">Rp
                            {{ number_format($summary['total_pengeluaran'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg {{ $summary['saldo'] >= 0 ? 'bg-blue-100' : 'bg-orange-100' }}">
                        <i
                            class="fas fa-wallet {{ $summary['saldo'] >= 0 ? 'text-blue-600' : 'text-orange-600' }} text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Saldo</p>
                        <p class="text-xl font-bold {{ $summary['saldo'] >= 0 ? 'text-blue-600' : 'text-orange-600' }}">Rp
                            {{ number_format($summary['saldo'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daily Breakdown -->
        @forelse($groupedLaporans as $date => $dailyLaporans)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-semibold text-gray-900">
                        {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}
                    </h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach ($dailyLaporans as $laporan)
                        <div class="px-6 py-4 flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-10 h-10 rounded-lg flex items-center justify-center {{ $laporan->jenis == 'Pemasukan' ? 'bg-green-100' : 'bg-red-100' }}">
                                    <i
                                        class="fas {{ $laporan->jenis == 'Pemasukan' ? 'fa-arrow-up text-green-600' : 'fa-arrow-down text-red-600' }}"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $laporan->keterangan }}</p>
                                    <p class="text-sm text-gray-500">{{ $laporan->kategori }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p
                                    class="font-semibold {{ $laporan->jenis == 'Pemasukan' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $laporan->jenis == 'Pemasukan' ? '+' : '-' }} Rp
                                    {{ number_format($laporan->jumlah, 0, ',', '.') }}
                                </p>
                                @php
                                    $statusColors = [
                                        'Draft' => 'bg-gray-100 text-gray-700',
                                        'Pending' => 'bg-yellow-100 text-yellow-700',
                                        'Approved' => 'bg-green-100 text-green-700',
                                        'Rejected' => 'bg-red-100 text-red-700',
                                    ];
                                @endphp
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $statusColors[$laporan->status] ?? '' }}">
                                    {{ $laporan->status }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                <div class="p-4 rounded-full bg-gray-100 inline-flex mb-4">
                    <i class="fas fa-receipt text-gray-400 text-3xl"></i>
                </div>
                <p class="text-gray-500 font-medium">Belum ada transaksi</p>
                <p class="text-gray-400 text-sm mt-1">Tidak ada transaksi pada minggu ini</p>
            </div>
        @endforelse
    </div>
@endsection
