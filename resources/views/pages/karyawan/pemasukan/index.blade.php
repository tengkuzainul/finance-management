@extends('layouts.app')

@section('title', 'Pendapatan Saya')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') }}"
                    class="w-10 h-10 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-600 hover:bg-slate-50 transition-colors">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Pendapatan Saya</h1>
                    <p class="text-gray-600 mt-1">Riwayat Pendapatan yang pernah Anda input</p>
                </div>
            </div>
            <a href="{{ route('karyawan.Pendapatan.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-green-500 to-green-600 text-white text-sm font-medium rounded-xl hover:from-green-600 hover:to-green-700 shadow-lg shadow-green-500/30 transition-all duration-200">
                <i class="fas fa-plus"></i>
                <span>Input Pendapatan</span>
            </a>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-receipt text-blue-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Total Transaksi</p>
                        <p class="text-xl font-bold text-slate-800">{{ $summary['total'] }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Approved</p>
                        <p class="text-xl font-bold text-green-600">{{ $summary['approved'] }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Pending</p>
                        <p class="text-xl font-bold text-yellow-600">{{ $summary['pending'] }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-file-alt text-slate-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Draft</p>
                        <p class="text-xl font-bold text-slate-600">{{ $summary['draft'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Approved -->
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-200 p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-wallet text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-green-600">Total Pendapatan Approved</p>
                        <p class="text-2xl font-bold text-green-800">Rp
                            {{ number_format($summary['total_nilai'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-xl border border-slate-200 p-4">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Dari Tanggal</label>
                    <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Sampai Tanggal</label>
                    <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Status</label>
                    <select name="status"
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Semua Status</option>
                        <option value="Draft" {{ request('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                        <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Kategori</label>
                    <select name="kategori"
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Semua Kategori</option>
                        @foreach ($kategoriList as $kat)
                            <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>
                                {{ $kat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-green-500 text-white text-sm font-medium rounded-lg hover:bg-green-600 transition-colors">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>
                    <a href="{{ route('karyawan.Pendapatan.index') }}"
                        class="px-4 py-2 bg-slate-100 text-slate-600 text-sm font-medium rounded-lg hover:bg-slate-200 transition-colors">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            @if ($laporans->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Tanggal</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Kategori</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Keterangan
                                </th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-slate-600 uppercase">Jumlah</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-slate-600 uppercase">Status</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-slate-600 uppercase">Bukti</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($laporans as $laporan)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-3 text-sm text-slate-600">
                                        {{ \Carbon\Carbon::parse($laporan->tanggal)->format('d M Y') }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-slate-800 font-medium">{{ $laporan->kategori }}</td>
                                    <td class="px-4 py-3 text-sm text-slate-600">
                                        <span class="line-clamp-1">{{ $laporan->keterangan }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-right font-semibold text-green-600">
                                        Rp {{ number_format($laporan->jumlah, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @php
                                            $statusConfig = [
                                                'Draft' => ['bg' => 'bg-slate-100', 'text' => 'text-slate-700'],
                                                'Pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700'],
                                                'Approved' => ['bg' => 'bg-green-100', 'text' => 'text-green-700'],
                                                'Rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-700'],
                                            ];
                                            $sc = $statusConfig[$laporan->status] ?? [
                                                'bg' => 'bg-slate-100',
                                                'text' => 'text-slate-700',
                                            ];
                                        @endphp
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $sc['bg'] }} {{ $sc['text'] }}">
                                            {{ $laporan->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if ($laporan->bukti_transaksi)
                                            <a href="{{ Storage::url($laporan->bukti_transaksi) }}" target="_blank"
                                                class="inline-flex items-center justify-center w-8 h-8 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors">
                                                <i class="fas fa-eye text-sm"></i>
                                            </a>
                                        @else
                                            <span class="text-slate-300">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-4 py-3 border-t border-slate-100">
                    {{ $laporans->withQueryString()->links() }}
                </div>
            @else
                <div class="p-8 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-slate-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-inbox text-slate-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-slate-800 mb-1">Belum ada Pendapatan</h3>
                    <p class="text-slate-500 mb-4">Mulai input Pendapatan pertama Anda</p>
                    <a href="{{ route('karyawan.Pendapatan.create') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-green-500 text-white text-sm font-medium rounded-lg hover:bg-green-600 transition-colors">
                        <i class="fas fa-plus"></i>
                        <span>Input Pendapatan</span>
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection

