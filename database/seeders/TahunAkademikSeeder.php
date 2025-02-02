<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TahunAkademikSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tahun_akademik')->insert([
            ['tahun' => '2023/2024', 'status' => 'nonaktif'],
            ['tahun' => '2024/2025', 'status' => 'aktif'], // Tahun aktif
        ]);
    }
}

