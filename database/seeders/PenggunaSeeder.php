<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;

class PenggunaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Pengguna::create([
            'username' => 'admin',
            'email' => 'pmkldn@gmail.com',
            'password' => Hash::make('pmkldn'),
        ]);
    }
}
