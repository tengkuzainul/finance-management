<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Gaji;
use App\Models\Karyawan;
use App\Models\LaporanKeuangan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard for admin.
     */
    public function index()
    {
        $user = Auth::user();

        // Jika karyawan, redirect ke dashboard karyawan
        if (!$user->is_admin) {
            return $this->karyawanDashboard();
        }

        // Data untuk admin - seluruh data
        $bulanIni = Carbon::now()->startOfMonth();
        $bulanLalu = Carbon::now()->subMonth()->startOfMonth();
        $akhirBulanLalu = Carbon::now()->subMonth()->endOfMonth();

        // Stats bulan ini
        $totalPemasukan = LaporanKeuangan::Pendapatan()->approved()
            ->where('tanggal', '>=', $bulanIni)
            ->sum('jumlah');

        $totalPengeluaran = LaporanKeuangan::pengeluaran()->approved()
            ->where('tanggal', '>=', $bulanIni)
            ->sum('jumlah');

        $totalGaji = Gaji::where('status', 'paid')
            ->where('tanggal', '>=', $bulanIni)
            ->sum('nominal_gaji');

        $totalTransaksi = LaporanKeuangan::approved()
            ->where('tanggal', '>=', $bulanIni)
            ->count();

        // Stats bulan lalu untuk perbandingan
        $pemasukanBulanLalu = LaporanKeuangan::Pendapatan()->approved()
            ->whereBetween('tanggal', [$bulanLalu, $akhirBulanLalu])
            ->sum('jumlah');

        $pengeluaranBulanLalu = LaporanKeuangan::pengeluaran()->approved()
            ->whereBetween('tanggal', [$bulanLalu, $akhirBulanLalu])
            ->sum('jumlah');

        // Hitung persentase perubahan
        $perubahanPemasukan = $pemasukanBulanLalu > 0
            ? round((($totalPemasukan - $pemasukanBulanLalu) / $pemasukanBulanLalu) * 100, 1)
            : 0;

        $perubahanPengeluaran = $pengeluaranBulanLalu > 0
            ? round((($totalPengeluaran - $pengeluaranBulanLalu) / $pengeluaranBulanLalu) * 100, 1)
            : 0;

        $profitBersih = $totalPemasukan - $totalPengeluaran - $totalGaji;
        $profitBulanLalu = $pemasukanBulanLalu - $pengeluaranBulanLalu;
        $perubahanProfit = $profitBulanLalu != 0
            ? round((($profitBersih - $profitBulanLalu) / abs($profitBulanLalu)) * 100, 1)
            : 0;

        $stats = [
            'total_pemasukan' => $totalPemasukan,
            'total_pengeluaran' => $totalPengeluaran,
            'total_gaji' => $totalGaji,
            'profit_bersih' => $profitBersih,
            'total_transaksi' => $totalTransaksi,
            'perubahan_pemasukan' => $perubahanPemasukan,
            'perubahan_pengeluaran' => $perubahanPengeluaran,
            'perubahan_profit' => $perubahanProfit,
            'pending_count' => LaporanKeuangan::pending()->count(),
            'total_cabang' => Cabang::active()->count(),
            'total_karyawan' => Karyawan::active()->count(),
        ];

        // Transaksi terbaru (10 terakhir)
        $recentTransactions = LaporanKeuangan::with(['cabang', 'karyawan'])
            ->approved()
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($laporan) {
                return [
                    'title' => $laporan->keterangan,
                    'kategori' => $laporan->kategori,
                    'cabang' => $laporan->cabang?->nama_cabang ?? '-',
                    'date' => $laporan->tanggal->diffForHumans(),
                    'tanggal' => $laporan->tanggal->format('d M Y'),
                    'amount' => $laporan->jumlah,
                    'type' => strtolower($laporan->jenis),
                ];
            });

        // Data chart - 7 hari terakhir
        $chartData = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $Pendapatan = LaporanKeuangan::Pendapatan()->approved()
                ->whereDate('tanggal', $date)
                ->sum('jumlah');
            $pengeluaran = LaporanKeuangan::pengeluaran()->approved()
                ->whereDate('tanggal', $date)
                ->sum('jumlah');

            $chartData->push([
                'date' => $date->format('d M'),
                'day' => $date->translatedFormat('D'),
                'Pendapatan' => $Pendapatan,
                'pengeluaran' => $pengeluaran,
            ]);
        }

        // Stats per cabang
        $cabangStats = Cabang::active()
            ->withCount(['laporanKeuangans as transaksi_count' => function ($query) use ($bulanIni) {
                $query->where('status', 'Approved')->where('tanggal', '>=', $bulanIni);
            }])
            ->withSum(['laporanKeuangans as Pendapatan' => function ($query) use ($bulanIni) {
                $query->where('status', 'Approved')
                    ->where('jenis', 'Pendapatan')
                    ->where('tanggal', '>=', $bulanIni);
            }], 'jumlah')
            ->withSum(['laporanKeuangans as pengeluaran' => function ($query) use ($bulanIni) {
                $query->where('status', 'Approved')
                    ->where('jenis', 'Pengeluaran')
                    ->where('tanggal', '>=', $bulanIni);
            }], 'jumlah')
            ->orderByDesc('Pendapatan')
            ->get();

        return view('dashboard', compact('stats', 'recentTransactions', 'chartData', 'cabangStats'));
    }

    /**
     * Display the dashboard for karyawan.
     */
    protected function karyawanDashboard()
    {
        $user = Auth::user();
        $karyawan = $user->karyawan;

        if (!$karyawan) {
            return view('dashboard-karyawan-no-data');
        }

        $bulanIni = Carbon::now()->startOfMonth();
        $hariIni = Carbon::today();

        // Stats karyawan - hanya data miliknya
        $totalPemasukan = LaporanKeuangan::where('karyawan_id', $karyawan->id)
            ->Pendapatan()
            ->approved()
            ->where('tanggal', '>=', $bulanIni)
            ->sum('jumlah');

        $totalPengeluaran = LaporanKeuangan::where('karyawan_id', $karyawan->id)
            ->pengeluaran()
            ->approved()
            ->where('tanggal', '>=', $bulanIni)
            ->sum('jumlah');

        $totalTransaksi = LaporanKeuangan::where('karyawan_id', $karyawan->id)
            ->where('tanggal', '>=', $bulanIni)
            ->count();

        $pendingCount = LaporanKeuangan::where('karyawan_id', $karyawan->id)
            ->pending()
            ->count();

        $draftCount = LaporanKeuangan::where('karyawan_id', $karyawan->id)
            ->where('status', 'Draft')
            ->count();

        // Gaji karyawan bulan ini
        $gajiDibayar = Gaji::where('karyawan_id', $karyawan->id)
            ->where('status', 'paid')
            ->where('tanggal', '>=', $bulanIni)
            ->sum('nominal_gaji');

        $gajiPending = Gaji::where('karyawan_id', $karyawan->id)
            ->where('status', 'pending')
            ->where('tanggal', '>=', $bulanIni)
            ->sum('nominal_gaji');

        // Pendapatan hari ini (untuk perhitungan estimasi gaji)
        $pemasukanHariIni = LaporanKeuangan::where('karyawan_id', $karyawan->id)
            ->Pendapatan()
            ->approved()
            ->whereDate('tanggal', $hariIni)
            ->sum('jumlah');

        $stats = [
            'total_pemasukan' => $totalPemasukan,
            'total_pengeluaran' => $totalPengeluaran,
            'total_transaksi' => $totalTransaksi,
            'pending_count' => $pendingCount,
            'draft_count' => $draftCount,
            'gaji_dibayar' => $gajiDibayar,
            'gaji_pending' => $gajiPending,
            'pemasukan_hari_ini' => $pemasukanHariIni,
        ];

        // Transaksi terbaru karyawan (10 terakhir)
        $recentTransactions = LaporanKeuangan::where('karyawan_id', $karyawan->id)
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($laporan) {
                return [
                    'title' => $laporan->keterangan,
                    'kategori' => $laporan->kategori,
                    'date' => $laporan->tanggal->diffForHumans(),
                    'tanggal' => $laporan->tanggal->format('d M Y'),
                    'amount' => $laporan->jumlah,
                    'type' => $laporan->jenis,
                    'status' => $laporan->status,
                ];
            });

        // Data chart - 7 hari terakhir
        $chartData = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $Pendapatan = LaporanKeuangan::where('karyawan_id', $karyawan->id)
                ->Pendapatan()
                ->approved()
                ->whereDate('tanggal', $date)
                ->sum('jumlah');

            $chartData->push([
                'date' => $date->format('d M'),
                'day' => $date->translatedFormat('D'),
                'Pendapatan' => $Pendapatan,
            ]);
        }

        // Riwayat gaji
        $gajiHistory = Gaji::where('karyawan_id', $karyawan->id)
            ->orderBy('tanggal', 'desc')
            ->limit(5)
            ->get();

        // Informasi manajemen terbaru
        $informasiList = \App\Models\Informasi::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard-karyawan', compact('stats', 'recentTransactions', 'chartData', 'gajiHistory', 'karyawan', 'informasiList'));
    }
}

