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
                    ->orWhere('Jabatan', 'like', '%supervisor%');
            })
            ->where('NIP', '!=', $karyawan->NIP)
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

            // 10 hari setelah pengisian terakhir
            // $allowed = ReadinessMatrixHeader::where('staff', auth()->user()->id)
            //     ->where('bagian', $request->readiness_bagian)
            //     ->whereDate('date', '>', date('Y-m-d', strtotime('-10 days')))
            //     ->get();

            // cegah staff mengisi di antara tanggal 10 sampai 25
            if (date('d') > 10 && date('d') < 25) {
                return redirect()->route('readinessmatrix.create')
                    ->withInput()
                    ->with('danger', 'Data readiness matrix hanya bisa diisi dari tanggal 25 sampai tanggal 10.');
            }

            // ambil data tanggal 25 sampai 10
            $allowed = ReadinessMatrixHeader::where('staff', auth()->user()->id)
                ->where('bagian', $request->readiness_bagian)
                ->when(date('d') >= 25, function ($q) {
                    $q->whereDate('date', '>=', date('Y-m-25'))
                        ->whereDate('date', '<=', date('Y-m-10', strtotime('+1 month')));
                })
                ->when(date('d') <= 10, function ($q) {
                    $q->whereDate('date', '>=', date('Y-m-25', strtotime('-1 month')))
                        ->whereDate('date', '<=', date('Y-m-10'));
                })
                ->get();

            // cek apakah sudah pernah mengisi?
            if (count($allowed) > 0) {
                return redirect()->route('readinessmatrix.create')
                    ->withInput()
                    ->with('danger', 'Data readiness matrix dengan bagian yang sama telah ada sebelumnya.');
            }

            // create readiness
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
            'dataAtasan' => function ($atasan) {
                $atasan->select('users.*', 'atasan_absen.NAMA AS nama_absen')
                    ->join('u1127775_absensi.Absen AS atasan_absen', 'atasan_absen.NIP', 'users.username');
            }
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
            'dataAtasan' => function ($atasan) {
                $atasan->select('users.*', 'atasan_absen.NAMA AS nama_absen')
                    ->join('u1127775_absensi.Absen AS atasan_absen', 'atasan_absen.NIP', 'users.username');
            }
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

            // cegah staff mengedit di antara tanggal 10 sampai 25
            if (date('d') > 10 && date('d') < 25) {
                return redirect()->route('readinessmatrix.create')
                    ->withInput()
                    ->with('danger', 'Data readiness matrix hanya bisa diubah dari tanggal 25 sampai tanggal 10.');
            }

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
};
