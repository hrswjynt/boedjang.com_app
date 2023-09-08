<?php

namespace App\Http\Controllers;

use App\User;
use App\ReadinessBagian;
use App\ReadinessMatrix;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ReadinessMatrixAtasanController extends Controller
{
    public function index()
    {
        return view('readinessmatrixatasan.index')->with('page', 'readinessmatrixatasan');
    }

    public function getData()
    {
        $staff = User::select('*')
            ->with(['matrix' => function ($q) {
                $q->where('atasan', Auth::user()->id);
            }])
            ->whereHas('matrix', function ($q) {
                $q->where('atasan', Auth::user()->id);
            })
            ->get();

        return $this->datatable($staff);
    }

    public function datatable($data)
    {
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                $action = '<div class="btn-group">';
                $action .= '<a class="btn btn-sm btn-warning btn-simple shadow" href="' . route("readinessmatrixatasan.edit", $data->id) . '" title="Edit"><i class="fa fa-edit"></i></a>';
                $action .= '</div>';

                return $action;
            })
            ->make(true);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $bagian = ReadinessBagian::with(['kompetensi.matrix' => function ($q) use ($id) {
            $q->where('staff', $id);
        }])
            ->whereHas('kompetensi.matrix', function ($q) use ($id) {
                $q->where('staff', $id);
            })
            ->get();

        return view('readinessmatrixatasan.edit')
            ->with('page', 'readinessmatrixatasan')
            ->with('bagian', $bagian)
            ->with('staff', $id);
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            foreach ($request->atasan as $atasan) {
                $matrix = ReadinessMatrix::find($atasan);
                $matrix->atasan_valid = 1;
                $matrix->atasan_valid_date = date('Y-m-d H:i:s');
                $matrix->save();
            }

            DB::commit();
            $message_type = 'success';
            $message = 'Readiness berhasil validasi.';
            return redirect()->route('readinessmatrixatasan.index')->with($message_type, $message);
        } catch (\Throwable $th) {
            DB::rollback();
            $message_type = 'danger';
            $message = 'Readiness gagal divalidasi.';
            return redirect()->route('readinessmatrixatasan.edit', $id)->withInput()->with($message_type, $message);
        }
    }

    public function destroy($id)
    {
        //
    }
}
