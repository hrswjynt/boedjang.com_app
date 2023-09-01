<?php

namespace App\Http\Controllers;

use App\Kompetensi;
use App\KompetensiBagian;
use App\KompetensiKategori;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class KompetensiController extends Controller
{
    public function index()
    {
        if (Auth::user()->role == 1) {
            return view('kompetensi.index')
                ->with('page', 'kompetensi');
        } else {
            $message_type = 'danger';
            $message = 'Tidak memiliki hak akses untuk melihat data.';
            return redirect()->route('dashboard')->with($message_type, $message);
        }
    }

    public function getData()
    {
        $data = Kompetensi::select(
            'kompetensi.id AS id',
            'kompetensi_bagian.kode AS kode',
            'kompetensi.nomor AS nomor',
            'kompetensi.kompetensi AS kompetensi',
            'kompetensi_bagian.nama AS kompetensi_bagian',
            'kompetensi.tipe'
        )
            ->join('kompetensi_bagian', 'kompetensi_bagian.id', 'kompetensi.kompetensi_bagian')
            ->get();
        return $this->datatable($data);
    }

    public function datatable($data)
    {
        return DataTables::of($data)
            ->addColumn('action', function ($data) {
                $action = '<div class="btn-group">';
                $action .= '<a class="btn btn-sm btn-warning btn-simple shadow" href="' . route("kompetensi.edit", $data->id) . '" title="Edit"><i class="fa fa-edit"></i></a>';
                $action .= '<a href="' . route("kompetensi.delete", $data->id) . '" class="btn btn-sm btn-danger btn-simple kompetensiDelete shadow" title="Hapus" data-id="' . $data->id . '"><i class="fa fa-trash"></i></a>';
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
            $kategori = KompetensiKategori::with('jenis.bagian')->get();

            return view('kompetensi.create')
                ->with('page', 'kompetensi')
                ->with('kategori', $kategori);

            // $bagian = KompetensiBagian::all();

            // return view('kompetensi.create')
            //     ->with('page', 'kompetensi')
            //     ->with('bagian', $bagian);
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
                'nomor' => [
                    'required',
                    'numeric',
                    Rule::unique('kompetensi')
                        ->where(function ($q) use ($request) {
                            $q->where('kompetensi_bagian', $request->kompetensi_bagian);
                        })
                ],
                'kompetensi' => 'required|string',
                'kompetensi_bagian' => 'required|numeric',
                'tipe' => 'required|numeric'
            ]);

            $model = new Kompetensi;
            $model->nomor = $request->nomor;
            $model->kompetensi = $request->kompetensi;
            $model->kompetensi_bagian = $request->kompetensi_bagian;
            $model->tipe = $request->tipe;
            $model->save();

            DB::commit();
            $message_type = 'success';
            $message = 'Kompetensi berhasil dibuat.';
            return redirect()->route('kompetensi.index')->with($message_type, $message);
        } catch (\Throwable $th) {
            DB::rollback();
            $message_type = 'danger';
            $message = 'Kompetensi gagal dibuat.';
            return redirect()->route('kompetensi.create')->with($message_type, $message);
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        if (Auth::user()->role == 1) {
            $kategori = KompetensiKategori::with('jenis.bagian')->get();
            $kompetensi = Kompetensi::select(
                'kompetensi.id AS id',
                'kompetensi.nomor AS nomor',
                'kompetensi.kompetensi AS kompetensi',
                'kompetensi_jenis.kompetensi_kategori AS kompetensi_kategori',
                'kompetensi_bagian.kompetensi_jenis AS kompetensi_jenis',
                'kompetensi.kompetensi_bagian AS kompetensi_bagian',
                'kompetensi.tipe AS tipe'
            )
                ->join('kompetensi_bagian', 'kompetensi_bagian.id', 'kompetensi.kompetensi_bagian')
                ->join('kompetensi_jenis', 'kompetensi_jenis.id', 'kompetensi_bagian.kompetensi_jenis')
                ->where('kompetensi.id', $id)
                ->first();

            return view('kompetensi.edit')
                ->with('page', 'kompetensi')
                ->with('kategori', $kategori)
                ->with('kompetensi', $kompetensi);

            // $bagian = KompetensiBagian::all();
            // $kompetensi = Kompetensi::find($id);

            // return view('kompetensi.edit')
            //     ->with('page', 'kompetensi')
            //     ->with('bagian', $bagian)
            //     ->with('kompetensi', $kompetensi);
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

                'nomor' => [
                    'required',
                    'numeric',
                    Rule::unique('kompetensi')
                        ->ignore($id)
                        ->where(function ($q) use ($request) {
                            $q->where('kompetensi_bagian', $request->kompetensi_bagian);
                        })
                ],
                'kompetensi' => 'required|string',
                'kompetensi_bagian' => 'required|numeric',
                'tipe' => 'required|numeric'
            ]);

            $model = Kompetensi::find($id);
            $model->nomor = $request->nomor;
            $model->kompetensi = $request->kompetensi;
            $model->kompetensi_bagian = $request->kompetensi_bagian;
            $model->tipe = $request->tipe;
            $model->save();

            DB::commit();
            $message_type = 'success';
            $message = 'Kompetensi berhasil diubah.';
            return redirect()->route('kompetensi.index')->with($message_type, $message);
        } catch (\Throwable $th) {
            DB::rollback();
            $message_type = 'danger';
            $message = 'Kompetensi gagal diubah.';
            return redirect()->route('kompetensi.edit', $id)->with($message_type, $message);
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $kompetensi = Kompetensi::find($id);

            if (Auth::user()->role == 1) {
                $kompetensi->delete();
            } else {
                throw new \Exception("Tidak memiliki hak akses untuk menghapus data.");
            }

            DB::commit();
            return response()->json([
                'message' => 'Kompetensi berhasil dihapus.',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Kompetensi gagal dihapus.',
                'type' => 'danger',
            ]);
        }
    }
}
