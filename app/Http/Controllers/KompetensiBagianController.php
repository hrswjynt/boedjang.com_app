<?php

namespace App\Http\Controllers;

use App\KompetensiJenis;
use App\KompetensiBagian;
use App\KompetensiKategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class KompetensiBagianController extends Controller
{
    public function index()
    {
        if (Auth::user()->role == 1) {
            return view('kompetensibagian.index')
                ->with('page', 'kompetensibagian');
        } else {
            $message_type = 'danger';
            $message = 'Tidak memiliki hak akses untuk melihat data.';
            return redirect()->route('dashboard')->with($message_type, $message);
        }
    }

    public function getData()
    {
        $data = KompetensiBagian::select('kompetensi_bagian.id AS id', 'kompetensi_bagian.kode', 'kompetensi_bagian.nama AS nama', 'kompetensi_jenis.nama AS kompetensi_jenis')
            ->join('kompetensi_jenis', 'kompetensi_jenis.id', 'kompetensi_bagian.kompetensi_jenis')
            ->get();
        return $this->datatable($data);
    }

    public function datatable($data)
    {
        return DataTables::of($data)
            ->addColumn('action', function ($data) {
                $action = '<div class="btn-group">';
                $action .= '<a class="btn btn-sm btn-warning btn-simple shadow" href="' . route("kompetensibagian.edit", $data->id) . '" title="Edit"><i class="fa fa-edit"></i></a>';
                $action .= '<a href="' . route("kompetensibagian.delete", $data->id) . '" class="btn btn-sm btn-danger btn-simple kompetensibagianDelete shadow" title="Hapus" data-id="' . $data->id . '"><i class="fa fa-trash"></i></a>';
                $action .= '</div>';

                return $action;
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
    }

    public function create()
    {
        if (Auth::user()->role == 1) {
            $kategori = KompetensiKategori::with('jenis')->get();

            return view('kompetensibagian.create')
                ->with('page', 'kompetensibagian')
                ->with('kategori', $kategori);

            // $jenis = KompetensiJenis::all();

            // return view('kompetensibagian.create')
            //     ->with('page', 'kompetensibagian')
            //     ->with('jenis', $jenis);
        } else {
            $message_type = 'danger';
            $message = 'Tidak memiliki hak akses untuk menambah data.';
            return redirect()->route('dashboard')->with($message_type, $message);
        }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            request()->validate([
                'kode' => 'required|string|unique:kompetensi_bagian,kode',
                'nama' => 'required|string',
                'kompetensi_jenis' => 'required|numeric'
            ]);

            $model = new KompetensiBagian;
            $model->kode = $request->kode;
            $model->nama = $request->nama;
            $model->kompetensi_jenis = $request->kompetensi_jenis;
            $model->save();

            DB::commit();
            $message_type = 'success';
            $message = 'Bagian kompetensi berhasil dibuat.';
            return redirect()->route('kompetensibagian.index')->with($message_type, $message);
        } catch (\Throwable $th) {
            DB::rollback();
            $message_type = 'danger';
            $message = 'Bagian kompetensi gagal dibuat.';
            return redirect()->route('kompetensibagian.create')->with($message_type, $message);
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        if (Auth::user()->role == 1) {
            $kategori = KompetensiKategori::with('jenis')->get();
            $bagian = KompetensiBagian::select(
                'kompetensi_bagian.id AS id',
                'kompetensi_bagian.kode AS kode',
                'kompetensi_bagian.nama AS nama',
                'kompetensi_jenis.kompetensi_kategori AS kompetensi_kategori',
                'kompetensi_bagian.kompetensi_jenis As kompetensi_jenis',
            )
                ->join('kompetensi_jenis', 'kompetensi_jenis.id', 'kompetensi_bagian.kompetensi_jenis')
                ->where('kompetensi_bagian.id', $id)
                ->first();

            return view('kompetensibagian.edit')
                ->with('page', 'kompetensibagian')
                ->with('kategori', $kategori)
                ->with('bagian', $bagian);

            // $jenis = KompetensiJenis::all();
            // $bagian = KompetensiBagian::find($id);

            // return view('kompetensibagian.edit')
            //     ->with('page', 'kompetensibagian')
            //     ->with('jenis', $jenis)
            //     ->with('bagian', $bagian);
        } else {
            $message_type = 'danger';
            $message = 'Tidak memiliki hak akses untuk mengubah data.';
            return redirect()->route('dashboard')->with($message_type, $message);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            request()->validate([
                'kode' => 'required|string|unique:kompetensi_bagian,kode,' . $id,
                'nama' => 'required|string',
                'kompetensi_jenis' => 'required|numeric'
            ]);

            $model = KompetensiBagian::find($id);
            $model->kode = $request->kode;
            $model->nama = $request->nama;
            $model->kompetensi_jenis = $request->kompetensi_jenis;
            $model->save();

            DB::commit();
            $message_type = 'success';
            $message = 'Bagian kompetensi berhasil diubah.';
            return redirect()->route('kompetensibagian.index')->with($message_type, $message);
        } catch (\Throwable $th) {
            DB::rollback();
            $message_type = 'danger';
            $message = 'Bagian kompetensi gagal diubah.';
            return redirect()->route('kompetensibagian.edit', $id)->with($message_type, $message);
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $kompetensibagian = KompetensiBagian::find($id);

            if (Auth::user()->role == 1) {
                $kompetensibagian->delete();
            } else {
                throw new \Exception("Tidak memiliki hak akses untuk menghapus data.");
            }

            DB::commit();
            return response()->json([
                'message' => 'Bagian Kompetensi berhasil dihapus.',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Bagian Kompetensi gagal dihapus.',
                'type' => 'danger',
            ]);
        }
    }
}
