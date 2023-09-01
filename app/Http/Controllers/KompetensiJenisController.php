<?php

namespace App\Http\Controllers;

use App\KompetensiJenis;
use App\KompetensiKategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class KompetensiJenisController extends Controller
{
    public function index()
    {
        if (Auth::user()->role == 1) {
            return view('kompetensijenis.index')
                ->with('page', 'kompetensijenis');
        } else {
            $message_type = 'danger';
            $message = 'Tidak memiliki hak akses untuk melihat data.';
            return redirect()->route('dashboard')->with($message_type, $message);
        }
    }

    public function getData()
    {
        $data = KompetensiJenis::select(
            'kompetensi_jenis.id AS id',
            'kompetensi_jenis.nama AS nama',
            'kompetensi_kategori.nama AS kompetensi_kategori',
        )
            ->join('kompetensi_kategori', 'kompetensi_kategori.id', 'kompetensi_jenis.kompetensi_kategori')
            ->get();
        return $this->datatable($data);
    }

    public function datatable($data)
    {
        return DataTables::of($data)
            ->addColumn('action', function ($data) {
                $action = '<div class="btn-group">';
                $action .= '<a class="btn btn-sm btn-warning btn-simple shadow" href="' . route("kompetensijenis.edit", $data->id) . '" title="Edit"><i class="fa fa-edit"></i></a>';
                $action .= '<a href="' . route("kompetensijenis.delete", $data->id) . '" class="btn btn-sm btn-danger btn-simple kompetensijenisDelete shadow" title="Hapus" data-id="' . $data->id . '"><i class="fa fa-trash"></i></a>';
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
            $kategori = KompetensiKategori::all();

            return view('kompetensijenis.create')
                ->with('page', 'kompetensijenis')
                ->with('kategori', $kategori);
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
                'nama' => 'required|string',
                'kompetensi_kategori' => 'required|numeric'
            ]);

            $model = new KompetensiJenis;
            $model->nama = $request->nama;
            $model->kompetensi_kategori = $request->kompetensi_kategori;
            $model->save();

            DB::commit();
            $message_type = 'success';
            $message = 'Jenis kompetensi berhasil dibuat.';
            return redirect()->route('kompetensijenis.index')->with($message_type, $message);
        } catch (\Throwable $th) {
            DB::rollback();
            $message_type = 'danger';
            $message = 'Jenis kompetensi gagal dibuat.';
            return redirect()->route('kompetensijenis.create')->with($message_type, $message);
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        if (Auth::user()->role == 1) {
            $kategori = KompetensiKategori::all();
            $jenis = KompetensiJenis::find($id);


            return view('kompetensijenis.edit')
                ->with('page', 'kompetensijenis')
                ->with('kategori', $kategori)
                ->with('jenis', $jenis);
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
                'nama' => 'required|string',
                'kompetensi_kategori' => 'required|numeric'
            ]);

            $model = KompetensiJenis::find($id);
            $model->nama = $request->nama;
            $model->kompetensi_kategori = $request->kompetensi_kategori;
            $model->save();

            DB::commit();
            $message_type = 'success';
            $message = 'Jenis kompetensi berhasil diubah.';
            return redirect()->route('kompetensijenis.index')->with($message_type, $message);
        } catch (\Throwable $th) {
            DB::rollback();
            $message_type = 'danger';
            $message = 'Jenis kompetensi gagal diubah.';
            return redirect()->route('kompetensijenis.edit', $id)->with($message_type, $message);
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $kompetensijenis = KompetensiJenis::find($id);

            if (Auth::user()->role == 1) {
                $kompetensijenis->delete();
            } else {
                throw new \Exception("Tidak memiliki hak akses untuk menghapus data.");
            }

            DB::commit();
            return response()->json([
                'message' => 'Jenis Kompetensi berhasil dihapus.',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Jenis Kompetensi gagal dihapus.',
                'type' => 'danger',
            ]);
        }
    }
}
