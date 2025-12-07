@extends('layouts.app')

@section('title', 'Detail Gaji - ' . $gaji->tanggal->format('d M Y'))

@section('page-header')
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-sm text-slate-500 mb-2">
                <a href="{{ route('karyawan.gaji.index') }}" class="hover:text-orange-600 transition-colors">Data Gaji
                    Saya</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-slate-700">Detail</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-800">Detail Gaji</h1>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('karyawan.gaji.slip-pdf', $gaji->hashid) }}" target="_blank"
                class="px-4 py-2.5 bg-gradient-to-r from-red-500 to-red-600 text-white text-sm font-medium rounded-xl hover:from-red-600 hover:to-red-700 shadow-lg shadow-red-500/30 transition-all duration-200">
                <i class="fas fa-file-pdf mr-2"></i>
                Download Slip Gaji
            </a>
            <a href="{{ route('karyawan.gaji.index') }}"
                class="px-4 py-2.5 bg-slate-100 text-slate-700 text-sm font-medium rounded-xl hover:bg-slate-200 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Info Gaji -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Header Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 bg-gradient-to-r from-emerald-50 to-teal-50 border-b border-slate-100">
                    <div class="flex items-start gap-4">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-500/30">
                            <i class="fas fa-money-bill-wave text-white text-2xl"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                @php $badge = $gaji->status_badge; @endphp
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    {{ $badge['color'] === 'green' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $badge['color'] === 'yellow' ? 'bg-yellow-100 text-yellow-700' : '' }}">
                                    {{ $badge['text'] }}
                                </span>
                            </div>
                            <h2 class="text-2xl font-bold text-slate-800">Rp
                                {{ number_format($gaji->nominal_gaji, 0, ',', '.') }}</h2>
                            <p class="text-slate-500 mt-1">Gaji untuk tanggal {{ $gaji->tanggal->format('d F Y') }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="p-4 bg-slate-50 rounded-xl">
                            <p class="text-sm text-slate-500 mb-1">Total Pemasukan</p>
                            <p class="text-lg font-bold text-slate-800">Rp
                                {{ number_format($gaji->total_pemasukan, 0, ',', '.') }}</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-xl">
                            <p class="text-sm text-slate-500 mb-1">Persentase Gaji</p>
                            <p class="text-lg font-bold text-slate-800">{{ $gaji->persen_gaji }}%</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-xl">
                            <p class="text-sm text-slate-500 mb-1">Jumlah Transaksi</p>
                            <p class="text-lg font-bold text-slate-800">{{ $gaji->jumlah_transaksi }}</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-xl">
                            <p class="text-sm text-slate-500 mb-1">Cabang</p>
                            <p class="text-lg font-bold text-slate-800">{{ $gaji->cabang->nama_cabang ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Transaksi -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800">Detail Transaksi Pemasukan</h3>
                    <p class="text-sm text-slate-500">Laporan yang di-approve pada tanggal
                        {{ $gaji->tanggal->format('d M Y') }}</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Kode</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Kategori</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Keterangan
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Nominal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($laporans as $laporan)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-4">
                                        <span class="font-mono text-sm text-orange-600">{{ $laporan->kode_laporan }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600">{{ $laporan->kategori ?? '-' }}</td>
                                    <td class="px-6 py-4 text-slate-600">{{ $laporan->keterangan ?? '-' }}</td>
                                    <td class="px-6 py-4 text-right font-medium text-slate-800">Rp
                                        {{ number_format($laporan->nominal, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-slate-500">
                                        Tidak ada data transaksi
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-slate-50">
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right font-bold text-slate-700">Total Pemasukan:
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-emerald-600">Rp
                                    {{ number_format($gaji->total_pemasukan, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status & History -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="font-bold text-slate-800">Status Pembayaran</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-full flex items-center justify-center
                            {{ $gaji->status === 'paid' ? 'bg-green-100' : 'bg-yellow-100' }}">
                            <i
                                class="fas {{ $gaji->status === 'paid' ? 'fa-check text-green-600' : 'fa-clock text-yellow-600' }}"></i>
                        </div>
                        <div>
                            <p class="font-medium text-slate-800">
                                {{ $gaji->status === 'paid' ? 'Sudah Dibayar' : 'Menunggu Pembayaran' }}</p>
                            @if ($gaji->paid_at)
                                <p class="text-sm text-slate-500">{{ $gaji->paid_at->format('d M Y, H:i') }}</p>
                            @endif
                        </div>
                    </div>

                    @if ($gaji->approver)
                        <div class="pt-4 border-t border-slate-100">
                            <p class="text-sm text-slate-500 mb-2">Dibayar oleh:</p>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span
                                        class="text-blue-600 font-semibold text-sm">{{ substr($gaji->approver->name, 0, 1) }}</span>
                                </div>
                                <span class="text-slate-700">{{ $gaji->approver->name }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Perhitungan -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="font-bold text-slate-800">Perhitungan Gaji</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Total Pemasukan</span>
                            <span class="text-slate-700">Rp {{ number_format($gaji->total_pemasukan, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Persentase</span>
                            <span class="text-slate-700">× {{ $gaji->persen_gaji }}%</span>
                        </div>
                        <div class="pt-3 border-t border-slate-100 flex justify-between">
                            <span class="font-bold text-slate-700">Gaji</span>
                            <span class="font-bold text-emerald-600">Rp
                                {{ number_format($gaji->nominal_gaji, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timestamp -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6">
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Dibuat</span>
                            <span class="text-slate-700">{{ $gaji->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        @if ($gaji->updated_at->ne($gaji->created_at))
                            <div class="flex justify-between">
                                <span class="text-slate-500">Diperbarui</span>
                                <span class="text-slate-700">{{ $gaji->updated_at->format('d M Y, H:i') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
