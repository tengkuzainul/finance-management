@extends('layouts.app')

@section('title', 'Data Gaji Saya')

@section('page-header')
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-800">Data Gaji Saya</h1>
            <p class="text-slate-500 mt-1">Riwayat dan slip gaji Anda</p>
        </div>
    </div>
@endsection

@section('content')
    <!-- Filter Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-6">
        <form method="GET" action="{{ route('karyawan.gaji.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Bulan</label>
                <select name="bulan"
                    class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
                    <option value="">Semua Bulan</option>
                    @foreach (['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $i => $bulan)
                        <option value="{{ $i + 1 }}" {{ request('bulan') == $i + 1 ? 'selected' : '' }}>
                            {{ $bulan }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Tahun</label>
                <select name="tahun"
                    class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
                    @for ($year = date('Y'); $year >= date('Y') - 5; $year--)
                        <option value="{{ $year }}" {{ request('tahun', date('Y')) == $year ? 'selected' : '' }}>
                            {{ $year }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                <select name="status"
                    class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Belum Dibayar</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Sudah Dibayar</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit"
                    class="flex-1 px-4 py-2.5 bg-gradient-to-r from-orange-500 to-orange-600 text-white text-sm font-medium rounded-xl hover:from-orange-600 hover:to-orange-700 transition-all">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
                <a href="{{ route('karyawan.gaji.index') }}"
                    class="px-4 py-2.5 bg-slate-100 text-slate-700 text-sm font-medium rounded-xl hover:bg-slate-200 transition-colors">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/30">
                    <i class="fas fa-money-bill-wave text-white text-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Gaji Bulan Ini</p>
                    <p class="text-xl font-bold text-slate-800">Rp
                        {{ number_format($summary['total_gaji_bulan_ini'] ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg shadow-green-500/30">
                    <i class="fas fa-check-circle text-white text-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Sudah Dibayar</p>
                    <p class="text-xl font-bold text-slate-800">Rp
                        {{ number_format($summary['total_gaji_dibayar'] ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg shadow-yellow-500/30">
                    <i class="fas fa-clock text-white text-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Belum Dibayar</p>
                    <p class="text-xl font-bold text-slate-800">Rp
                        {{ number_format($summary['total_gaji_pending'] ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                    <i class="fas fa-file-invoice text-white text-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Total Slip</p>
                    <p class="text-xl font-bold text-slate-800">{{ $gajis->total() ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Cabang
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Total
                            Pemasukan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Persen
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            Nominal Gaji</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($gajis as $gaji)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-medium text-slate-800">{{ $gaji->tanggal->format('d M Y') }}</p>
                                    <p class="text-sm text-slate-500">{{ $gaji->tanggal->format('l') }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ $gaji->cabang->nama_cabang ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="font-medium text-slate-800">Rp
                                    {{ number_format($gaji->total_pemasukan, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ $gaji->persen_gaji }}%</td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-emerald-600">Rp
                                    {{ number_format($gaji->nominal_gaji, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php $badge = $gaji->status_badge; @endphp
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                    {{ $badge['color'] === 'green' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $badge['color'] === 'yellow' ? 'bg-yellow-100 text-yellow-700' : '' }}">
                                    {{ $badge['text'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('karyawan.gaji.show', $gaji->hashid) }}"
                                        class="p-2 text-slate-400 hover:text-orange-600 hover:bg-orange-50 rounded-lg transition-colors"
                                        title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('karyawan.gaji.slip-pdf', $gaji->hashid) }}" target="_blank"
                                        class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                        title="Download Slip">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-money-bill-wave text-slate-400 text-3xl"></i>
                                    </div>
                                    <h3 class="text-lg font-semibold text-slate-800 mb-1">Belum Ada Data Gaji</h3>
                                    <p class="text-slate-500">Data gaji Anda akan muncul di sini</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($gajis->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $gajis->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection
