<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateSiswaToSlims extends Command
{
    protected $signature = 'migrate:siswa-to-slims';
    protected $description = 'Migrasikan data siswa ke database SLiMS';

    public function handle()
    {
        // Ambil data siswa dari database utama
        $siswaData = DB::table('siswa')->get();

        if ($siswaData->isEmpty()) {
            $this->error('Tidak ada data siswa untuk dimigrasikan.');
            return;
        }

        // Loop melalui setiap data siswa
        foreach ($siswaData as $siswa) {
            // Transformasi gender
            $gender = $siswa->gender === 'Laki-laki' ? 1 : 0;
        
            // Ekstraksi tanggal lahir dari kolom TTL
            // Contoh format TTL: "Pamekasan, 12 February 2009"
            $ttlParts = explode(',', $siswa->ttl);
            $birthDate = isset($ttlParts[1]) ? date('Y-m-d', strtotime(trim($ttlParts[1]))) : null;
        
            // Tambahkan prefix "member_" pada nama file foto
            $memberImage = 'member_' . $siswa->foto;

            $expireDate = '2027-08-03';
        
            // Masukkan data ke tabel SLiMS
            DB::connection('slims')->table('member')->insert([
                'member_id' => $siswa->nis,
                'member_name' => $siswa->nama,
                'gender' => $gender,
                'birth_date' => $birthDate,
                'member_address' => $siswa->alamat,
                'member_image' => $memberImage,
                'pin' => $siswa->id_card,
                'expire_date' => $expireDate,
            ]);
        }           

        $this->info('Data siswa berhasil dimigrasikan ke SLiMS!');
    }
}
