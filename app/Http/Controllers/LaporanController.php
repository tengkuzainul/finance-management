<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Gaji;
use App\Models\LaporanKeuangan;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Vinkla\Hashids\Facades\Hashids;
use Carbon\Carbon;

class LaporanController extends Controller
{
   /**
    * Laporan Harian untuk Admin
    */
   public function harian(Request $request): View
   {
      $tanggal = $request->get('tanggal', Carbon::today()->format('Y-m-d'));
      $cabangId = $request->get('cabang_id');

      // Decode hash ID for cabang
      if ($cabangId) {
         $decoded = Hashids::decode($cabangId);
         $cabangId = !empty($decoded) ? $decoded[0] : null;
      }

      $query = LaporanKeuangan::with(['cabang', 'karyawan', 'creator'])
         ->whereDate('tanggal', $tanggal)
         ->where('status', 'Approved');

      if ($cabangId) {
         $query->where('cabang_id', $cabangId);
      }

      $laporans = $query->orderBy('created_at', 'desc')->get();

      // Group by cabang
      $groupedByCabang = $laporans->groupBy('cabang_id');

      // Calculate total gaji paid for this date
      $gajiQuery = Gaji::where('status', 'paid')
         ->whereDate('tanggal', $tanggal);
      if ($cabangId) {
         $gajiQuery->where('cabang_id', $cabangId);
      }
      $totalGaji = $gajiQuery->sum('nominal_gaji');

      $summary = [
         'total_pemasukan' => $laporans->where('jenis', 'Pendapatan')->sum('jumlah'),
         'total_pengeluaran' => $laporans->where('jenis', 'Pengeluaran')->sum('jumlah'),
         'total_gaji' => $totalGaji,
         'jumlah_transaksi' => $laporans->count(),
      ];
      $summary['saldo'] = $summary['total_pemasukan'] - $summary['total_pengeluaran'] - $summary['total_gaji'];

      $cabangs = Cabang::active()->orderBy('nama_cabang')->get();

      return view('pages.laporan.harian', compact(
         'laporans',
         'groupedByCabang',
         'summary',
         'tanggal',
         'cabangId',
         'cabangs'
      ));
   }

   /**
    * Laporan Mingguan untuk Admin
    */
   public function mingguan(Request $request): View
   {
      $mingguKe = $request->get('minggu', Carbon::now()->weekOfMonth);
      $bulan = $request->get('bulan', Carbon::now()->month);
      $tahun = $request->get('tahun', Carbon::now()->year);
      $cabangId = $request->get('cabang_id');

      // Decode hash ID for cabang
      if ($cabangId) {
         $decoded = Hashids::decode($cabangId);
         $cabangId = !empty($decoded) ? $decoded[0] : null;
      }

      // Calculate week start and end
      $startOfMonth = Carbon::create($tahun, $bulan, 1)->startOfMonth();
      $startOfWeek = $startOfMonth->copy()->addWeeks($mingguKe - 1)->startOfWeek();
      $endOfWeek = $startOfWeek->copy()->endOfWeek();

      $query = LaporanKeuangan::with(['cabang', 'karyawan', 'creator'])
         ->whereBetween('tanggal', [$startOfWeek, $endOfWeek])
         ->where('status', 'Approved');

      if ($cabangId) {
         $query->where('cabang_id', $cabangId);
      }

      $laporans = $query->orderBy('tanggal', 'desc')->get();

      // Group by tanggal
      $groupedByTanggal = $laporans->groupBy(function ($item) {
         return $item->tanggal->format('Y-m-d');
      });

      // Group by cabang
      $groupedByCabang = $laporans->groupBy('cabang_id');

      // Calculate total gaji paid for this week
      $gajiQuery = Gaji::where('status', 'paid')
         ->whereBetween('tanggal', [$startOfWeek, $endOfWeek]);
      if ($cabangId) {
         $gajiQuery->where('cabang_id', $cabangId);
      }
      $totalGaji = $gajiQuery->sum('nominal_gaji');

      $summary = [
         'total_pemasukan' => $laporans->where('jenis', 'Pendapatan')->sum('jumlah'),
         'total_pengeluaran' => $laporans->where('jenis', 'Pengeluaran')->sum('jumlah'),
         'total_gaji' => $totalGaji,
         'jumlah_transaksi' => $laporans->count(),
      ];
      $summary['saldo'] = $summary['total_pemasukan'] - $summary['total_pengeluaran'] - $summary['total_gaji'];

      $cabangs = Cabang::active()->orderBy('nama_cabang')->get();

      return view('pages.laporan.mingguan', compact(
         'laporans',
         'groupedByTanggal',
         'groupedByCabang',
         'summary',
         'mingguKe',
         'bulan',
         'tahun',
         'startOfWeek',
         'endOfWeek',
         'cabangId',
         'cabangs'
      ));
   }

