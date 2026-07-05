<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Gaji - {{ $tanggal->format('d F Y') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            color: #1f2937;
            background: #fff;
        }

        .container {
            max-width: 100%;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            border-bottom: 3px double #f97316;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 22px;
            color: #f97316;
            margin-bottom: 5px;
        }

        .header h2 {
            font-size: 14px;
            color: #333;
            font-weight: normal;
        }

        .header p {
            font-size: 11px;
            color: #666;
            margin-top: 5px;
        }

        .info-box {
            background: #fff7ed;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .info-box table {
            width: 100%;
        }

        .info-box td {
            padding: 3px 0;
        }

        .info-box td:first-child {
            width: 150px;
            color: #666;
        }

        .summary-cards {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .summary-card {
            display: table-cell;
            width: 25%;
            padding: 5px;
        }

        .summary-card-inner {
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }

        .summary-card-inner.orange {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            color: #fff;
        }

        .summary-card-inner .label {
            font-size: 10px;
            color: #666;
            margin-bottom: 5px;
        }

        .summary-card-inner.orange .label {
            color: rgba(255, 255, 255, 0.9);
        }

        .summary-card-inner .value {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }

        .summary-card-inner.orange .value {
            color: #fff;
        }

        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #333;
            margin: 15px 0 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #e5e7eb;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .data-table th,
        .data-table td {
            padding: 10px 10px;
            text-align: left;
            border: 1px solid #e5e7eb;
        }

        .data-table th {
            background: #f97316;
            color: #fff;
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
        }

        .data-table td {
            font-size: 11px;
        }

        .data-table tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .data-table tbody tr:hover {
            background: #fff7ed;
        }

        .data-table td.number {
            text-align: right;
        }

        .data-table td.center {
            text-align: center;
        }

        .data-table tfoot td {
            font-weight: bold;
            background: #fef3c7;
            border-top: 2px solid #f97316;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-paid {
            background: #dcfce7;
            color: #166534;
        }

        .status-pending {
            background: #fef9c3;
            color: #854d0e;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
        }

        .signatures {
            display: table;
            width: 100%;
            margin-top: 30px;
        }

        .signature-box {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            padding: 0 15px;
        }

        .signature-line {
            border-top: 1px solid #333;
            margin-top: 10px;
            padding-top: 5px;
        }

        .signature-image {
            height: 50px;
            max-width: 100px;
            margin: 0 auto;
            display: block;
        }

        .no-signature {
            height: 50px;
            margin-top: 0;
        }

        .print-date {
            text-align: center;
            font-size: 10px;
            color: #666;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px dashed #e5e7eb;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>KEBAB IKHWAN</h1>
            <h2>REKAPITULASI PENGGAJIAN KARYAWAN</h2>
            <p>Tanggal: {{ $tanggal->format('d F Y') }}</p>
        </div>

        <!-- Info Box -->
        <div class="info-box">
            <table>
                <tr>
                    <td>Tanggal Rekap</td>
                    <td>: <strong>{{ $tanggal->format('d F Y') }}</strong></td>
                    <td style="width: 50px;"></td>
                    <td>Jumlah Karyawan</td>
                    <td>: <strong>{{ $gajis->count() }} orang</strong></td>
                </tr>
                <tr>
                    <td>Persentase Gaji</td>
                    <td>: <strong>{{ $persen }}%</strong></td>
                    <td></td>
                    <td>Total Transaksi</td>
                    <td>: <strong>{{ $gajis->sum('jumlah_transaksi') }} transaksi</strong></td>
                </tr>
            </table>
        </div>

        <!-- Summary Cards -->
        <div class="summary-cards">
            <div class="summary-card">
                <div class="summary-card-inner">
                    <div class="label">Total Pendapatan</div>
                    <div class="value">Rp {{ number_format($gajis->sum('total_pemasukan'), 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-card-inner orange">
                    <div class="label">Total Gaji</div>
                    <div class="value">Rp {{ number_format($gajis->sum('nominal_gaji'), 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-card-inner">
                    <div class="label">Sudah Dibayar</div>
                    <div class="value">Rp
                        {{ number_format($gajis->where('status', 'paid')->sum('nominal_gaji'), 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-card-inner">
                    <div class="label">Belum Dibayar</div>
                    <div class="value">Rp
                        {{ number_format($gajis->where('status', 'pending')->sum('nominal_gaji'), 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="section-title">Daftar Gaji Karyawan</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 30px;">No</th>
                    <th>Nama Karyawan</th>
                    <th>Cabang</th>
                    <th style="width: 50px;">Jml Trx</th>
                    <th>Total Pendapatan</th>
                    <th style="width: 45px;">Persen</th>
                    <th>Nominal Gaji</th>
                    <th style="width: 80px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($gajis as $index => $gaji)
                    <tr>
                        <td class="center">{{ $index + 1 }}</td>
                        <td>{{ $gaji->karyawan->nama_lengkap ?? '-' }}</td>
                        <td>{{ $gaji->cabang->nama_cabang ?? '-' }}</td>
                        <td class="center">{{ $gaji->jumlah_transaksi }}</td>
                        <td class="number">Rp {{ number_format($gaji->total_pemasukan, 0, ',', '.') }}</td>
                        <td class="center">{{ $gaji->persen_gaji }}%</td>
                        <td class="number">Rp {{ number_format($gaji->nominal_gaji, 0, ',', '.') }}</td>
                        <td class="center">
                            <span
                                class="status-badge {{ $gaji->status === 'paid' ? 'status-paid' : 'status-pending' }}">
                                {{ $gaji->status === 'paid' ? 'DIBAYAR' : 'PENDING' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; color: #666; padding: 20px;">
                            Tidak ada data gaji untuk tanggal ini
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align: right;">TOTAL:</td>
                    <td class="center">{{ $gajis->sum('jumlah_transaksi') }}</td>
                    <td class="number">Rp {{ number_format($gajis->sum('total_pemasukan'), 0, ',', '.') }}</td>
                    <td></td>
                    <td class="number" style="color: #f97316;">Rp
                        {{ number_format($gajis->sum('nominal_gaji'), 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        <!-- Summary by Status -->
        <div class="section-title">Ringkasan Status Pembayaran</div>
        <table class="data-table" style="width: 50%;">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Jumlah</th>
                    <th>Total Nominal</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><span class="status-badge status-paid">SUDAH DIBAYAR</span></td>
                    <td class="center">{{ $gajis->where('status', 'paid')->count() }} orang</td>
                    <td class="number">Rp
                        {{ number_format($gajis->where('status', 'paid')->sum('nominal_gaji'), 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td><span class="status-badge status-pending">BELUM DIBAYAR</span></td>
                    <td class="center">{{ $gajis->where('status', 'pending')->count() }} orang</td>
                    <td class="number">Rp
                        {{ number_format($gajis->where('status', 'pending')->sum('nominal_gaji'), 0, ',', '.') }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td><strong>TOTAL</strong></td>
                    <td class="center"><strong>{{ $gajis->count() }} orang</strong></td>
                    <td class="number"><strong>Rp
                            {{ number_format($gajis->sum('nominal_gaji'), 0, ',', '.') }}</strong></td>
                </tr>
            </tfoot>
        </table>

        <!-- Footer -->
        <div class="footer">
            <!-- Signatures -->
            <div class="signatures">
                <div class="signature-box">
                    <!-- Empty spacer -->
                </div>
                <div class="signature-box">
                    <!-- Empty spacer -->
                </div>
                <div class="signature-box">
                    <p>Disetujui oleh,</p>
                    @if (isset($owner) && $owner && $owner->ttd)
                        <img src="{{ $owner->ttd }}" alt="Tanda Tangan" class="signature-image">
                    @else
                        <div class="no-signature"></div>
                    @endif
                    <div class="signature-line">
                        {{ $owner->name ?? 'Owner UMKM Kebab Ikhwan' }}
                    </div>
                    <p style="font-size: 9px; color: #666;">Owner UMKM Kebab Ikhwan</p>
                </div>
            </div>

            <!-- Print Date -->
            <div class="print-date">
                <p>Dokumen ini dicetak secara elektronik pada {{ now()->format('d F Y, H:i') }} WIB</p>
                <p style="margin-top: 5px; color: #f97316;"><strong>KEBAB IKHWAN</strong> - Sistem Informasi Keuangan
                </p>
            </div>
        </div>
    </div>
</body>

</html>

