<?php

namespace App\Http\Controllers;

use App\ReadinessBagian;
use App\ReadinessMatrix;
use App\ReadinessValidator;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Contracts\DataTable;

class ReadinessValidatorController extends Controller
{
    public function index()
    {
        if (Auth::user()->role == 1) {
            return view('readinessvalidator.index')
                ->with('page', 'readinessvalidator');
        } else {
            $message_type = 'danger';
            $message = 'Tidak memiliki hak akses untuk melihat data.';
            return redirect()->route('dashboard')->with($message_type, $message);
        }
    }

    public function getData()
    {
        $staff = User::select(
            'users.id AS user_id',
            'users.name AS user_name',
            'readiness_bagian.id AS bagian_id',
            'readiness_bagian.nama AS bagian_nama',
            DB::raw('(SELECT COUNT(rm.atasan_valid) FROM readiness_matrix rm INNER JOIN readiness_kompetensi rk ON rk.id = rm.readiness_kompetensi WHERE rm.staff = users.id AND rk.readiness_bagian = readiness_bagian.id AND rm.atasan_valid = 1) AS checked_staff'),
            DB::raw('(SELECT COUNT(rm.atasan_valid) FROM readiness_matrix rm INNER JOIN readiness_kompetensi rk ON rk.id = rm.readiness_kompetensi WHERE rm.staff = users.id AND rk.readiness_bagian = readiness_bagian.id AND rm.atasan_valid_date IS NOT NULL) AS checked_atasan'),
            DB::raw('COUNT(readiness_matrix.readiness_kompetensi) AS total')
        )
            ->join('readiness_matrix', 'readiness_matrix.staff', 'users.id')
            ->join('readiness_kompetensi', 'readiness_kompetensi.id', 'readiness_matrix.readiness_kompetensi')
            ->join('readiness_bagian', 'readiness_bagian.id', 'readiness_kompetensi.readiness_bagian')
            ->groupBy('users.name', 'readiness_kompetensi.readiness_bagian')
            ->get();

        return $this->datatable($staff);
    }

    public function datatable($data)
    {
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                $action = '<div class="btn-group">';
                $action .= '<a class="btn btn-sm btn-warning btn-simple shadow" href="' . route("readinessvalidator.show", [$data->user_id, 'bagian' => $data->bagian_id]) . '" title="Edit"><i class="fa fa-edit"></i></a>';
                $action .= '</div>';

                return $action;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            foreach ($request->validator as $validator) {
                $model = new ReadinessValidator;
                $model->date = date('Y-m-d H:i:s');
                $model->readiness_matrix = $validator;
                $model->validator = Auth::user()->id;
                $model->save();
            }

            DB::commit();
            $message_type = 'success';
            $message = 'Data readiness berhasil divalidasi.';
            return redirect()->route('readinessvalidator.index')->with($message_type, $message);
        } catch (\Throwable $th) {
            DB::rollback();
            $message_type = 'danger';
            $message = 'Data readiness gagal divalidasi.';
            return redirect()->route('readinessvalidator.show', [$request->staff, 'bagian' => $request->bagian])->withInput()->with($message_type, $message);
        }
    }

    public function show(Request $request, $id)
    {
        $bagian = ReadinessBagian::select('*')
            ->with(['kompetensi.matrix' => function ($q) use ($id) {
                $q->with('validator')
                    ->where('readiness_matrix.staff', $id);
            }])
            ->where('readiness_bagian.id', $request->bagian)
            ->first();

        $staff = User::find($id);
        $atasan = User::find($bagian->kompetensi->first()->matrix->atasan);

        return view('readinessvalidator.show')
            ->with('page', 'readinessvalidator')
            ->with('staff', $staff)
            ->with('atasan', $atasan)
            ->with('bagian', $bagian);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
        //
    }
}
