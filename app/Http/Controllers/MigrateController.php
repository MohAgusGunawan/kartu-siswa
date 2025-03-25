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
        try {
            //code...
            $dataSiswa = Siswa::all()->map(function ($siswa) {
                return [
                    'nama' => $siswa->nama,
                    'nis' => $siswa->nis,
                    'ttl' => $siswa->ttl,
                    'alamat' => $siswa->alamat,
                    'gender' => $siswa->gender,
                    'id_card' => $siswa->id_card,
                    'foto' => asset("storage/images/siswa/{$siswa->foto}")
                ];
            });
        
            return response()->json($dataSiswa);
        } catch (\Throwable $th) {
            throw $th;
        }
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
                $namaFile = basename($siswa['foto']); // Ambil hanya nama file dari URL
                $memberImage = 'member_' . $namaFile;

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
                        'register_date' => now()->format('Y-m-d'),
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
        // URL API Laravel yang online
        $apiUrl = "https://kartu-pelajar.gunawans.web.id/api/siswa"; 

        // Ambil data siswa dari API
        $response = Http::get($apiUrl);
        if ($response->failed()) {
            return response()->json(['message' => 'Gagal mengambil data dari server online'], 500);
        }

        $siswaData = $response->json(); // Konversi ke array

        foreach ($siswaData as $siswa) {
            // Pastikan ada foto
            if (!isset($siswa['foto']) || empty($siswa['foto'])) {
                Log::warning("Siswa {$siswa['nama']} tidak memiliki foto.");
                continue;
            }

            $fotoUrl = $siswa['foto']; // URL langsung dari API
            
            // Ambil nama file dari URL
            $fileName = basename($fotoUrl);

            // Lokasi penyimpanan di SLiMS lokal
            $destinationPath = 'D:\laragon\www\slims\images\siswa\member_' . $fileName;

            // Cek apakah file sudah ada, agar tidak mendownload ulang
            if (File::exists($destinationPath)) {
                Log::info("Foto sudah ada: $fileName");
                continue;
            }

            // Gunakan cURL untuk download file agar lebih stabil
            $ch = curl_init($fotoUrl);
            $fp = fopen($destinationPath, 'wb');
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_exec($ch);
            curl_close($ch);
            fclose($fp);

            Log::info("Berhasil mengunduh: $fotoUrl");
        }

        return response()->json(['message' => 'Migrasi foto dari online ke lokal selesai.']);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
    }
}
}
