<?php

namespace App\Http\Controllers;

use App\KompetensiKategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class KompetensiKategoriController extends Controller
{
    public function index()
    {
        if (Auth::user()->role == 1) {
            return view('kompetensikategori.index')->with('page', 'kompetensikategori');
        } else {
            $message_type = 'danger';
            $message = 'Tidak memiliki hak akses untuk melihat data.';
            return redirect()->route('dashboard')->with($message_type, $message);
        }
    }

    public function getData()
    {
        $data = KompetensiKategori::all();
        return $this->datatable($data);
    }

    public function datatable($data)
    {
        return DataTables::of($data)
            ->addColumn('action', function ($data) {
                $action = '<div class="btn-group">';
                $action .= '<a class="btn btn-sm btn-warning btn-simple shadow" href="' . route("kompetensikategori.edit", $data->id) . '" title="Edit"><i class="fa fa-edit"></i></a>';
                $action .= '<a href="' . route("kompetensikategori.delete", $data->id) . '" class="btn btn-sm btn-danger btn-simple kompetensikategoriDelete shadow" title="Hapus" data-id="' . $data->id . '"><i class="fa fa-trash"></i></a>';
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
            return view('kompetensikategori.create')->with('page', 'kompetensikategori');
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
                'nama' => 'required|string'
            ]);

            $model = new KompetensiKategori;
            $model->nama = $request->nama;
            $model->save();

            DB::commit();
            $message_type = 'success';
            $message = 'Kategori kompetensi berhasil dibuat.';
            return redirect()->route('kompetensikategori.index')->with($message_type, $message);
        } catch (\Throwable $th) {
            DB::rollback();
            $message_type = 'danger';
            $message = 'Kategori kompetensi gagal dibuat.';
            return redirect()->route('kompetensikategori.create')->with($message_type, $message);
        }
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
        if (Auth::user()->role == 1) {
            $kategori = KompetensiKategori::find($id);
            return view('kompetensikategori.edit')->with('page', 'kompetensikategori')->with('kategori', $kategori);
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
                'nama' => 'required|string'
            ]);

            $model = KompetensiKategori::find($id);
            $model->nama = $request->nama;
            $model->save();

            DB::commit();
            $message_type = 'success';
            $message = 'Kategori kompetensi berhasil diubah.';
            return redirect()->route('kompetensikategori.index')->with($message_type, $message);
        } catch (\Throwable $th) {
            DB::rollback();
            $message_type = 'danger';
            $message = 'Kategori kompetensi gagal diubah.';
            return redirect()->route('kompetensikategori.edit', $id)->with($message_type, $message);
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $kompetensiKategori = KompetensiKategori::find($id);

            if (Auth::user()->role == 1) {
                $kompetensiKategori->delete();
            } else {
                throw new \Exception("Tidak memiliki hak akses untuk menghapus data.");
            }

            DB::commit();
            return response()->json([
                'message' => 'Kategori Kompetensi berhasil dihapus.',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Kategori Kompetensi gagal dihapus.',
                'type' => 'danger',
            ]);
        }
    }
}
