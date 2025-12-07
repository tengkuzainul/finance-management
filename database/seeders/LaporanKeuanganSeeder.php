<?php

namespace Database\Seeders;

use App\Models\Cabang;
use App\Models\Karyawan;
use App\Models\LaporanKeuangan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class LaporanKeuanganSeeder extends Seeder
{
   /**
    * Run the database seeds.
    */
   public function run(): void
   {
      $admin = User::where('username', 'admin')->first();

      // Data laporan keuangan sample
      $laporans = [
         // Cabang Pusat (KI-001)
         [
            'cabang_kode' => 'KI-001',
            'karyawan_nik' => 'KI-202501-001',
            'tanggal' => Carbon::now()->subDays(5),
            'jenis' => 'Pemasukan',
            'kategori' => 'Penjualan Tunai',
            'keterangan' => 'Penjualan harian kebab',
            'jumlah' => 2500000,
            'catatan' => 'Penjualan hari Sabtu, ramai pengunjung',
            'status' => 'Approved',
         ],
         [
            'cabang_kode' => 'KI-001',
            'karyawan_nik' => 'KI-202501-001',
            'tanggal' => Carbon::now()->subDays(4),
            'jenis' => 'Pengeluaran',
            'kategori' => 'Bahan Baku',
            'keterangan' => 'Pembelian daging sapi dan ayam',
            'jumlah' => 1500000,
            'catatan' => 'Stok mingguan',
            'status' => 'Approved',
         ],
         [
            'cabang_kode' => 'KI-001',
            'karyawan_nik' => 'KI-202501-002',
            'tanggal' => Carbon::now()->subDays(3),
            'jenis' => 'Pemasukan',
            'kategori' => 'Penjualan Non-Tunai',
            'keterangan' => 'Penjualan via GoFood & GrabFood',
            'jumlah' => 1800000,
            'catatan' => 'Pesanan online hari Minggu',
            'status' => 'Approved',
         ],
         [
            'cabang_kode' => 'KI-001',
            'karyawan_nik' => 'KI-202501-002',
            'tanggal' => Carbon::now()->subDays(2),
            'jenis' => 'Pengeluaran',
            'kategori' => 'Operasional',
            'keterangan' => 'Pembelian gas LPG dan peralatan',
            'jumlah' => 350000,
            'catatan' => '3 tabung gas + spatula baru',
            'status' => 'Pending',
         ],

         // Cabang Bandung (KI-002)
         [
            'cabang_kode' => 'KI-002',
            'karyawan_nik' => 'KI-202502-001',
            'tanggal' => Carbon::now()->subDays(5),
            'jenis' => 'Pemasukan',
            'kategori' => 'Penjualan Tunai',
            'keterangan' => 'Penjualan harian kebab',
            'jumlah' => 1800000,
            'catatan' => 'Weekend ramai',
            'status' => 'Approved',
         ],
         [
            'cabang_kode' => 'KI-002',
            'karyawan_nik' => 'KI-202502-001',
            'tanggal' => Carbon::now()->subDays(3),
            'jenis' => 'Pengeluaran',
            'kategori' => 'Listrik & Air',
            'keterangan' => 'Pembayaran listrik bulan November',
            'jumlah' => 450000,
            'catatan' => 'PLN Postpaid',
            'status' => 'Approved',
         ],
         [
            'cabang_kode' => 'KI-002',
            'karyawan_nik' => 'KI-202502-001',
            'tanggal' => Carbon::now()->subDays(1),
            'jenis' => 'Pemasukan',
            'kategori' => 'Pendapatan Lainnya',
            'keterangan' => 'Catering acara kantor',
            'jumlah' => 3000000,
            'catatan' => 'Pesanan 100 porsi untuk PT ABC',
            'status' => 'Pending',
         ],

         // Cabang Surabaya (KI-003)
         [
            'cabang_kode' => 'KI-003',
            'karyawan_nik' => 'KI-202503-001',
            'tanggal' => Carbon::now()->subDays(6),
            'jenis' => 'Pemasukan',
            'kategori' => 'Penjualan Tunai',
            'keterangan' => 'Penjualan harian kebab',
            'jumlah' => 2200000,
            'catatan' => 'Penjualan di CFD Tunjungan',
            'status' => 'Approved',
         ],
         [
            'cabang_kode' => 'KI-003',
            'karyawan_nik' => 'KI-202503-001',
            'tanggal' => Carbon::now()->subDays(4),
            'jenis' => 'Pengeluaran',
            'kategori' => 'Sewa Tempat',
            'keterangan' => 'Sewa booth bulan Desember',
            'jumlah' => 2000000,
            'catatan' => 'Pembayaran sewa bulanan',
            'status' => 'Approved',
         ],
         [
            'cabang_kode' => 'KI-003',
            'karyawan_nik' => 'KI-202503-001',
            'tanggal' => Carbon::now()->subDays(2),
            'jenis' => 'Pengeluaran',
            'kategori' => 'Marketing',
            'keterangan' => 'Cetak banner dan brosur promosi',
            'jumlah' => 500000,
            'catatan' => 'Promo akhir tahun',
            'status' => 'Draft',
         ],

         // Cabang Yogyakarta (KI-004)
         [
            'cabang_kode' => 'KI-004',
            'karyawan_nik' => 'KI-202504-001',
            'tanggal' => Carbon::now()->subDays(7),
            'jenis' => 'Pemasukan',
            'kategori' => 'Penjualan Tunai',
            'keterangan' => 'Penjualan harian kebab',
            'jumlah' => 1600000,
            'catatan' => 'Penjualan di area Malioboro',
            'status' => 'Approved',
         ],
         [
            'cabang_kode' => 'KI-004',
            'karyawan_nik' => 'KI-202504-001',
            'tanggal' => Carbon::now()->subDays(5),
            'jenis' => 'Pengeluaran',
            'kategori' => 'Bahan Baku',
            'keterangan' => 'Pembelian sayuran dan bumbu',
            'jumlah' => 400000,
            'catatan' => 'Stok harian',
            'status' => 'Approved',
         ],
         [
            'cabang_kode' => 'KI-004',
            'karyawan_nik' => 'KI-202504-001',
            'tanggal' => Carbon::now()->subDays(1),
            'jenis' => 'Pemasukan',
            'kategori' => 'Penjualan Non-Tunai',
            'keterangan' => 'Penjualan via ShopeeFood',
            'jumlah' => 950000,
            'catatan' => 'Promo gratis ongkir',
            'status' => 'Pending',
         ],

         // Cabang Semarang (KI-005) - tanpa karyawan terdaftar, pakai admin
         [
            'cabang_kode' => 'KI-005',
            'karyawan_nik' => 'KI-202501-001',
            'tanggal' => Carbon::now()->subDays(3),
            'jenis' => 'Pemasukan',
            'kategori' => 'Penjualan Tunai',
            'keterangan' => 'Grand opening cabang Semarang',
            'jumlah' => 5000000,
            'catatan' => 'Promo opening 50% off',
            'status' => 'Approved',
         ],
         [
            'cabang_kode' => 'KI-005',
            'karyawan_nik' => 'KI-202501-001',
            'tanggal' => Carbon::now()->subDays(2),
            'jenis' => 'Pengeluaran',
            'kategori' => 'Pemeliharaan',
            'keterangan' => 'Pembelian peralatan dapur baru',
            'jumlah' => 3500000,
            'catatan' => 'Setup awal cabang baru',
            'status' => 'Approved',
         ],
      ];

      foreach ($laporans as $laporan) {
         $cabang = Cabang::where('kode_cabang', $laporan['cabang_kode'])->first();
         $karyawan = Karyawan::where('nik', $laporan['karyawan_nik'])->first();

         if ($cabang && $karyawan) {
            $approvedBy = null;
            $approvedAt = null;

            if ($laporan['status'] === 'Approved' && $admin) {
               $approvedBy = $admin->id;
               $approvedAt = Carbon::now();
            }

            LaporanKeuangan::create([
               'cabang_id' => $cabang->id,
               'karyawan_id' => $karyawan->id,
               'created_by' => $karyawan->user_id,
               'tanggal' => $laporan['tanggal'],
               'jenis' => $laporan['jenis'],
               'kategori' => $laporan['kategori'],
               'keterangan' => $laporan['keterangan'],
               'jumlah' => $laporan['jumlah'],
               'catatan' => $laporan['catatan'],
               'status' => $laporan['status'],
               'approved_by' => $approvedBy,
               'approved_at' => $approvedAt,
            ]);
         }
      }
   }
}
