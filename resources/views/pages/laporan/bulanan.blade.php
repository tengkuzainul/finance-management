@extends('layouts.app')

@section('title', 'Laporan Bulanan')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Laporan Bulanan</h1>
                <p class="text-gray-600 mt-1">Laporan transaksi bulanan semua cabang</p>
            </div>
            <div class="flex items-center gap-2">
                @php
                    $startMonth = \Carbon\Carbon::create($tahun, $bulan, 1)->startOfMonth()->format('Y-m-d');
                    $endMonth = \Carbon\Carbon::create($tahun, $bulan, 1)->endOfMonth()->format('Y-m-d');
                @endphp
                <a href="{{ route('master-data.laporan-keuangan.export-pdf', ['tanggal_mulai' => $startMonth, 'tanggal_akhir' => $endMonth]) }}"
                    class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 flex items-center gap-2">
                    <i class="fas fa-file-pdf"></i>
                    <span>Export PDF</span>
                </a>
            </div>
        </div>

        <!-- Filter -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <form action="{{ route('laporan.bulanan') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1 grid grid-cols-1 sm:grid-cols-3 gap-4">
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
                                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cabang</label>
                        <select name="cabang_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            <option value="">Semua Cabang</option>
                            @foreach ($cabangs as $cabang)
                                <option value="{{ $cabang->hash_id }}" {{ $cabangId == $cabang->id ? 'selected' : '' }}>
                                    {{ $cabang->nama_cabang }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="px-6 py-2 bg-transparent text-orange-600 hover:text-white font-medium rounded-lg hover:bg-orange-600 border border-orange-600 transition-colors">
                        <i class="fas fa-filter mr-2"></i> Filter
                    </button>
                    <a href="{{ route('laporan.bulanan') }}"
                        class="px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300">
                        <i class="fas fa-undo"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Period Info -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
            <div class="flex items-center gap-3">
                <i class="fas fa-calendar-alt text-blue-500"></i>
                <p class="text-blue-700">
                    <span class="font-medium">Periode:</span>
                    {{ \Carbon\Carbon::create($tahun, $bulan, 1)->translatedFormat('F Y') }}
                </p>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-green-100">
                        <i class="fas fa-arrow-up text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Pendapatan</p>
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
                    <div class="p-3 rounded-lg bg-purple-100">
                        <i class="fas fa-money-bill-wave text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Gaji Dibayar</p>
                        <p class="text-xl font-bold text-purple-600">Rp
                            {{ number_format($summary['total_gaji'] ?? 0, 0, ',', '.') }}</p>
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
                        <p class="text-sm font-medium text-gray-500">Saldo Final</p>
                        <p class="text-xl font-bold {{ $summary['saldo'] >= 0 ? 'text-blue-600' : 'text-orange-600' }}">Rp
                            {{ number_format($summary['saldo'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-indigo-100">
                        <i class="fas fa-receipt text-indigo-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Jumlah Transaksi</p>
                        <p class="text-xl font-bold text-indigo-600">{{ $summary['jumlah_transaksi'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary per Cabang -->
        @if ($cabangStats->isNotEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900">Ringkasan Per Cabang</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Cabang</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pendapatan</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pengeluaran</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Saldo</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Transaksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($cabangStats as $stat)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center mr-3">
                                                <i class="fas fa-store text-blue-600"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $stat['cabang']->nama_cabang ?? '-' }}</p>
                                                <p class="text-xs text-gray-500">
                                                    {{ $stat['cabang']->alamat_lengkap ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-right text-green-600 font-medium">
                                        Rp {{ number_format($stat['Pendapatan'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-right text-red-600 font-medium">
                                        Rp {{ number_format($stat['pengeluaran'], 0, ',', '.') }}
                                    </td>
                                    <td
                                        class="px-6 py-4 text-sm text-right {{ $stat['saldo'] >= 0 ? 'text-blue-600' : 'text-orange-600' }} font-medium">
                                        Rp {{ number_format($stat['saldo'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-center">{{ $stat['transaksi'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Weekly Breakdown -->
        @if ($weeklyStats->isNotEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900">Ringkasan Per Minggu</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Minggu</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pendapatan</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pengeluaran</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Saldo</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Transaksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($weeklyStats as $week)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">Minggu {{ $week['minggu'] }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-right text-green-600 font-medium">
                                        Rp {{ number_format($week['Pendapatan'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-right text-red-600 font-medium">
                                        Rp {{ number_format($week['pengeluaran'], 0, ',', '.') }}
                                    </td>
                                    <td
                                        class="px-6 py-4 text-sm text-right {{ $week['saldo'] >= 0 ? 'text-blue-600' : 'text-orange-600' }} font-medium">
                                        Rp {{ number_format($week['saldo'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-center">{{ $week['transaksi'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Transaction List -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Daftar Transaksi</h3>
            </div>
            @if ($laporans->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Cabang</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Karyawan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jenis</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kategori</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Keterangan</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($laporans as $laporan)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $laporan->tanggal->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-store mr-1"></i>
                                            {{ $laporan->cabang->nama_cabang ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center mr-2">
                                                <i class="fas fa-user text-gray-500 text-xs"></i>
                                            </div>
                                            <span
                                                class="text-sm text-gray-900">{{ $laporan->karyawan->nama_lengkap ?? ($laporan->creator->name ?? '-') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $laporan->jenis == 'Pendapatan' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            <i
                                                class="fas {{ $laporan->jenis == 'Pendapatan' ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                                            {{ $laporan->jenis }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $laporan->kategori }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($laporan->keterangan, 30) }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium {{ $laporan->jenis == 'Pendapatan' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $laporan->jenis == 'Pendapatan' ? '+' : '-' }} Rp
                                        {{ number_format($laporan->jumlah, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-sm font-bold text-gray-900">Total</td>
                                <td
                                    class="px-6 py-4 text-right text-lg font-bold {{ $summary['saldo'] >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                                    Rp {{ number_format($summary['saldo'], 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="p-4 rounded-full bg-gray-100 inline-flex mb-4">
                        <i class="fas fa-receipt text-gray-400 text-3xl"></i>
                    </div>
                    <p class="text-gray-500 font-medium">Belum ada transaksi</p>
                    <p class="text-gray-400 text-sm mt-1">Tidak ada transaksi pada bulan ini</p>
                </div>
            @endif
        </div>
    </div>
@endsection

