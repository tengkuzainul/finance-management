<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        // Data dummy untuk dashboard
        // Nanti bisa diganti dengan data dari database
        $stats = [
            'total_pemasukan' => 24500000,
            'total_pengeluaran' => 8750000,
            'profit_bersih' => 15750000,
            'total_transaksi' => 1254,
        ];

        $recentTransactions = [
            [
                'title' => 'Penjualan Kebab Original',
                'date' => 'Hari ini, 14:30',
                'amount' => 150000,
                'type' => 'pemasukan',
            ],
            [
                'title' => 'Pembelian Daging Sapi',
                'date' => 'Hari ini, 10:15',
                'amount' => 500000,
                'type' => 'pengeluaran',
            ],
            [
                'title' => 'Penjualan Kebab Jumbo',
                'date' => 'Kemarin, 19:45',
                'amount' => 275000,
                'type' => 'pemasukan',
            ],
        ];

        return view('dashboard', compact('stats', 'recentTransactions'));
    }
}
