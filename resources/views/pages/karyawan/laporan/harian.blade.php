@extends('layouts.app')

@section('title', 'Laporan Harian')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Laporan Harian</h1>
                <p class="text-gray-600 mt-1">
                    @if ($karyawan)
                        {{ $karyawan->nama_lengkap }} - {{ $karyawan->cabang->nama_cabang ?? '-' }}
                    @else
                        Laporan Anda
                    @endif
                </p>
            </div>
        </div>

        <!-- Filter Tanggal -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <form action="{{ route('karyawan.laporan.harian') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">Pilih Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" value="{{ $tanggal }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-orange focus:border-brand-orange">
                </div>
                <div class="flex items-end">
                    <button type="submit"
                        class="px-6 py-2 bg-transparent text-orange-600 hover:text-white font-medium rounded-lg hover:bg-orange-600 border border-orange-600 transition-colors">
                        <i class="fas fa-filter mr-2"></i> Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-900">
                    Transaksi {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Waktu</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Jenis</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Keterangan</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Jumlah</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($laporans as $laporan)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $laporan->created_at->format('H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $laporan->jenis == 'Pendapatan' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        <i
                                            class="fas {{ $laporan->jenis == 'Pendapatan' ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                                        {{ $laporan->jenis }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $laporan->kategori }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $laporan->keterangan }}</td>
                                <td
                                    class="px-6 py-4 text-right font-semibold {{ $laporan->jenis == 'Pendapatan' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $laporan->jenis == 'Pendapatan' ? '+' : '-' }} Rp
                                    {{ number_format($laporan->jumlah, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $statusColors = [
                                            'Draft' => 'bg-gray-100 text-gray-700',
                                            'Pending' => 'bg-yellow-100 text-yellow-700',
                                            'Approved' => 'bg-green-100 text-green-700',
                                            'Rejected' => 'bg-red-100 text-red-700',
                                        ];
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusColors[$laporan->status] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ $laporan->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="p-4 rounded-full bg-gray-100 mb-4">
                                            <i class="fas fa-receipt text-gray-400 text-3xl"></i>
                                        </div>
                                        <p class="text-gray-500 font-medium">Belum ada transaksi</p>
                                        <p class="text-gray-400 text-sm mt-1">Tidak ada transaksi pada tanggal ini</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

