<?php

namespace App\Http\Controllers;

use App\User;
use App\Karyawan;
use App\ReadinessBagian;
use App\ReadinessMatrix;
use App\ReadinessKategori;
use App\ReadinessKompetensi;
use App\ReadinessMatrixHeader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ReadinessMatrixController extends Controller
{
    public function index()
    {
        return view('readinessmatrix.index')->with('page', 'readinessmatrix');
    }

    public function getData()
    {
        $matrix = ReadinessMatrixHeader::select(
            'readiness_matrix_header.id AS id',
            'readiness_matrix_header.date AS date',
            'atasan.id AS atasan_id',
            'atasan.name AS atasan_name',
            'rb.id AS bagian_id',
            'rb.nama AS bagian_nama',
            DB::raw("CAST(SUM(rm.staff_valid) AS int) AS staff_checked"),
            DB::raw("COALESCE(CAST(SUM(rm.atasan_valid) AS int), 0) AS atasan_checked"),
            DB::raw("COUNT(*) AS total"),
        )
            ->join('readiness_matrix AS rm', 'rm.readiness_matrix_header', 'readiness_matrix_header.id')
            ->join('readiness_bagian AS rb', 'rb.id', 'readiness_matrix_header.bagian')
            ->join('users AS staff', 'staff.id', 'readiness_matrix_header.staff')
            ->join('users AS atasan', 'atasan.id', 'readiness_matrix_header.atasan')
            ->where('staff', Auth::user()->id)
            ->groupBy('readiness_matrix_header.id')
            ->get();

        return $this->datatable($matrix);
    }

    public function datatable($data)
    {
        return DataTables::of($data)
            ->addColumn('action', function ($data) {
                $action = '<div class="btn-group">';
                $action .= '<a class="btn btn-sm btn-info btn-simple shadow" href="' . route("readinessmatrix.show", $data->id) . '" title="Info"><i class="fa fa-search"></i></a>';
                $action .= '<a class="btn btn-sm btn-warning btn-simple shadow" href="' . route("readinessmatrix.edit", $data->id) . '" title="Edit"><i class="fa fa-edit"></i></a>';
                $action .= '</div>';

                return $action;
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
    }

    public function create()
    {
        $kategori = ReadinessKategori::with(['jenis.bagian.kompetensi' => function ($kompetensi) {
            $kompetensi->select(
                'readiness_kompetensi.id',
                'readiness_kompetensi.nomor',
                'readiness_kompetensi.kompetensi',
                'readiness_kompetensi.readiness_bagian',
                'readiness_kompetensi.tipe',
            )
                ->orderBy('readiness_kompetensi.nomor', 'ASC');
        }])
            ->get();

        $karyawan = Karyawan::where('NIP', Auth::user()->username)->first();

        $atasan = Karyawan::select('NIP', 'NAMA', 'Jabatan')->where('region', $karyawan->region)
            ->whereNotIn('Status', ['Resign'])
            ->where(function ($q) use ($karyawan) {
                $q->where('Jabatan', 'like', '%leader%')
                    ->orWhere('Jabatan', 'like', '%manager%')
                    ->orWhere('Jabatan', 'like', '%manajer%')
                    ->orWhere('Jabatan', 'like', '%supervisor%')
                    ->whereNot('NIP', $karyawan->NIP);
            })
            ->get();

        return view('readinessmatrix.create')
            ->with('page', 'readinessmatrix')
            ->with('kategori', $kategori)
            ->with('atasan', $atasan);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $kompetensi = ReadinessKompetensi::where('readiness_bagian', $request->readiness_bagian)
                ->pluck('id');

            $matrixHeader = new ReadinessMatrixHeader;
            $matrixHeader->date = date('Y-m-d H:i:s');
            $matrixHeader->atasan = User::where('username', $request->atasan)->first()->id;
            $matrixHeader->staff = Auth::user()->id;
            $matrixHeader->bagian = $request->readiness_bagian;
            $matrixHeader->save();

            foreach ($kompetensi as $k) {
                $matrix = new ReadinessMatrix;
                $matrix->readiness_matrix_header = $matrixHeader->id;
                $matrix->readiness_kompetensi = $k;
                $matrix->staff_valid = in_array($k, $request->kompetensi) ? 1 : 0;
                $matrix->staff_valid_date = in_array($k, $request->kompetensi) ? date('Y-m-d H:i:s') : null;
                $matrix->atasan_valid = null;
                $matrix->atasan_valid_date = null;
                $matrix->save();
            }

            DB::commit();
            $message_type = 'success';
            $message = 'Data readiness berhasil ditambah.';
            return redirect()->route('readinessmatrix.index')->with($message_type, $message);
        } catch (\Throwable $th) {
            DB::rollback();
            dd($th);
            $message_type = 'danger';
            $message = 'Data readiness gagal ditambah.';
            return redirect()->route('readinessmatrix.create')->withInput()->with($message_type, $message);
        }
    }

    public function show($id)
    {
        $matrixHeader = ReadinessMatrixHeader::with([
            'matrix' => function ($matrix) {
                $matrix->select(
                    'readiness_matrix.*',
                    'readiness_kompetensi.tipe',
                    'readiness_kompetensi.kompetensi'
                )
                    ->join('readiness_kompetensi', 'readiness_kompetensi.id', 'readiness_matrix.readiness_kompetensi')
                    ->orderBy('readiness_kompetensi.nomor', 'ASC');
            },
            'dataBagian',
            'dataAtasan'
        ])
            ->where('id', $id)
            ->first();

        return view('readinessmatrix.show')
            ->with('page', 'readinessmatrix')
            ->with('matrixHeader', $matrixHeader);
    }

    public function edit($id)
    {

        $matrixHeader = ReadinessMatrixHeader::with([
            'matrix' => function ($matrix) {
                $matrix->select(
                    'readiness_matrix.*',
                    'readiness_kompetensi.tipe',
                    'readiness_kompetensi.kompetensi'
                )
                    ->join('readiness_kompetensi', 'readiness_kompetensi.id', 'readiness_matrix.readiness_kompetensi')
                    ->orderBy('readiness_kompetensi.nomor', 'ASC');
            },
            'dataBagian',
            'dataAtasan'
        ])
            ->where('id', $id)
            ->first();

        return view('readinessmatrix.edit')
            ->with('page', 'readinessmatrix')
            ->with('matrixHeader', $matrixHeader);
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            ReadinessMatrix::where('readiness_matrix_header', $id)
                ->whereNull('atasan_valid')
                ->update(['staff_valid' => 0, 'staff_valid_date' => null]);

            foreach ($request->kompetensi ?? [] as $kompetensi) {
                $matrix = ReadinessMatrix::find($kompetensi);
                $matrix->staff_valid = 1;
                $matrix->staff_valid_date = date('Y-m-d H:i:s');
                $matrix->save();
            }

            DB::commit();
            $message_type = 'success';
            $message = 'Data readiness berhasil diubah.';
            return redirect()->route('readinessmatrix.index')->with($message_type, $message);
        } catch (\Throwable $th) {
            DB::rollback();
            $message_type = 'danger';
            $message = 'Data readiness gagal diubah.';
            return redirect()->route('readinessmatrix.edit', $id)->withInput()->with($message_type, $message);
        }
    }

    public function delete($id)
    {
        //
    }
};
