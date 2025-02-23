<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class MigrateController extends Controller
{
    public function index()
    {
        $dataSiswa = Siswa::all()->map(function ($siswa) {
            return [
                'nama' => $siswa->nama,
                'nis' => $siswa->nis,
                'ttl' => $siswa->ttl,
                'alamat' => $siswa->alamat,
                'gender' => $siswa->gender,
                'id_card' => $siswa->id_card,
                'foto' => asset("storage/app/public/images/siswa/{$siswa->foto}")
            ];
        });
    
        return response()->json($dataSiswa);
    }

    public function migrateSiswaToSlims()
    {
        try {
            // **Ganti dengan URL API Laravel yang online**
            $apiUrl = "https://kartu-pelajar.gunawans.web.id/api/siswa"; 
            
            // Ambil data dari Laravel online
            $response = Http::get($apiUrl);
            if ($response->failed()) {
                return response()->json(['message' => 'Gagal mengambil data dari server online'], 500);
            }

            $siswaData = $response->json(); // Konversi ke array

            foreach ($siswaData as $siswa) {
                // Transformasi gender
                $gender = $siswa['gender'] === 'Laki-laki' ? 1 : 0;

                // Ekstraksi tanggal lahir dari TTL
                $ttlParts = explode(',', $siswa['ttl']);
                $birthDate = isset($ttlParts[1]) ? date('Y-m-d', strtotime(trim($ttlParts[1]))) : null;

                // Nama foto dengan prefix "member_"
                $memberImage = 'member_' . $siswa['foto'];

                // Tentukan tanggal kedaluwarsa (1 tahun dari sekarang)
                $expireDate = now()->addYear()->format('Y-m-d');

                // Masukkan ke database SLiMS
                DB::connection('slims')->table('member')->updateOrInsert(
                    ['member_id' => $siswa['nis']], // Primary Key
                    [
                        'member_name' => $siswa['nama'],
                        'gender' => $gender,
                        'birth_date' => $birthDate,
                        'member_address' => $siswa['alamat'],
                        'member_image' => $memberImage,
                        'pin' => $siswa['id_card'],
                        'expire_date' => $expireDate,
                    ]
                );
            }

            return response()->json(['message' => 'Migrasi data berhasil dari online ke SLiMS lokal!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function migratePhotos()
    {
        try {
            // **Ganti dengan URL API Laravel yang online**
            $apiUrl = "https://kartu-pelajar.gunawans.web.id/api/siswa"; 

            // Ambil data dari Laravel online
            $response = Http::get($apiUrl);
            if ($response->failed()) {
                return response()->json(['message' => 'Gagal mengambil data dari server online'], 500);
            }

            $siswaData = $response->json(); // Konversi ke array

            foreach ($siswaData as $siswa) {
                $fotoUrl = $siswa['foto']; // Ambil URL gambar dari API

                // Lokasi penyimpanan di SLiMS lokal
                $destinationPath = 'D:\laragon\www\slims\images\siswa\member_' . basename($fotoUrl);

                // **Download dan simpan foto**
                $fileContents = file_get_contents($fotoUrl);
                if ($fileContents !== false) {
                    File::put($destinationPath, $fileContents);
                } else {
                    Log::warning("Gagal mengunduh gambar: $fotoUrl");
                }
            }

            return response()->json(['message' => 'Migrasi foto dari online ke lokal selesai.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}
