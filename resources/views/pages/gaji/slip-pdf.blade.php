<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji - {{ $gaji->karyawan->nama_lengkap }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            color: #333;
            background: #fff;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            border-bottom: 3px double #f97316;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            color: #f97316;
            margin-bottom: 5px;
        }

        .header h2 {
            font-size: 16px;
            color: #333;
            font-weight: normal;
        }

        .header p {
            font-size: 11px;
            color: #666;
            margin-top: 5px;
        }

        .slip-info {
            background: #fff7ed;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .slip-info table {
            width: 100%;
        }

        .slip-info td {
            padding: 3px 0;
            vertical-align: top;
        }

        .slip-info td:first-child {
            width: 120px;
            color: #666;
        }

        .slip-info td:nth-child(2) {
            width: 10px;
            text-align: center;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #333;
            margin: 20px 0 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #e5e7eb;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .data-table th,
        .data-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        .data-table th {
            background: #f8fafc;
            font-weight: 600;
            color: #475569;
            font-size: 11px;
            text-transform: uppercase;
        }

        .data-table td:last-child {
            text-align: right;
        }

        .data-table th:last-child {
            text-align: right;
        }

        .data-table tfoot td {
            font-weight: bold;
            background: #f8fafc;
        }

        .summary-box {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            color: #fff;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }

        .summary-box .title {
            font-size: 12px;
            opacity: 0.9;
            margin-bottom: 5px;
        }

        .summary-box .amount {
            font-size: 28px;
            font-weight: bold;
        }

        .summary-table {
            width: 100%;
            margin-top: 15px;
        }

        .summary-table td {
            padding: 5px 0;
            color: rgba(255, 255, 255, 0.9);
        }

        .summary-table td:last-child {
            text-align: right;
        }

        .calculation {
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .calculation table {
            width: 100%;
        }

        .calculation td {
            padding: 5px 0;
        }

        .calculation td:last-child {
            text-align: right;
        }

        .calculation .total {
            border-top: 2px solid #e5e7eb;
            padding-top: 10px;
            margin-top: 5px;
            font-weight: bold;
            font-size: 14px;
        }

        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 10px;
            color: #666;
        }

        .signatures {
            display: table;
            width: 100%;
            margin-top: 40px;
        }

        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 0 20px;
        }

        .signature-line {
            border-top: 1px solid #333;
            margin-top: 10px;
            padding-top: 5px;
        }

        .signature-image {
            height: 60px;
            max-width: 120px;
            margin: 0 auto;
            display: block;
        }

        .no-signature {
            height: 60px;
            margin-top: 0;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 11px;
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
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>KEBAB IKHWAN</h1>
            <h2>SLIP GAJI KARYAWAN</h2>
            <p>Periode: {{ $gaji->tanggal->format('d F Y') }}</p>
        </div>

        <!-- Employee Info -->
        <div class="slip-info">
            <table>
                <tr>
                    <td>Nama Karyawan</td>
                    <td>:</td>
                    <td><strong>{{ $gaji->karyawan->nama_lengkap ?? '-' }}</strong></td>
                </tr>
                <tr>
                    <td>No. Telepon</td>
                    <td>:</td>
                    <td>{{ $gaji->karyawan->no_telepon ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Cabang</td>
                    <td>:</td>
                    <td>{{ $gaji->cabang->nama_cabang ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Tanggal Gaji</td>
                    <td>:</td>
                    <td>{{ $gaji->tanggal->format('d F Y') }}</td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>:</td>
                    <td>
                        <span class="status-badge {{ $gaji->status === 'paid' ? 'status-paid' : 'status-pending' }}">
                            {{ $gaji->status === 'paid' ? 'SUDAH DIBAYAR' : 'BELUM DIBAYAR' }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Detail Transaksi -->
        <div class="section-title">Detail Transaksi Pemasukan</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Laporan</th>
                    <th>Kategori</th>
                    <th>Keterangan</th>
                    <th>Nominal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laporans as $index => $laporan)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $laporan->kode_laporan }}</td>
                        <td>{{ $laporan->kategori ?? '-' }}</td>
                        <td>{{ $laporan->keterangan ?? '-' }}</td>
                        <td>Rp {{ number_format($laporan->jumlah, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: #666;">Tidak ada data transaksi</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" style="text-align: right;">Total Pemasukan:</td>
                    <td>Rp {{ number_format($gaji->total_pemasukan, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <!-- Calculation -->
        <div class="calculation">
            <table>
                <tr>
                    <td>Total Pemasukan</td>
                    <td>Rp {{ number_format($gaji->total_pemasukan, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Persentase Gaji</td>
                    <td>× {{ $gaji->persen_gaji }}%</td>
                </tr>
                <tr class="total">
                    <td>NOMINAL GAJI</td>
                    <td style="color: #f97316;">Rp {{ number_format($gaji->nominal_gaji, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <!-- Summary Box -->
        <div class="summary-box">
            <div class="title">GAJI YANG DITERIMA</div>
            <div class="amount">Rp {{ number_format($gaji->nominal_gaji, 0, ',', '.') }}</div>
            <table class="summary-table">
                <tr>
                    <td>Jumlah Transaksi</td>
                    <td>{{ $gaji->jumlah_transaksi }} transaksi</td>
                </tr>
                @if ($gaji->catatan)
                    <tr>
                        <td>Catatan</td>
                        <td>{{ $gaji->catatan }}</td>
                    </tr>
                @endif
            </table>
        </div>

        <!-- Signatures -->
        <div class="signatures">
            <div class="signature-box">
                <p>Diterima oleh,</p>
                @if ($gaji->karyawan->user && $gaji->karyawan->user->ttd)
                    <img src="{{ $gaji->karyawan->user->ttd }}" alt="Tanda Tangan" class="signature-image">
                @else
                    <div class="no-signature"></div>
                @endif
                <div class="signature-line">
                    {{ $gaji->karyawan->nama_lengkap ?? '-' }}
                </div>
                <p style="font-size: 10px; color: #666;">Karyawan</p>
            </div>
            <div class="signature-box">
                <p>Disetujui oleh,</p>
                @if (isset($owner) && $owner && $owner->ttd)
                    <img src="{{ $owner->ttd }}" alt="Tanda Tangan" class="signature-image">
                @else
                    <div class="no-signature"></div>
                @endif
                <div class="signature-line">
                    {{ $owner->name ?? ($gaji->approver->name ?? 'Owner UMKM Kebab Ikhwan') }}
                </div>
                <p style="font-size: 10px; color: #666;">Owner UMKM Kebab Ikhwan</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Slip gaji ini diterbitkan secara elektronik dan sah tanpa tanda tangan basah.</p>
            <p>Dicetak pada: {{ now()->format('d F Y, H:i') }} WIB</p>
            <p style="margin-top: 10px; color: #f97316;"><strong>KEBAB IKHWAN</strong> - Sistem Informasi Keuangan</p>
        </div>
    </div>
</body>

</html>
