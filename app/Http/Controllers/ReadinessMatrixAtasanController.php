<?php

namespace App\Http\Controllers;

use App\User;
use App\ReadinessJenis;
use App\ReadinessBagian;
use App\ReadinessMatrix;
use Illuminate\Http\Request;
use App\ReadinessMatrixHeader;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class ReadinessMatrixAtasanController extends Controller
{
    public function index()
    {
        return view('readinessmatrixatasan.index')->with('page', 'readinessmatrixatasan');
    }

    public function getData()
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
            DB::raw("COUNT(*) AS total"),
        )
            ->join('readiness_matrix AS rm', 'rm.readiness_matrix_header', 'readiness_matrix_header.id')
            ->join('readiness_bagian AS rb', 'rb.id', 'readiness_matrix_header.bagian')
            ->join('users AS staff', 'staff.id', 'readiness_matrix_header.staff')
            ->join('u1127775_absensi.Absen AS staff_absen', 'staff_absen.NIP', 'staff.username')
            ->join('users AS atasan', 'atasan.id', 'readiness_matrix_header.atasan')
            ->join('u1127775_absensi.Absen AS atasan_absen', 'atasan_absen.NIP', 'atasan.username')
            ->where('atasan', Auth::user()->id)
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
                $action .= '<a class="btn btn-sm btn-warning btn-simple shadow" href="' . route("readinessmatrixatasan.show", $data->id) . '" title="Validasi"><i class="fa fa-list"></i></a>';
                $action .= '</div>';

                return $action;
            })
            ->make(true);
    }

    public function show($id)
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
                $staff->select('users.*', 'staff_absen.NAMA AS nama_absen')
                    ->join('u1127775_absensi.Absen AS staff_absen', 'staff_absen.NIP', 'users.username');
            },
            'dataAtasan' => function ($atasan) {
                $atasan->select('users.*', 'atasan_absen.NAMA AS nama_absen')
                    ->join('u1127775_absensi.Absen AS atasan_absen', 'atasan_absen.NIP', 'users.username');
            }
        ])
            ->where('id', $id)
            ->first();

        return view('readinessmatrixatasan.show')
            ->with('page', 'readinessmatrixatasan')
            ->with('matrixHeader', $matrixHeader);
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make(
                $request->all(),
                [
                    'catatan' => 'required'
                ],
                [
                    'catatan.required' => 'Catatan wajib diisi'
                ]
            );

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }

            ReadinessMatrix::where('readiness_matrix_header', $id)
                ->whereDoesntHave('validator')
                ->update([
                    'atasan_valid' => null,
                    'atasan_valid_date' => null
                ]);

            ReadinessMatrixHeader::find($id)->update(['catatan' => $request->catatan]);

            foreach ($request->valid ?? [] as $matrix_id) {
                $matrix = ReadinessMatrix::find($matrix_id);
                $matrix->atasan_valid = 1;
                $matrix->atasan_valid_date = date('Y-m-d H:i:s');
                $matrix->save();
            }

            DB::commit();
            $message_type = 'success';
            $message = 'Data readiness berhasil divalidasi.';
            return redirect()->route('readinessmatrixatasan.index')->with($message_type, $message);
        } catch (\Throwable $th) {
            DB::rollback();
            $message_type = 'danger';
            $message = 'Data readiness gagal divalidasi.';
            return redirect()->route('readinessmatrixatasan.show', $id)->withInput()->with($message_type, $message);
        }
    }

    // public function validate(Request $request, array $rules, array $messages = [], array $customAttributes = [])
    // {
    //     return $this->getValidationFactory()
    //         ->make($request->all(), $rules, $messages, $customAttributes)
    //         ->validate();
    // }
}
