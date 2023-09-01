<?php

namespace App\Http\Controllers;

use App\ReadinessKategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ReadinessKategoriController extends Controller
{
    public function index()
    {
        if (Auth::user()->role == 1) {
            return view('readinesskategori.index')->with('page', 'readinesskategori');
        } else {
            $message_type = 'danger';
            $message = 'Tidak memiliki hak akses untuk melihat data.';
            return redirect()->route('dashboard')->with($message_type, $message);
        }
    }

    public function getData()
    {
        $data = ReadinessKategori::all();
        return $this->datatable($data);
    }

    public function datatable($data)
    {
        return DataTables::of($data)
            ->addColumn('action', function ($data) {
                $action = '<div class="btn-group">';
                $action .= '<a class="btn btn-sm btn-warning btn-simple shadow" href="' . route("readinesskategori.edit", $data->id) . '" title="Edit"><i class="fa fa-edit"></i></a>';
                $action .= '<a href="' . route("readinesskategori.delete", $data->id) . '" class="btn btn-sm btn-danger btn-simple readinesskategoriDelete shadow" title="Hapus" data-id="' . $data->id . '"><i class="fa fa-trash"></i></a>';
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
            return view('readinesskategori.create')->with('page', 'readinesskategori');
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

            $model = new ReadinessKategori;
            $model->nama = $request->nama;
            $model->save();

            DB::commit();
            $message_type = 'success';
            $message = 'Kategori readiness berhasil dibuat.';
            return redirect()->route('readinesskategori.index')->with($message_type, $message);
        } catch (\Throwable $th) {
            DB::rollback();
            $message_type = 'danger';
            $message = 'Kategori readiness gagal dibuat.';
            return redirect()->route('readinesskategori.create')->with($message_type, $message);
        }
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
        if (Auth::user()->role == 1) {
            $kategori = ReadinessKategori::find($id);
            return view('readinesskategori.edit')->with('page', 'readinesskategori')->with('kategori', $kategori);
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

            $model = ReadinessKategori::find($id);
            $model->nama = $request->nama;
            $model->save();

            DB::commit();
            $message_type = 'success';
            $message = 'Kategori readiness berhasil diubah.';
            return redirect()->route('readinesskategori.index')->with($message_type, $message);
        } catch (\Throwable $th) {
            DB::rollback();
            $message_type = 'danger';
            $message = 'Kategori readiness gagal diubah.';
            return redirect()->route('readinesskategori.edit', $id)->with($message_type, $message);
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $readinessKategori = ReadinessKategori::find($id);

            if (Auth::user()->role == 1) {
                $readinessKategori->delete();
            } else {
                throw new \Exception("Tidak memiliki hak akses untuk menghapus data.");
            }

            DB::commit();
            return response()->json([
                'message' => 'Kategori Readiness berhasil dihapus.',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Kategori Readiness gagal dihapus.',
                'type' => 'danger',
            ]);
        }
    }
}
