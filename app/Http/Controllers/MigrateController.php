<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    public function migrateSiswaToSlims(Request $request)
    {
        try {
            // Ambil data siswa dari database utama
            $siswaData = DB::table('siswa')->get();

            foreach ($siswaData as $siswa) {
                // Transformasi gender
                $gender = $siswa->gender === 'Laki-laki' ? 1 : 0;

                // Ekstraksi tanggal lahir dari kolom TTL
                $ttlParts = explode(',', $siswa->ttl);
                $birthDate = isset($ttlParts[1]) ? date('Y-m-d', strtotime(trim($ttlParts[1]))) : null;

                // Tambahkan prefix "member_" pada nama file foto
                $memberImage = 'member_' . $siswa->foto;

                // Tentukan tanggal kedaluwarsa (1 tahun dari sekarang)
                $expireDate = now()->addYear()->format('Y-m-d');

                // Gunakan updateOrInsert untuk update jika member_id ada, atau insert jika tidak ada
                DB::connection('slims')->table('member')->updateOrInsert(
                    ['member_id' => $siswa->nis], // Kondisi pencarian
                    [
                        'member_name' => $siswa->nama,
                        'gender' => $gender,
                        'birth_date' => $birthDate,
                        'member_address' => $siswa->alamat,
                        'member_image' => $memberImage,
                        'pin' => $siswa->id_card,
                        'expire_date' => $expireDate,
                    ]
                );
            }

            return response()->json(['message' => 'Migrasi data berhasil!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function migratePhotos()
    {
        $siswaData = DB::table('siswa')->get(); // Ambil data siswa dari database Kartu Siswa

        foreach ($siswaData as $siswa) {
            // Lokasi sumber dan tujuan file
            $sourcePath = storage_path('app/public/images/siswa/' . $siswa->foto);
            $destinationPath = 'D:\laragon\www\slims\images\siswa\member_' . $siswa->foto;

            // Periksa apakah file sumber ada
            if (File::exists($sourcePath)) {
                // Buat folder tujuan jika belum ada
                $destinationDir = dirname($destinationPath);
                if (!File::exists($destinationDir)) {
                    File::makeDirectory($destinationDir, 0755, true);
                }

                // Salin file ke lokasi tujuan
                File::copy($sourcePath, $destinationPath);
            } else {
                // Log jika file tidak ditemukan
                Log::warning("File tidak ditemukan: $sourcePath");
            }
        }

        return response()->json(['message' => 'Migrasi file selesai.']);
    }
}
