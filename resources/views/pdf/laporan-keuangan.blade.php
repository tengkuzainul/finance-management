<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan - UMKM Kebab Ikhwan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #333;
        }

        .container {
            padding: 20px;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 3px solid #f97316;
        }

        .header h1 {
            font-size: 20px;
            color: #f97316;
            margin-bottom: 5px;
        }

        .header h2 {
            font-size: 16px;
            color: #333;
            margin-bottom: 5px;
        }

        .header p {
            color: #666;
            font-size: 10px;
        }

        /* Period Info */
        .period-info {
            background-color: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .period-info table {
            width: 100%;
        }

        .period-info td {
            padding: 3px 0;
        }

        .period-info .label {
            font-weight: bold;
            width: 150px;
        }

        /* Summary Cards */
        .summary {
            margin-bottom: 25px;
        }

        .summary table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary td {
            padding: 10px 15px;
            text-align: center;
            width: 33.33%;
        }

        .summary .pemasukan {
            background-color: #dcfce7;
            border-radius: 5px 0 0 5px;
        }

        .summary .pengeluaran {
            background-color: #fee2e2;
        }

        .summary .saldo {
            background-color: #dbeafe;
            border-radius: 0 5px 5px 0;
        }

        .summary .amount {
            font-size: 14px;
            font-weight: bold;
            margin-top: 5px;
        }

        .summary .pemasukan .amount {
            color: #16a34a;
        }

        .summary .pengeluaran .amount {
            color: #dc2626;
        }

        .summary .saldo .amount {
            color: #2563eb;
        }

        /* Data Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .data-table th {
            background-color: #f97316;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-weight: bold;
            font-size: 10px;
        }

        .data-table th:first-child {
            border-radius: 5px 0 0 0;
        }

        .data-table th:last-child {
            border-radius: 0 5px 0 0;
        }

        .data-table td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
        }

        .data-table tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .data-table .text-right {
            text-align: right;
        }

        .data-table .text-center {
            text-align: center;
        }

        .pemasukan-text {
            color: #16a34a;
        }

        .pengeluaran-text {
            color: #dc2626;
        }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
        }

        .badge-pemasukan {
            background-color: #dcfce7;
            color: #16a34a;
        }

        .badge-pengeluaran {
            background-color: #fee2e2;
            color: #dc2626;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
        }

        .footer table {
            width: 100%;
        }

        .footer .signature {
            text-align: center;
            width: 200px;
        }

        .footer .signature-line {
            border-bottom: 1px solid #333;
            margin: 10px auto 5px;
            width: 150px;
        }

        .footer .signature-image {
            height: 50px;
            max-width: 120px;
            margin: 0 auto;
            display: block;
        }

        .footer .no-signature {
            height: 50px;
        }

        .footer .date {
            text-align: right;
            margin-bottom: 20px;
        }

        .page-break {
            page-break-after: always;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>UMKM KEBAB IKHWAN</h1>
            <h2>LAPORAN KEUANGAN</h2>
            <p>Sistem Informasi Keuangan - Laporan {{ $jenisFilter ?? 'Semua Transaksi' }}</p>
        </div>

        <!-- Period Info -->
        <div class="period-info">
            <table>
                <tr>
                    <td class="label">Periode Laporan</td>
                    <td>: {{ $tanggalMulai->format('d F Y') }} - {{ $tanggalAkhir->format('d F Y') }}</td>
                </tr>
                @if ($cabang)
                    <tr>
                        <td class="label">Cabang</td>
                        <td>: {{ $cabang->nama_cabang }} ({{ $cabang->kode_cabang }})</td>
                    </tr>
                @else
                    <tr>
                        <td class="label">Cabang</td>
                        <td>: Semua Cabang</td>
                    </tr>
                @endif
                @if ($jenisFilter)
                    <tr>
                        <td class="label">Jenis Transaksi</td>
                        <td>: {{ $jenisFilter }}</td>
                    </tr>
                @else
                    <tr>
                        <td class="label">Jenis Transaksi</td>
                        <td>: Semua Jenis</td>
                    </tr>
                @endif
                <tr>
                    <td class="label">Tanggal Cetak</td>
                    <td>: {{ now()->format('d F Y H:i') }}</td>
                </tr>
            </table>
        </div>

        <!-- Summary -->
        <div class="summary">
            <table>
                <tr>
                    <td class="pemasukan">
                        <div>Total Pemasukan</div>
                        <div class="amount">Rp {{ number_format($summary['total_pemasukan'], 0, ',', '.') }}</div>
                    </td>
                    <td class="pengeluaran">
                        <div>Total Pengeluaran</div>
                        <div class="amount">Rp {{ number_format($summary['total_pengeluaran'], 0, ',', '.') }}</div>
                    </td>
                    <td class="pengeluaran">
                        <div>Total Gaji Dibayar</div>
                        <div class="amount">Rp {{ number_format($summary['total_gaji'] ?? 0, 0, ',', '.') }}</div>
                    </td>
                    <td class="saldo">
                        <div>Saldo Final</div>
                        <div class="amount">Rp {{ number_format($summary['saldo'], 0, ',', '.') }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Data Table -->
        @if ($laporans->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 30px;">No</th>
                        <th style="width: 70px;">Tanggal</th>
                        <th>Keterangan</th>
                        <th style="width: 80px;">Kategori</th>
                        <th style="width: 80px;">Cabang</th>
                        <th style="width: 60px;" class="text-center">Jenis</th>
                        <th style="width: 100px;" class="text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($laporans as $index => $laporan)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $laporan->tanggal->format('d/m/Y') }}</td>
                            <td>{{ Str::limit($laporan->keterangan, 40) }}</td>
                            <td>{{ $laporan->kategori }}</td>
                            <td>{{ $laporan->cabang->nama_cabang ?? '-' }}</td>
                            <td class="text-center">
                                <span
                                    class="badge {{ $laporan->jenis == 'Pemasukan' ? 'badge-pemasukan' : 'badge-pengeluaran' }}">
                                    {{ $laporan->jenis }}
                                </span>
                            </td>
                            <td
                                class="text-right {{ $laporan->jenis == 'Pemasukan' ? 'pemasukan-text' : 'pengeluaran-text' }}">
                                {{ $laporan->jenis == 'Pemasukan' ? '+' : '-' }} Rp
                                {{ number_format($laporan->jumlah, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background-color: #f3f4f6; font-weight: bold;">
                        <td colspan="6" class="text-right" style="padding: 10px;">Total:</td>
                        <td class="text-right" style="padding: 10px;">
                            @php
                                $totalPemasukan = $laporans->where('jenis', 'Pemasukan')->sum('jumlah');
                                $totalPengeluaran = $laporans->where('jenis', 'Pengeluaran')->sum('jumlah');
                            @endphp
                            <div class="pemasukan-text">+ Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
                            <div class="pengeluaran-text">- Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        @else
            <div class="empty-state">
                <p>Tidak ada data laporan keuangan untuk periode ini.</p>
            </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <div class="date">
                {{ $cabang ? $cabang->alamat_lengkap : 'Jakarta' }}, {{ now()->format('d F Y') }}
            </div>
            <table>
                <tr>
                    <td></td>
                    <td></td>
                    <td class="signature">
                        <div>Mengetahui,</div>
                        @if (isset($owner) && $owner && $owner->ttd)
                            <img src="{{ $owner->ttd }}" alt="Tanda Tangan" class="signature-image">
                        @else
                            <div class="no-signature"></div>
                        @endif
                        <div class="signature-line"></div>
                        <div><strong>{{ $owner->name ?? 'Owner UMKM Kebab Ikhwan' }}</strong></div>
                        <div style="font-size: 9px; color: #666;">Owner UMKM Kebab Ikhwan</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>
