<?php

namespace Database\Seeders;

use App\Models\Cabang;
use Illuminate\Database\Seeder;

class CabangSeeder extends Seeder
{
   /**
    * Run the database seeds.
    */
   public function run(): void
   {
      $cabangs = [
         [
            'nama_cabang' => 'Kebab Ikhwan Pusat',
            'kode_cabang' => 'KI-001',
            'alamat_lengkap' => 'Jl. Raya Utama No. 100, Jakarta Pusat',
            'no_telepon' => '021-12345678',
            'email' => 'pusat@kebabikhwan.com',
            'jumlah_karyawan' => 0,
            'is_active' => true,
         ],
         [
            'nama_cabang' => 'Kebab Ikhwan Bandung',
            'kode_cabang' => 'KI-002',
            'alamat_lengkap' => 'Jl. Braga No. 45, Bandung',
            'no_telepon' => '022-87654321',
            'email' => 'bandung@kebabikhwan.com',
            'jumlah_karyawan' => 0,
            'is_active' => true,
         ],
         [
            'nama_cabang' => 'Kebab Ikhwan Surabaya',
            'kode_cabang' => 'KI-003',
            'alamat_lengkap' => 'Jl. Tunjungan No. 88, Surabaya',
            'no_telepon' => '031-11223344',
            'email' => 'surabaya@kebabikhwan.com',
            'jumlah_karyawan' => 0,
            'is_active' => true,
         ],
         [
            'nama_cabang' => 'Kebab Ikhwan Yogyakarta',
            'kode_cabang' => 'KI-004',
            'alamat_lengkap' => 'Jl. Malioboro No. 55, Yogyakarta',
            'no_telepon' => '0274-556677',
            'email' => 'yogyakarta@kebabikhwan.com',
            'jumlah_karyawan' => 0,
            'is_active' => true,
         ],
         [
            'nama_cabang' => 'Kebab Ikhwan Semarang',
            'kode_cabang' => 'KI-005',
            'alamat_lengkap' => 'Jl. Pandanaran No. 33, Semarang',
            'no_telepon' => '024-99887766',
            'email' => 'semarang@kebabikhwan.com',
            'jumlah_karyawan' => 0,
            'is_active' => true,
         ],
      ];

      foreach ($cabangs as $cabang) {
         Cabang::updateOrCreate(
            ['kode_cabang' => $cabang['kode_cabang']],
            $cabang
         );
      }
   }
}
