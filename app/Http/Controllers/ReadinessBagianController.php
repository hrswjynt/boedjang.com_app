<?php

namespace App\Http\Controllers;

use App\ReadinessJenis;
use App\ReadinessBagian;
use App\ReadinessKategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ReadinessBagianController extends Controller
{
    public function index()
    {
        if (Auth::user()->role == 1) {
            return view('readinessbagian.index')
                ->with('page', 'readinessbagian');
        } else {
            $message_type = 'danger';
            $message = 'Tidak memiliki hak akses untuk melihat data.';
            return redirect()->route('dashboard')->with($message_type, $message);
        }
    }

    public function getData()
    {
        $data = ReadinessBagian::select('readiness_bagian.id AS id', 'readiness_bagian.kode', 'readiness_bagian.nama AS nama', 'readiness_jenis.nama AS readiness_jenis')
            ->join('readiness_jenis', 'readiness_jenis.id', 'readiness_bagian.readiness_jenis')
            ->get();
        return $this->datatable($data);
    }

    public function datatable($data)
    {
        return DataTables::of($data)
            ->addColumn('action', function ($data) {
                $action = '<div class="btn-group">';
                $action .= '<a class="btn btn-sm btn-warning btn-simple shadow" href="' . route("readinessbagian.edit", $data->id) . '" title="Edit"><i class="fa fa-edit"></i></a>';
                $action .= '<a href="' . route("readinessbagian.delete", $data->id) . '" class="btn btn-sm btn-danger btn-simple readinessbagianDelete shadow" title="Hapus" data-id="' . $data->id . '"><i class="fa fa-trash"></i></a>';
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
            $kategori = ReadinessKategori::with('jenis')->get();

            return view('readinessbagian.create')
                ->with('page', 'readinessbagian')
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
                'kode' => 'required|string|unique:readiness_bagian,kode',
                'nama' => 'required|string',
                'readiness_jenis' => 'required|numeric'
            ]);

            $model = new ReadinessBagian;
            $model->kode = $request->kode;
            $model->nama = $request->nama;
            $model->readiness_jenis = $request->readiness_jenis;
            $model->save();

            DB::commit();
            $message_type = 'success';
            $message = 'Bagian readiness berhasil dibuat.';
            return redirect()->route('readinessbagian.index')->with($message_type, $message);
        } catch (\Throwable $th) {
            DB::rollback();
            $message_type = 'danger';
            $message = 'Bagian readiness gagal dibuat.';
            return redirect()->route('readinessbagian.create')->with($message_type, $message);
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        if (Auth::user()->role == 1) {
            $kategori = ReadinessKategori::with('jenis')->get();
            $bagian = ReadinessBagian::select(
                'readiness_bagian.id AS id',
                'readiness_bagian.kode AS kode',
                'readiness_bagian.nama AS nama',
                'readiness_jenis.readiness_kategori AS readiness_kategori',
                'readiness_bagian.readiness_jenis As readiness_jenis',
            )
                ->join('readiness_jenis', 'readiness_jenis.id', 'readiness_bagian.readiness_jenis')
                ->where('readiness_bagian.id', $id)
                ->first();

            return view('readinessbagian.edit')
                ->with('page', 'readinessbagian')
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
                'kode' => 'required|string|unique:readiness_bagian,kode,' . $id,
                'nama' => 'required|string',
                'readiness_jenis' => 'required|numeric'
            ]);

            $model = ReadinessBagian::find($id);
            $model->kode = $request->kode;
            $model->nama = $request->nama;
            $model->readiness_jenis = $request->readiness_jenis;
            $model->save();

            DB::commit();
            $message_type = 'success';
            $message = 'Bagian readiness berhasil diubah.';
            return redirect()->route('readinessbagian.index')->with($message_type, $message);
        } catch (\Throwable $th) {
            DB::rollback();
            $message_type = 'danger';
            $message = 'Bagian readiness gagal diubah.';
            return redirect()->route('readinessbagian.edit', $id)->with($message_type, $message);
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $readinessbagian = ReadinessBagian::find($id);

            if (Auth::user()->role == 1) {
                $readinessbagian->delete();
            } else {
                throw new \Exception("Tidak memiliki hak akses untuk menghapus data.");
            }

            DB::commit();
            return response()->json([
                'message' => 'Bagian Readiness berhasil dihapus.',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Bagian Readiness gagal dihapus.',
                'type' => 'danger',
            ]);
        }
    }
}
