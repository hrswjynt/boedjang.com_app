<?php

namespace App\Http\Controllers;

use App\ReadinessKompetensi;
use App\ReadinessBagian;
use App\ReadinessKategori;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ReadinessKompetensiController extends Controller
{
    public function index()
    {
        if (Auth::user()->role == 1) {
            return view('readinesskompetensi.index')
                ->with('page', 'readinesskompetensi');
        } else {
            $message_type = 'danger';
            $message = 'Tidak memiliki hak akses untuk melihat data.';
            return redirect()->route('dashboard')->with($message_type, $message);
        }
    }

    public function getData()
    {
        $data = ReadinessKompetensi::select(
            'readiness_kompetensi.id AS id',
            'readiness_bagian.kode AS kode',
            'readiness_kompetensi.nomor AS nomor',
            'readiness_kompetensi.kompetensi AS kompetensi',
            'readiness_bagian.nama AS readiness_bagian',
            'readiness_kompetensi.tipe'
        )
            ->join('readiness_bagian', 'readiness_bagian.id', 'readiness_kompetensi.readiness_bagian')
            ->get();
        return $this->datatable($data);
    }

    public function datatable($data)
    {
        return DataTables::of($data)
            ->addColumn('action', function ($data) {
                $action = '<div class="btn-group">';
                $action .= '<a class="btn btn-sm btn-warning btn-simple shadow" href="' . route("readinesskompetensi.edit", $data->id) . '" title="Edit"><i class="fa fa-edit"></i></a>';
                $action .= '<a href="' . route("readinesskompetensi.delete", $data->id) . '" class="btn btn-sm btn-danger btn-simple readinesskompetensiDelete shadow" title="Hapus" data-id="' . $data->id . '"><i class="fa fa-trash"></i></a>';
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
            $kategori = ReadinessKategori::with('jenis.bagian')->get();

            return view('readinesskompetensi.create')
                ->with('page', 'readinesskompetensi')
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
                    Rule::unique('readiness_kompetensi')
                        ->where(function ($q) use ($request) {
                            $q->where('readiness_bagian', $request->readiness_bagian);
                        })
                ],
                'kompetensi' => 'required|string',
                'readiness_bagian' => 'required|numeric',
                'tipe' => 'required|numeric'
            ]);

            $model = new ReadinessKompetensi;
            $model->nomor = $request->nomor;
            $model->kompetensi = $request->kompetensi;
            $model->readiness_bagian = $request->readiness_bagian;
            $model->tipe = $request->tipe;
            $model->save();

            DB::commit();
            $message_type = 'success';
            $message = 'Kompetensi readiness berhasil dibuat.';
            return redirect()->route('readinesskompetensi.index')->with($message_type, $message);
        } catch (\Throwable $th) {
            DB::rollback();
            $message_type = 'danger';
            $message = 'Kompetensi readiness gagal dibuat.';
            return redirect()->route('readinesskompetensi.create')->withInput()->with($message_type, $message);
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        if (Auth::user()->role == 1) {
            $kategori = ReadinessKategori::with('jenis.bagian')->get();
            $kompetensi = ReadinessKompetensi::select(
                'readiness_kompetensi.id AS id',
                'readiness_kompetensi.nomor AS nomor',
                'readiness_kompetensi.kompetensi AS kompetensi',
                'readiness_jenis.readiness_kategori AS readiness_kategori',
                'readiness_bagian.readiness_jenis AS readiness_jenis',
                'readiness_kompetensi.readiness_bagian AS readiness_bagian',
                'readiness_kompetensi.tipe AS tipe'
            )
                ->join('readiness_bagian', 'readiness_bagian.id', 'readiness_kompetensi.readiness_bagian')
                ->join('readiness_jenis', 'readiness_jenis.id', 'readiness_bagian.readiness_jenis')
                ->where('readiness_kompetensi.id', $id)
                ->first();

            return view('readinesskompetensi.edit')
                ->with('page', 'readinesskompetensi')
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
                    Rule::unique('readiness_kompetensi')
                        ->ignore($id)
                        ->where(function ($q) use ($request) {
                            $q->where('readiness_bagian', $request->readiness_bagian);
                        })
                ],
                'kompetensi' => 'required|string',
                'readiness_bagian' => 'required|numeric',
                'tipe' => 'required|numeric'
            ]);

            $model = ReadinessKompetensi::find($id);
            $model->nomor = $request->nomor;
            $model->kompetensi = $request->kompetensi;
            $model->readiness_bagian = $request->readiness_bagian;
            $model->tipe = $request->tipe;
            $model->save();

            DB::commit();
            $message_type = 'success';
            $message = 'Kompetensi readiness berhasil diubah.';
            return redirect()->route('readinesskompetensi.index')->with($message_type, $message);
        } catch (\Throwable $th) {
            DB::rollback();
            $message_type = 'danger';
            $message = 'Kompetensi readiness gagal diubah.';
            return redirect()->route('readinesskompetensi.edit', $id)->withInput()->with($message_type, $message);
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $readinessKompetensi = ReadinessKompetensi::find($id);

            if (Auth::user()->role == 1) {
                $readinessKompetensi->delete();
            } else {
                throw new \Exception("Tidak memiliki hak akses untuk menghapus data.");
            }

            DB::commit();
            return response()->json([
                'message' => 'Kompetensi readiness berhasil dihapus.',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Kompetensi readiness gagal dihapus.',
                'type' => 'danger',
            ]);
        }
    }
}
