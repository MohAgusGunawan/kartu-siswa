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

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // $data = Siswa::all();
            $data = Siswa::table('siswa')
            ->orderByRaw("id_card IS NULL OR id_card = '' DESC")
            ->get();
            $data = $data->map(function($item) {
                return array_map('utf8_encode', $item->toArray());
            });
            // dd($data);

            return Datatables::of($data)->make(true);
        }
        $kota = $this->getKota();
        $rekapKelas = DB::table('siswa')
        ->select('kelas', DB::raw('count(*) as jumlah'))
        ->groupBy('kelas')
        ->orderBy('kelas', 'asc') 
        ->get();

        $kelas = Siswa::select('kelas')->distinct()->orderBy('kelas')->get();
        $nis = Siswa::select('nis')->distinct()->orderBy('nis')->get();

        return view('dashboard', compact('kota', 'rekapKelas', 'kelas', 'nis'));

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

    public function downloadClassPdf(Request $request)
    {
        $kelas = $request->query('kelas');

        if (!$kelas) {
            return back()->with('error', 'Silakan pilih kelas.');
        }

        $dataSiswa = Siswa::where('kelas', $kelas)->get();

        // Initialize Mpdf
        $mpdf = new Mpdf();

        // Load the view and pass data
        $pdfContent = view('reports.siswa_perkelas', [
            'dataSiswa' => $dataSiswa,
            'kelas' => $kelas,
        ])->render();

        // Write HTML content to Mpdf
        $mpdf->WriteHTML($pdfContent);

        // Output PDF as download
        return $mpdf->Output("Laporan_Kelas_{$kelas}.pdf", 'D'); // 'D' untuk download
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
            'format' => [85.6, 53.98], // Ukuran ID card dalam mm
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

    public function show($id)
    {
        $data = Siswa::findOrFail($id);
        return view('dashboard.show', compact('data'));
    }

    public function edit($id)
    {
        $data = Siswa::findOrFail($id);
        $ttl = explode(', ', $data->ttl);
        $tempat_lahir = $ttl[0]; 
        $tanggal_lahir = Carbon::createFromFormat('d F Y', $ttl[1])->format('Y-m-d');
        $kota = $this->getKota();

        return view('dashboard.edit', compact('data', 'kota', 'tempat_lahir', 'tanggal_lahir'));
    }

    public function update(Request $request, $id)
    {
        // Validasi data
        $validated = $request->validate([
            'data' => 'required|string|max:255',
        ]);

        // Update data berdasarkan ID
        $item = Siswa::findOrFail($id);
        $item->update(['id_card' => $validated['data']]);

        // Redirect dengan pesan sukses
        return redirect()->route('dashboard.index')->with('success', 'Data updated successfully.');
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