   /**
    * Laporan Bulanan untuk Admin
    */
   public function bulanan(Request $request): View
   {
      $bulan = $request->get('bulan', Carbon::now()->month);
      $tahun = $request->get('tahun', Carbon::now()->year);
      $cabangId = $request->get('cabang_id');

      // Decode hash ID for cabang
      if ($cabangId) {
         $decoded = Hashids::decode($cabangId);
         $cabangId = !empty($decoded) ? $decoded[0] : null;
      }

      $startOfMonth = Carbon::create($tahun, $bulan, 1)->startOfMonth();
      $endOfMonth = $startOfMonth->copy()->endOfMonth();

      $query = LaporanKeuangan::with(['cabang', 'karyawan', 'creator'])
         ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
         ->where('status', 'Approved');

      if ($cabangId) {
         $query->where('cabang_id', $cabangId);
      }

      $laporans = $query->orderBy('tanggal', 'desc')->get();

      // Weekly stats
      $weeklyStats = collect();
      for ($week = 1; $week <= 5; $week++) {
         $weekStart = Carbon::create($tahun, $bulan, 1)->startOfMonth()->addWeeks($week - 1)->startOfWeek();
         $weekEnd = $weekStart->copy()->endOfWeek();

         if ($weekStart->month != $bulan && $weekEnd->month != $bulan) continue;

         $weekLaporans = $laporans->filter(function ($item) use ($weekStart, $weekEnd) {
            return $item->tanggal->between($weekStart, $weekEnd);
         });

         if ($weekLaporans->isNotEmpty()) {
            $weeklyStats->push([
               'minggu' => $week,
               'Pendapatan' => $weekLaporans->where('jenis', 'Pendapatan')->sum('jumlah'),
               'pengeluaran' => $weekLaporans->where('jenis', 'Pengeluaran')->sum('jumlah'),
               'saldo' => $weekLaporans->where('jenis', 'Pendapatan')->sum('jumlah') - $weekLaporans->where('jenis', 'Pengeluaran')->sum('jumlah'),
               'transaksi' => $weekLaporans->count(),
            ]);
         }
      }

      // Stats per cabang
      $cabangStats = $laporans->groupBy('cabang_id')->map(function ($items, $cabangId) {
         $cabang = $items->first()->cabang;
         return [
            'cabang' => $cabang,
            'Pendapatan' => $items->where('jenis', 'Pendapatan')->sum('jumlah'),
            'pengeluaran' => $items->where('jenis', 'Pengeluaran')->sum('jumlah'),
            'saldo' => $items->where('jenis', 'Pendapatan')->sum('jumlah') - $items->where('jenis', 'Pengeluaran')->sum('jumlah'),
            'transaksi' => $items->count(),
         ];
      });

      $summary = [
         'total_pemasukan' => $laporans->where('jenis', 'Pendapatan')->sum('jumlah'),
         'total_pengeluaran' => $laporans->where('jenis', 'Pengeluaran')->sum('jumlah'),
         'jumlah_transaksi' => $laporans->count(),
      ];

      // Calculate total gaji paid for this month
      $gajiQuery = Gaji::where('status', 'paid')
         ->whereBetween('tanggal', [$startOfMonth, $endOfMonth]);
      if ($cabangId) {
         $gajiQuery->where('cabang_id', $cabangId);
      }
      $summary['total_gaji'] = $gajiQuery->sum('nominal_gaji');
      $summary['saldo'] = $summary['total_pemasukan'] - $summary['total_pengeluaran'] - $summary['total_gaji'];

      $cabangs = Cabang::active()->orderBy('nama_cabang')->get();

      return view('pages.laporan.bulanan', compact(
         'laporans',
         'weeklyStats',
         'cabangStats',
         'summary',
         'bulan',
         'tahun',
         'cabangId',
         'cabangs'
      ));
   }
}

