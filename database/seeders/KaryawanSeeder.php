<?php

namespace Database\Seeders;

use App\Models\Cabang;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class KaryawanSeeder extends Seeder
{
   /**
    * Run the database seeds.
    */
   public function run(): void
   {
      // Get cabang pertama (Pusat) untuk admin
      $cabangPusat = Cabang::where('kode_cabang', 'KI-001')->first();

      // Get admin user
      $adminUser = User::where('username', 'admin')->first();

      // Create Administrator karyawan
      if ($cabangPusat && $adminUser) {
         $adminKaryawan = Karyawan::updateOrCreate(
            ['nik' => 'KI-202501-001'],
            [
               'cabang_id' => $cabangPusat->id,
               'user_id' => $adminUser->id,
               'nik' => 'KI-202501-001',
               'nama_lengkap' => 'Administrator',
               'tempat_lahir' => 'Jakarta',
               'tanggal_lahir' => '1990-01-15',
               'jenis_kelamin' => 'Laki-laki',
               'alamat' => 'Jl. Admin No. 1, Jakarta Pusat',
               'no_telepon' => '081234567890',
               'email' => 'admin@kebabikhwan.com',
               'agama' => 'Islam',
               'status_pernikahan' => 'Menikah',
               'tanggal_masuk' => '2020-01-01',
               'is_active' => true,
            ]
         );
      }

      // Data karyawan lainnya
      $karyawans = [
         [
            'cabang_kode' => 'KI-001',
            'user_data' => [
               'name' => 'Budi Santoso',
               'username' => 'budi.santoso',
               'email' => 'budi@kebabikhwan.com',
               'password' => Hash::make('password123'),
               'is_active' => true,
               'is_admin' => false,
            ],
            'karyawan_data' => [
               'nik' => 'KI-202501-002',
               'nama_lengkap' => 'Budi Santoso',
               'tempat_lahir' => 'Jakarta',
               'tanggal_lahir' => '1995-05-20',
               'jenis_kelamin' => 'Laki-laki',
               'alamat' => 'Jl. Kebon Jeruk No. 10, Jakarta Barat',
               'no_telepon' => '081234567891',
               'email' => 'budi@kebabikhwan.com',
               'agama' => 'Islam',
               'status_pernikahan' => 'Belum Menikah',
               'tanggal_masuk' => '2023-03-15',
               'is_active' => true,
            ],
         ],
         [
            'cabang_kode' => 'KI-002',
            'user_data' => [
               'name' => 'Siti Rahayu',
               'username' => 'siti.rahayu',
               'email' => 'siti@kebabikhwan.com',
               'password' => Hash::make('password123'),
               'is_active' => true,
               'is_admin' => false,
            ],
            'karyawan_data' => [
               'nik' => 'KI-202502-001',
               'nama_lengkap' => 'Siti Rahayu',
               'tempat_lahir' => 'Bandung',
               'tanggal_lahir' => '1998-08-12',
               'jenis_kelamin' => 'Perempuan',
               'alamat' => 'Jl. Dago No. 25, Bandung',
               'no_telepon' => '081234567892',
               'email' => 'siti@kebabikhwan.com',
               'agama' => 'Islam',
               'status_pernikahan' => 'Belum Menikah',
               'tanggal_masuk' => '2023-06-01',
               'is_active' => true,
            ],
         ],
         [
            'cabang_kode' => 'KI-003',
            'user_data' => [
               'name' => 'Ahmad Hidayat',
               'username' => 'ahmad.hidayat',
               'email' => 'ahmad@kebabikhwan.com',
               'password' => Hash::make('password123'),
               'is_active' => true,
               'is_admin' => false,
            ],
            'karyawan_data' => [
               'nik' => 'KI-202503-001',
               'nama_lengkap' => 'Ahmad Hidayat',
               'tempat_lahir' => 'Surabaya',
               'tanggal_lahir' => '1992-11-30',
               'jenis_kelamin' => 'Laki-laki',
               'alamat' => 'Jl. Gubeng No. 77, Surabaya',
               'no_telepon' => '081234567893',
               'email' => 'ahmad@kebabikhwan.com',
               'agama' => 'Islam',
               'status_pernikahan' => 'Menikah',
               'tanggal_masuk' => '2022-09-10',
               'is_active' => true,
            ],
         ],
         [
            'cabang_kode' => 'KI-004',
            'user_data' => [
               'name' => 'Dewi Lestari',
               'username' => 'dewi.lestari',
               'email' => 'dewi@kebabikhwan.com',
               'password' => Hash::make('password123'),
               'is_active' => true,
               'is_admin' => false,
            ],
            'karyawan_data' => [
               'nik' => 'KI-202504-001',
               'nama_lengkap' => 'Dewi Lestari',
               'tempat_lahir' => 'Yogyakarta',
               'tanggal_lahir' => '1996-02-14',
               'jenis_kelamin' => 'Perempuan',
               'alamat' => 'Jl. Prawirotaman No. 18, Yogyakarta',
               'no_telepon' => '081234567894',
               'email' => 'dewi@kebabikhwan.com',
               'agama' => 'Islam',
               'status_pernikahan' => 'Belum Menikah',
               'tanggal_masuk' => '2024-01-15',
               'is_active' => true,
            ],
         ],
      ];

      foreach ($karyawans as $data) {
         $cabang = Cabang::where('kode_cabang', $data['cabang_kode'])->first();

         if ($cabang) {
            // Create user
            $user = User::updateOrCreate(
               ['email' => $data['user_data']['email']],
               $data['user_data']
            );

            // Create karyawan
            $karyawanData = array_merge($data['karyawan_data'], [
               'cabang_id' => $cabang->id,
               'user_id' => $user->id,
            ]);

            Karyawan::updateOrCreate(
               ['nik' => $data['karyawan_data']['nik']],
               $karyawanData
            );
         }
      }

      // Update jumlah karyawan di setiap cabang
      $cabangs = Cabang::all();
      foreach ($cabangs as $cabang) {
         $cabang->updateJumlahKaryawan();
      }
   }
}
