<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
   /**
    * Fonnte API Token
    * Dapatkan token di https://fonnte.com
    */
   protected $apiToken;

   /**
    * Fonnte API URL
    */
   protected $apiUrl = 'https://api.fonnte.com/send';

   public function __construct()
   {
      $this->apiToken = config('services.fonnte.token');
   }

   /**
    * Send WhatsApp message
    *
    * @param string $phoneNumber
    * @param string $message
    * @return array
    */
   public function sendMessage(string $phoneNumber, string $message): array
   {
      // Format phone number (remove leading 0, add 62)
      $phoneNumber = $this->formatPhoneNumber($phoneNumber);

      // Check if token is configured
      if (empty($this->apiToken)) {
         Log::warning('WhatsApp API token not configured');
         return [
            'success' => false,
            'message' => 'WhatsApp API token not configured'
         ];
      }

      try {
         $response = Http::withHeaders([
            'Authorization' => $this->apiToken,
         ])->post($this->apiUrl, [
            'target' => $phoneNumber,
            'message' => $message,
            'countryCode' => '62', // Indonesia
         ]);

         $result = $response->json();

         if ($response->successful() && isset($result['status']) && $result['status'] === true) {
            Log::info('WhatsApp message sent successfully', [
               'phone' => $phoneNumber,
               'response' => $result
            ]);

            return [
               'success' => true,
               'message' => 'Pesan WhatsApp berhasil dikirim',
               'data' => $result
            ];
         }

         Log::warning('WhatsApp message failed', [
            'phone' => $phoneNumber,
            'response' => $result
         ]);

         return [
            'success' => false,
            'message' => $result['reason'] ?? 'Gagal mengirim pesan WhatsApp',
            'data' => $result
         ];
      } catch (\Exception $e) {
         Log::error('WhatsApp API error', [
            'phone' => $phoneNumber,
            'error' => $e->getMessage()
         ]);

         return [
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
         ];
      }
   }

   /**
    * Format phone number to international format
    *
    * @param string $phoneNumber
    * @return string
    */
   protected function formatPhoneNumber(string $phoneNumber): string
   {
      // Remove all non-numeric characters
      $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

      // Remove leading 0 and add 62
      if (str_starts_with($phoneNumber, '0')) {
         $phoneNumber = '62' . substr($phoneNumber, 1);
      }

      // If already starts with 62, keep as is
      if (!str_starts_with($phoneNumber, '62')) {
         $phoneNumber = '62' . $phoneNumber;
      }

      return $phoneNumber;
   }

   /**
    * Send salary payment notification
    *
    * @param \App\Models\Gaji $gaji
    * @return array
    */
   public function sendSalaryNotification($gaji): array
   {
      $karyawan = $gaji->karyawan;

      if (!$karyawan || empty($karyawan->no_telepon)) {
         return [
            'success' => false,
            'message' => 'Nomor telepon karyawan tidak tersedia'
         ];
      }

      $message = $this->buildSalaryMessage($gaji);

      return $this->sendMessage($karyawan->no_telepon, $message);
   }

   /**
    * Build salary notification message
    *
    * @param \App\Models\Gaji $gaji
    * @return string
    */
   protected function buildSalaryMessage($gaji): string
   {
      $karyawan = $gaji->karyawan;
      $cabang = $gaji->cabang;

      $tanggal = \Carbon\Carbon::parse($gaji->tanggal)->locale('id')->translatedFormat('d F Y');
      $paidAt = \Carbon\Carbon::parse($gaji->paid_at)->locale('id')->translatedFormat('d F Y, H:i');

      $nominalGaji = number_format($gaji->nominal_gaji, 0, ',', '.');
      $totalPemasukan = number_format($gaji->total_pemasukan, 0, ',', '.');
      $persenGaji = $gaji->persen_gaji ?? 13;

      $message = "🎉 *NOTIFIKASI PEMBAYARAN GAJI*\n\n";
      $message .= "Halo *{$karyawan->nama_lengkap}*,\n\n";
      $message .= "Kami informasikan bahwa gaji Anda telah dibayarkan dengan rincian sebagai berikut:\n\n";
      $message .= "━━━━━━━━━━━━━━━━━━━━\n";
      $message .= "📅 *Periode:* {$tanggal}\n";
      $message .= "🏪 *Cabang:* {$cabang->nama_cabang}\n";
      $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
      $message .= "💰 *RINCIAN GAJI*\n";
      $message .= "• Total Pendapatan: Rp {$totalPemasukan}\n";
      $message .= "• Persentase Gaji: {$persenGaji}%\n";
      $message .= "• *Nominal Gaji: Rp {$nominalGaji}*\n\n";
      $message .= "━━━━━━━━━━━━━━━━━━━━\n";
      $message .= "✅ *Status:* SUDAH DIBAYAR\n";
      $message .= "📆 *Tanggal Bayar:* {$paidAt} WIB\n";
      $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
      $message .= "Terima kasih atas kerja keras Anda! 🙏\n\n";
      $message .= "_Pesan ini dikirim otomatis dari Sistem Keuangan Kebab Ikhwan_";

      return $message;
   }

   /**
    * Send bulk salary notifications
    *
    * @param array $gajiIds - Array of Gaji models
    * @return array
    */
   public function sendBulkSalaryNotifications(array $gajis): array
   {
      $results = [
         'success' => 0,
         'failed' => 0,
         'details' => []
      ];

      foreach ($gajis as $gaji) {
         $result = $this->sendSalaryNotification($gaji);

         if ($result['success']) {
            $results['success']++;
         } else {
            $results['failed']++;
         }

         $results['details'][] = [
            'karyawan' => $gaji->karyawan->nama_lengkap ?? 'Unknown',
            'phone' => $gaji->karyawan->no_telepon ?? 'N/A',
            'result' => $result
         ];

         // Small delay to avoid rate limiting
         usleep(500000); // 0.5 second delay
      }

      return $results;
   }
}

