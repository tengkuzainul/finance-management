<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::updateOrCreate(
            ['email' => 'admin@kebabikhwan.com'],
            [
                'name' => 'Administrator',
                'username' => 'admin',
                'email' => 'admin@kebabikhwan.com',
                'password' => Hash::make('password123'),
                'is_active' => true,
                'is_admin' => true,
            ]
        );

        // Call other seeders
        // $this->call([
        //     CabangSeeder::class,
        //     KaryawanSeeder::class,
        //     LaporanKeuanganSeeder::class,
        // ]);
    }
}
