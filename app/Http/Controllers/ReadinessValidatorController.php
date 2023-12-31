<?php

namespace App\Http\Controllers;

use App\User;
use App\ReadinessBagian;
use App\ReadinessJenis;
use App\ReadinessMatrix;
use App\ReadinessValidator;
use Illuminate\Http\Request;
use App\ReadinessMatrixHeader;
use App\ReadinessStatus;
use Illuminate\Foundation\Console\Presets\React;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Contracts\DataTable;

class ReadinessValidatorController extends Controller
{
    public function index()
    {
        $readinessStatus = ReadinessStatus::first();

        if (Auth::user()->role == 1) {
            return view('readinessvalidator.index')
                ->with('page', 'readinessvalidator')
                ->with('readiness_status', $readinessStatus);
        } else {
            $message_type = 'danger';
            $message = 'Tidak memiliki hak akses untuk melihat data.';
            return redirect()->route('dashboard')->with($message_type, $message);
        }
    }

    public function setReadinessStatus()
    {
        DB::beginTransaction();
        try {
            $readinessStatus = ReadinessStatus::first();
            $readinessStatus->status = request()->status;
            $readinessStatus->save();
            if (!!request()->status) {
                $status = 'dibuka';
            } else {
                $status = 'ditutup';
            }
            DB::commit();
            return response()->json([
                'message' => 'Pengisian readiness matrix berhasil ' . $status,
                'type' => 'success',
                'status' => !!request()->status,
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => 'Pengisian readiness matrix gagal ' . $status,
                'type' => 'danger',
                'status' => +!request()->status,
            ]);
        }
    }

    public function getData(Request $request)
    {
        $matrix = ReadinessMatrixHeader::select(
            'readiness_matrix_header.id AS id',
            'readiness_matrix_header.date AS date',
            'staff.id AS staff_id',
            'staff_absen.NAMA AS staff_name',
            'atasan.id AS atasan_id',
            'atasan_absen.NAMA AS atasan_name',
            'rb.id AS bagian_id',
            'rb.nama AS bagian_nama',
            DB::raw("CAST(SUM(rm.staff_valid) AS int) AS staff_checked"),
            DB::raw("COALESCE(CAST(SUM(rm.atasan_valid) AS int), 0) AS atasan_checked"),
            DB::raw("COALESCE(COUNT(rv.id)) AS hc_valid"),
            DB::raw("COUNT(*) AS total"),
        )
            ->join('readiness_matrix AS rm', 'rm.readiness_matrix_header', 'readiness_matrix_header.id')
            ->leftJoin('readiness_validator AS rv', 'rv.readiness_matrix', 'rm.id')
            ->join('readiness_bagian AS rb', 'rb.id', 'readiness_matrix_header.bagian')
            ->join('users AS staff', 'staff.id', 'readiness_matrix_header.staff')
            ->join('u1127775_absensi.Absen AS staff_absen', 'staff_absen.NIP', 'staff.username')
            ->join('users AS atasan', 'atasan.id', 'readiness_matrix_header.atasan')
            ->join('u1127775_absensi.Absen AS atasan_absen', 'atasan_absen.NIP', 'atasan.username')
            ->whereRaw('DATE(readiness_matrix_header.date) BETWEEN DATE(?) AND DATE(?)', [$request->sdate, $request->edate])
            ->groupBy('readiness_matrix_header.id')
            ->get();

        return $this->datatable($matrix);
    }

    public function datatable($data)
    {
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                $action = '<div class="btn-group">';
                $action .= '<a class="btn btn-sm btn-warning btn-simple shadow" href="' . route("readinessvalidator.show", $data->id) . '" title="Validasi"><i class="fa fa-list"></i></a>';
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
        //
    }

    public function show(Request $request, $id)
    {
        $matrixHeader = ReadinessMatrixHeader::with([
            'matrix' => function ($matrix) {
                $matrix->select(
                    'readiness_matrix.*',
                    'readiness_kompetensi.tipe',
                    'readiness_kompetensi.nomor',
                    'readiness_kompetensi.kompetensi',
                    'readiness_validator.date AS validator_date',
                    'readiness_validator.readiness_matrix',
                    'readiness_validator.validator',
                )
                    ->join('readiness_kompetensi', 'readiness_kompetensi.id', 'readiness_matrix.readiness_kompetensi')
                    ->leftJoin('readiness_validator', 'readiness_validator.readiness_matrix', 'readiness_matrix.id')
                    ->orderBy('readiness_kompetensi.nomor');
            },
            'dataBagian',
            'dataStaff' => function ($staff) {
                $staff->select('users.*', 'staff_absen.NAMA AS staff_name')
                    ->join('u1127775_absensi.Absen AS staff_absen', 'staff_absen.NIP', 'users.username');
            },
            'dataAtasan' => function ($atasan) {
                $atasan->select('users.*', 'atasan_absen.NAMA AS atasan_name')
                    ->join('u1127775_absensi.Absen AS atasan_absen', 'atasan_absen.NIP', 'users.username');
            }
        ])
            ->where('id', $id)
            ->first();

        return view('readinessvalidator.show')
            ->with('page', 'readinessvalidator')
            ->with('matrixHeader', $matrixHeader);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {

        try {
            DB::beginTransaction();

            ReadinessValidator::whereHas('matrix', function ($q) use ($id) {
                $q->where('readiness_matrix_header', $id);
            })->delete();

            $matrixHeader = ReadinessMatrixHeader::find($id);
            $matrixHeader->catatan = $request->catatan;
            $matrixHeader->save();

            foreach ($request->hc ?? [] as $matrix_id) {
                $matrixValidator = new ReadinessValidator;
                $matrixValidator->date = date('Y-m-d H:i:s');
                $matrixValidator->readiness_matrix = $matrix_id;
                $matrixValidator->validator = Auth::user()->id;
                $matrixValidator->save();
            }

            DB::commit();
            $message_type = 'success';
            $message = 'Data readiness berhasil divalidasi.';
            return redirect()->route('readinessvalidator.index')->with($message_type, $message);
        } catch (\Throwable $th) {
            DB::rollback();
            $message_type = 'danger';
            $message = 'Data readiness gagal divalidasi.';
            return redirect()->route('readinessvalidator.show', $id)->withInput()->with($message_type, $message);
        }
    }

    public function destroy($id)
    {
        //
    }
}
