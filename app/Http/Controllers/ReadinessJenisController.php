<?php

namespace App\Http\Controllers;

use App\ReadinessJenis;
use App\ReadinessKategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ReadinessJenisController extends Controller
{
    public function index()
    {
        if (Auth::user()->role == 1) {
            return view('readinessjenis.index')
                ->with('page', 'readinessjenis');
        } else {
            $message_type = 'danger';
            $message = 'Tidak memiliki hak akses untuk melihat data.';
            return redirect()->route('dashboard')->with($message_type, $message);
        }
    }

    public function getData()
    {
        $data = ReadinessJenis::select(
            'readiness_jenis.id AS id',
            'readiness_jenis.nama AS nama',
            'readiness_kategori.nama AS readiness_kategori',
        )
            ->join('readiness_kategori', 'readiness_kategori.id', 'readiness_jenis.readiness_kategori')
            ->get();
        return $this->datatable($data);
    }

    public function datatable($data)
    {
        return DataTables::of($data)
            ->addColumn('action', function ($data) {
                $action = '<div class="btn-group">';
                $action .= '<a class="btn btn-sm btn-warning btn-simple shadow" href="' . route("readinessjenis.edit", $data->id) . '" title="Edit"><i class="fa fa-edit"></i></a>';
                $action .= '<a href="' . route("readinessjenis.delete", $data->id) . '" class="btn btn-sm btn-danger btn-simple readinessjenisDelete shadow" title="Hapus" data-id="' . $data->id . '"><i class="fa fa-trash"></i></a>';
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
            $kategori = ReadinessKategori::all();

            return view('readinessjenis.create')
                ->with('page', 'readinessjenis')
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
                'readiness_kategori' => 'required|numeric'
            ]);

            $model = new ReadinessJenis;
            $model->nama = $request->nama;
            $model->readiness_kategori = $request->readiness_kategori;
            $model->save();

            DB::commit();
            $message_type = 'success';
            $message = 'Jenis readiness berhasil dibuat.';
            return redirect()->route('readinessjenis.index')->with($message_type, $message);
        } catch (\Throwable $th) {
            DB::rollback();
            $message_type = 'danger';
            $message = 'Jenis readiness gagal dibuat.';
            return redirect()->route('readinessjenis.create')->with($message_type, $message);
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        if (Auth::user()->role == 1) {
            $kategori = ReadinessKategori::all();
            $jenis = ReadinessJenis::find($id);


            return view('readinessjenis.edit')
                ->with('page', 'readinessjenis')
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
                'readiness_kategori' => 'required|numeric'
            ]);

            $model = ReadinessJenis::find($id);
            $model->nama = $request->nama;
            $model->readiness_kategori = $request->readiness_kategori;
            $model->save();

            DB::commit();
            $message_type = 'success';
            $message = 'Jenis readiness berhasil diubah.';
            return redirect()->route('readinessjenis.index')->with($message_type, $message);
        } catch (\Throwable $th) {
            DB::rollback();
            $message_type = 'danger';
            $message = 'Jenis readiness gagal diubah.';
            return redirect()->route('readinessjenis.edit', $id)->with($message_type, $message);
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $readinessjenis = ReadinessJenis::find($id);

            if (Auth::user()->role == 1) {
                $readinessjenis->delete();
            } else {
                throw new \Exception("Tidak memiliki hak akses untuk menghapus data.");
            }

            DB::commit();
            return response()->json([
                'message' => 'Jenis Readiness berhasil dihapus.',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Jenis Readiness gagal dihapus.',
                'type' => 'danger',
            ]);
        }
    }
}
