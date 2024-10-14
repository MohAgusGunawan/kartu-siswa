<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Mail\FormSubmitted;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Siswa::all();
            $data = $data->map(function($item) {
                return array_map('utf8_encode', $item->toArray());
            });
            // dd($data);

            return Datatables::of($data)->make(true);
        }
        $path = storage_path('app/data/kota.json'); // Ubah path sesuai tempat penyimpanan file
        $json = file_get_contents($path);
        $data = json_decode($json, true); // Decode JSON menjadi array

        $kota = []; // Inisialisasi array kosong untuk kota

        // Loop melalui provinsi dan kota
        foreach ($data as $provinsi) {
            // Menggabungkan semua kota dalam satu array
            $kota = array_merge($kota, $provinsi['kota']);
        }

        return view('form', compact('kota'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // return view('siswa.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'nis' => 'required|integer|unique:siswa,nis',
            'nama' => 'required|string|max:100',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'alamat' => 'required|string|max:50',
            'wa' => 'required|string|max:20',
            'kelas' => 'required|string|max:10',
            'email' => 'required|email',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $siswa = new Siswa();
        $siswa->nis = $request->nis;
        $siswa->nama = $request->nama;
        $tempatLahir = $request->tempat_lahir;
        $tanggalLahir = $request->tanggal_lahir;
        $siswa->ttl = $tempatLahir . ', ' . $tanggalLahir;
        $siswa->gender = $request->gender;
        $siswa->alamat = $request->alamat;
        $siswa->wa = $request->wa;
        $siswa->kelas = $request->kelas;
        $siswa->email = $request->email;

        // Jika ada file foto yang di-upload
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');

            // Resize gambar menggunakan GD atau Imagick
            $img = imagecreatefromstring(file_get_contents($foto->getRealPath()));

            // Ukuran baru untuk 4x6 (472x709 pixel)
            $newWidth = 472;
            $newHeight = 709;

            // Membuat gambar baru dengan ukuran yang diinginkan
            $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($resizedImage, $img, 0, 0, 0, 0, $newWidth, $newHeight, imagesx($img), imagesy($img));

            // Kompres gambar ke 200KB jika diperlukan
            ob_start();
            imagejpeg($resizedImage, null, 75); // Menggunakan kualitas 75%
            $compressedImageData = ob_get_contents();
            ob_end_clean();

            // Jika ukuran masih lebih dari 200KB, kompres lebih lanjut
            if (strlen($compressedImageData) > 200 * 1024) {
                ob_start();
                imagejpeg($resizedImage, null, 50); // Kompres lebih lanjut jika perlu
                $compressedImageData = ob_get_contents();
                ob_end_clean();
            }

            // Simpan gambar yang sudah di-resize ke dalam file temporer
            $tempPath = sys_get_temp_dir() . '/' . uniqid() . '.jpg';
            file_put_contents($tempPath, $compressedImageData);

            // Simpan gambar ke folder storage/app/public/images/siswa
            $path = $foto->storeAs('images/siswa', basename($tempPath), 'public');

            // Simpan path file ke dalam database
            $siswa->foto = basename($tempPath);

            // Membersihkan memori
            imagedestroy($img);
            imagedestroy($resizedImage);
        }

        // Simpan data siswa ke database
        $siswa->save();
        
        Mail::to($request->email)->send(new FormSubmitted($request->all()));

        // Redirect setelah data berhasil disimpan
        return redirect()->route('form.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function downloadReport()
    {
        $data = Siswa::all();
        $no = 1;

        $pelajarData = $data->map(function ($pelajar) use (&$no) {
            return [
                'NO' => $no++,
                'NIS' => $pelajar->nis,
                'Nama' => $pelajar->nama,
                'Tempat, Tanggal Lahir' => $pelajar->ttl,
                'Jenis Kelamin' => $pelajar->gender,
                'Alamat' => $pelajar->alamat,
                'Nomor HP (WA)' => $pelajar->wa,
                'Kelas' => $pelajar->kelas,
            ];
        });

        return (new FastExcel($pelajarData))->download('siswa_report.xlsx');
    }

    public function show($id)
    {
        $data = Siswa::findOrFail($id);
        return view('form.show', compact('data'));
    }

    public function edit($id)
    {
        $data = Siswa::findOrFail($id);
        $ttl = explode(', ', $data->ttl);
        $tempat_lahir = $ttl[0]; 
        $tanggal_lahir = $ttl[1];

        return view('form.edit', compact('data', 'tempat_lahir', 'tanggal_lahir'));
    }

    public function update(Request $request, $id)
    {
        $data = Siswa::findOrFail($id);

        // Validasi
        $request->validate([
            'nis' => 'required|integer',
            'nama' => 'required|string|max:100',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'alamat' => 'required|string|max:50',
            'wa' => 'required|string|max:20',
            'kelas' => 'required|string|max:10',
            'email' => 'required|email',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Update data
        $ttl = $request->tempat_lahir . ', ' . $request->tanggal_lahir;

        $data->update([
            'nis' => $request->nis,
            'nama' => $request->nama,
            'ttl' => $ttl,  
            'gender' => $request->gender,
            'alamat' => $request->alamat,
            'wa' => $request->wa,
            'kelas' => $request->kelas,
            'email' => $request->email,
        ]);

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($data->foto) {
                Storage::disk('public')->delete('images/siswa/' . $data->foto);
            }

            // Simpan foto baru
            $file = $request->file('foto');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/images/siswa/', $filename);
            $data->foto = $filename;
            $data->save();
        }

        return redirect()->route('form.index')->with('success', 'Data Siswa berhasil diupdate 👍');
    }

    public function destroy($id)
    {
        $data = Siswa::findOrFail($id);
        
        // Hapus foto pegawai jika ada
        if ($data->foto) {
            Storage::delete('images/siswa/' . $data->foto);
        }

        $data->delete();

        return response()->json(['success' => 'Data pegawai berhasil dihapus 👍']);
    }
}
