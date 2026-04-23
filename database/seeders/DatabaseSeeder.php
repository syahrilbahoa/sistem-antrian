<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Loket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Faisal Djukiro',
            'email' => 'faisaldjukiro98@gmail.com',
        ]);

        // Seed data loket
        $lokets = [
            ['nama_loket' => 'Pendaftaran 1'],
            ['nama_loket' => 'Pendaftaran 2'],
            ['nama_loket' => 'Pemeriksaan 1'],
            ['nama_loket' => 'Pemeriksaan 2'],
            ['nama_loket' => 'Kasir'],
            ['nama_loket' => 'Farmasi'],
        ];

        foreach ($lokets as $loket) {
            Loket::create($loket);
        }
    }
}
