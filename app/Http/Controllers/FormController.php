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
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;
use App\Models\Kelas;

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
        $kota = $this->getKota();
        // $rekapKelas = DB::table('siswa')
        // ->select('kelas', DB::raw('count(*) as jumlah'))
        // ->groupBy('kelas')
        // ->orderBy('kelas', 'asc') 
        // ->get();
        // $rekapKelas = DB::table('siswa')
        // ->join('kelas', 'siswa.kelas_id', '=', 'kelas.id') // Bergabung dengan tabel kelas
        // ->select('kelas.nama_kelas', DB::raw('count(*) as jumlah')) // Mengambil nama_kelas dan jumlah siswa
        // ->groupBy('kelas.nama_kelas') // Grup berdasarkan nama_kelas
        // ->orderBy('kelas.nama_kelas', 'asc') // Urutkan berdasarkan nama_kelas
        // ->get();

        $kelas = Kelas::all();
        $nis = Siswa::select('nis')->distinct()->orderBy('nis')->get();

        return view('form', compact('kota', 'kelas', 'nis'));

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
            'g-recaptcha-response' => 'required',  // Tambahkan validasi reCAPTCHA
        ]);

        // Verifikasi reCAPTCHA
        $recaptchaSecretKey = env('6Ldea8YqAAAAABO6nnoIS1vsemeuOIzcXQjQukOm');  // Ambil secret key dari .env
        $recaptchaResponse = $request->input('g-recaptcha-response');

        $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
        $response = file_get_contents($verifyUrl . '?secret=' . $recaptchaSecretKey . '&response=' . $recaptchaResponse);
        $responseKeys = json_decode($response, true);

        // Jika verifikasi gagal, kirimkan pesan error
        if (!$responseKeys['success']) {
            return redirect()->back()->withErrors(['captcha' => 'Captcha verification failed.'])->withInput();
        }

        // Proses penyimpanan data siswa
        $siswa = new Siswa();
        $siswa->id_card = '';
        $siswa->nis = $request->nis;
        $siswa->nama = strtoupper($request->nama);
        
        $tempatLahir = $request->tempat_lahir;
        $tanggalLahir = Carbon::parse($request->tanggal_lahir)->translatedFormat('d F Y'); // Contoh: 09 Juli 2005
        $siswa->ttl = $tempatLahir . ', ' . $tanggalLahir;

        $siswa->gender = $request->gender;
        $siswa->alamat = $request->alamat;
        $siswa->wa = $request->wa;
        $siswa->kelas_id = $request->kelas;
        $siswa->email = $request->email;

        // Jika ada file foto yang di-upload
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $originalPath = $foto->getRealPath();
            $extension = $foto->getClientOriginalExtension();
        
            // Ukuran target (4x6 cm = 472x709 pixel)
            $targetWidth = 435;
            $targetHeight = 581;
        
            // Buat gambar dari file yang diupload
            if ($extension === 'jpeg' || $extension === 'jpg') {
                $sourceImage = imagecreatefromjpeg($originalPath);
            } elseif ($extension === 'png') {
                $sourceImage = imagecreatefrompng($originalPath);
            } else {
                return response()->json(['error' => 'Format gambar tidak didukung. Gunakan JPEG atau PNG.']);
            }
        
            // Buat canvas baru untuk ukuran target
            $resizedImage = imagecreatetruecolor($targetWidth, $targetHeight);
        
            // Resize gambar
            imagecopyresampled(
                $resizedImage,
                $sourceImage,
                0, 0, 0, 0,
                $targetWidth, $targetHeight,
                imagesx($sourceImage), imagesy($sourceImage)
            );
        
            // Nama file unik
            $fileName = uniqid() . '.jpg';
            $savePath = storage_path('app/public/images/siswa/') . $fileName;
        
            // Simpan gambar ke file
            imagejpeg($resizedImage, $savePath, 75); // Kompresi 75%
        
            // Simpan nama file ke database
            $siswa->foto = $fileName;
        
            // Bersihkan memori
            imagedestroy($sourceImage);
            imagedestroy($resizedImage);
        }

        // Simpan data siswa ke database
        $siswa->save();
        
        // Kirim email setelah data berhasil disimpan
        Mail::send('emails.form_submitted', ['siswa' => $siswa], function($message) use ($request) {
            $message->to($request->email)
                    ->subject('Form Submitted');
        });

        // Redirect setelah data berhasil disimpan
        return redirect()->route('form.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function downloadReport()
    {
        // Ambil data siswa beserta relasi kelas (eager loading)
        $data = Siswa::with('kelas')->get();
        $no = 1;

        // Transformasi data untuk laporan
        $pelajarData = $data->map(function ($pelajar) use (&$no) {
            return [
                'NO' => $no++,
                'RFID' => $pelajar->id_card,
                'NIS' => $pelajar->nis,
                'Nama' => $pelajar->nama,
                'Tempat, Tanggal Lahir' => $pelajar->ttl,
                'Jenis Kelamin' => $pelajar->gender,
                'Alamat' => $pelajar->alamat,
                'Nomor HP (WA)' => $pelajar->wa,
                'Kelas' => $pelajar->kelas ? $pelajar->kelas->nama_kelas : '-', // Nama kelas atau '-'
            ];
        });

        // Ekspor ke Excel menggunakan FastExcel
        return (new FastExcel($pelajarData))->download('siswa_report.xlsx');
    }

    public function downloadClassPdf(Request $request)
    {
        // Ambil parameter nama_kelas dari query
        $namaKelas = $request->query('kelas');

        // Validasi jika kelas tidak dipilih
        if (!$namaKelas) {
            return back()->with('error', 'Silakan pilih kelas.');
        }

        // Ambil id kelas berdasarkan nama_kelas
        $kelas = Kelas::where('nama_kelas', $namaKelas)->first();

        if (!$kelas) {
            return back()->with('error', 'Kelas tidak ditemukan.');
        }

        $dataSiswa = Siswa::where('kelas_id', $kelas->id)->get();

        // Cek apakah ada data siswa dalam kelas tersebut
        if ($dataSiswa->isEmpty()) {
            return back()->with('error', 'Tidak ada siswa dalam kelas ini.');
        }

        // Initialize Mpdf
        $mpdf = new \Mpdf\Mpdf([
            'format' => [88, 53.98], // Ukuran ID card dalam mm
            'margin_left' => 0,
            'margin_right' => 0,
            'margin_top' => 0,
            'margin_bottom' => 0,
            'orientation' => 'P'
        ]);

        // Load the view dan pass data ke view
        $pdfContent = view('reports.siswa_perkelas', [
            'dataSiswa' => $dataSiswa,
        ])->render();

        // Tulis HTML ke Mpdf
        $mpdf->WriteHTML($pdfContent);

        // Output PDF untuk diunduh
        return $mpdf->Output("Kartu_Siswa_Kelas_{$namaKelas}.pdf", 'D'); // 'D' untuk download
    }

    public function downloadCardPdf(Request $request)
    {
        $nis = $request->query('nis');

        if (!$nis) {
            return back()->with('error', 'Silakan pilih NIS.');
        }

        // Filter siswa berdasarkan NIS
        $dataSiswa = Siswa::where('nis', $nis)->first();

        if (!$dataSiswa) {
            return back()->with('error', 'Data siswa tidak ditemukan.');
        }

        // Initialize Mpdf
        $mpdf = new Mpdf([
            'format' => [88, 53.98], // Ukuran ID card dalam mm
            'margin_left' => 0,
            'margin_right' => 0,
            'margin_top' => 0,
            'margin_bottom' => 0,
            'orientation' => 'P'
        ]);

        // Load the view and pass data
        $pdfContent = view('reports.kartu_siswa', [
            'dataSiswa' => $dataSiswa,
        ])->render();

        // Write HTML content to Mpdf
        $mpdf->WriteHTML($pdfContent);

        // Output PDF as download
        return $mpdf->Output("Kartu_Siswa_{$nis}.pdf", 'D'); // 'D' untuk download
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
        $tanggal_lahir = Carbon::createFromFormat('d F Y', $ttl[1])->format('Y-m-d');
        $kota = $this->getKota();

        $kelas = Kelas::all();

        return view('form.edit', compact('data', 'kelas', 'kota', 'tempat_lahir', 'tanggal_lahir'));
    }

    private function getKota()
    {
        $path = storage_path('app/data/kota.json'); // Ubah path sesuai tempat penyimpanan file
        $json = file_get_contents($path);
        $data = json_decode($json, true); // Decode JSON menjadi array

        $kota = []; // Inisialisasi array kosong untuk kota

        // Loop melalui provinsi dan kota
        foreach ($data as $provinsi) {
            foreach ($provinsi['kota'] as $namaKota) {
                // Hapus imbuhan "Kota" dan "Kab." dari nama kota
                $namaKotaBersih = str_replace(['Kota ', 'Kab. '], '', $namaKota);
                $kota[] = $namaKotaBersih;
            }
        }

        return $kota;
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
        $tempatLahir = $request->tempat_lahir;
        $tanggalLahir = Carbon::parse($request->tanggal_lahir)->translatedFormat('d F Y'); // Contoh: 09 Juli 2005
        $ttl = $tempatLahir . ', ' . $tanggalLahir;

        $data->update([
            'nis' => $request->nis,
            'nama' => strtoupper($request->nama),
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
        
            $foto = $request->file('foto');
            $originalPath = $foto->getRealPath();
            $extension = $foto->getClientOriginalExtension();
        
            // Ukuran target (4x6 cm = 435x581 pixel)
            $targetWidth = 435;
            $targetHeight = 581;
        
            // Buat gambar dari file yang diupload
            if ($extension === 'jpeg' || $extension === 'jpg') {
                $sourceImage = imagecreatefromjpeg($originalPath);
            } elseif ($extension === 'png') {
                $sourceImage = imagecreatefrompng($originalPath);
            } else {
                return response()->json(['error' => 'Format gambar tidak didukung. Gunakan JPEG atau PNG.']);
            }
        
            // Buat canvas baru untuk ukuran target
            $resizedImage = imagecreatetruecolor($targetWidth, $targetHeight);
        
            // Resize gambar
            imagecopyresampled(
                $resizedImage,
                $sourceImage,
                0, 0, 0, 0,
                $targetWidth, $targetHeight,
                imagesx($sourceImage), imagesy($sourceImage)
            );
        
            // Nama file unik
            $fileName = uniqid() . '.jpg';
            $savePath = storage_path('app/public/images/siswa/') . $fileName;
        
            // Simpan gambar ke file
            imagejpeg($resizedImage, $savePath, 75); // Kompresi 75%
        
            // Simpan nama file baru ke database
            $data->foto = $fileName;
            $data->save();
        
            // Bersihkan memori
            imagedestroy($sourceImage);
            imagedestroy($resizedImage);
        }        

        return redirect()->route('form.index')->with('success', 'Data Siswa berhasil diupdate 👍');
    }

    public function destroy($id)
    {
        $data = Siswa::findOrFail($id);

        if ($data->foto) {
            Storage::disk('public')->delete('images/siswa/' . $data->foto);
        }

        $data->delete();

        return response()->json(['success' => 'Data Siswa berhasil dihapus 👍']);
    }
}
