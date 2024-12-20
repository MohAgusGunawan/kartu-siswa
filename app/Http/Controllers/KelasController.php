<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class KelasController extends Controller
{
    public function index()
    {
        return view('kelas');
    }

    public function getKelasData()
    {
        $kelas = Kelas::all();
        return DataTables::of($kelas)
            ->addColumn('action', function ($row) {
                return '
                    <button class="btn btn-sm btn-primary editKelas" data-id="' . $row->id . '" data-nama="' . $row->nama_kelas . '"><i class="fa-solid fa-pen-to-square"></i></button>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    // public function getKelasData()
    // {
    //     $kelas = Kelas::all();
    //     return DataTables::of($kelas)
    //         ->addColumn('action', function ($row) {
    //             return '
    //                 <button class="btn btn-sm btn-primary editKelas" data-id="' . $row->id . '" data-nama="' . $row->nama_kelas . '">Edit</button>
    //                 <button class="btn btn-sm btn-danger deleteKelas" data-id="' . $row->id . '">Hapus</button>
    //             ';
    //         })
    //         ->rawColumns(['action'])
    //         ->make(true);
    // }

    public function store(Request $request)
    {
        $request->validate(['nama_kelas' => 'required|unique:kelas']);

        Kelas::create(['nama_kelas' => $request->nama_kelas]);

        return response()->json(['success' => 'Kelas berhasil ditambahkan']);
    }

    public function update(Request $request, $id)
    {
        $request->validate(['nama_kelas' => 'required|unique:kelas,nama_kelas,' . $id]);

        $kelas = Kelas::find($id);
        $kelas->update(['nama_kelas' => $request->nama_kelas]);

        return response()->json(['success' => 'Kelas berhasil diperbarui']);
    }

    public function destroy($id)
    {
        Kelas::destroy($id);

        return response()->json(['success' => 'Kelas berhasil dihapus']);
    }
}
