<?php

namespace App\Http\Controllers;

use App\User;
use App\Karyawan;
use App\ReadinessBagian;
use App\ReadinessMatrix;
use App\ReadinessKategori;
use App\ReadinessKompetensi;
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
        $bagian = ReadinessBagian::with(['kompetensi.matrixes' => function ($matrix) {
            $matrix->where('staff', Auth::user()->id);
        }])
            ->whereHas('kompetensi.matrixes', function ($matrix) {
                $matrix->where('staff', Auth::user()->id);
            })
            ->get();
        return $this->datatable($bagian);
    }

    public function datatable($data)
    {
        return DataTables::of($data)
            ->addColumn('action', function ($data) {
                $action = '<div class="btn-group">';
                $action .= '<a class="btn btn-sm btn-info btn-simple shadow" href="' . route("readinessmatrix.show", $data->id) . '" title="Info"><i class="fa fa-search"></i></a>';
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
                ->with(['matrix' => function ($q) {
                    $q->where('readiness_matrix.staff', Auth::user()->id);
                }]);
        }])
            ->get();

        $karyawan = Karyawan::where('NIP', Auth::user()->username)->first();

        $atasan = Karyawan::select('NIP', 'NAMA', 'Jabatan')->where('region', $karyawan->region)
            ->whereNotIn('Status', ['Resign'])
            ->where(function ($q) {
                $q->where('Jabatan', 'like', '%leader%')
                    ->orWhere('NIP', '221219002')
                    ->orWhere('Jabatan', 'like', '%manager%')
                    ->orWhere('Jabatan', 'like', '%manajer%')
                    ->orWhere('Jabatan', 'like', '%supervisor%');
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

            $komp = ReadinessKompetensi::where('readiness_bagian', $request->readiness_bagian)
                ->pluck('id');

            foreach ($komp as $kompetensi) {
                $model = ReadinessMatrix::where('readiness_kompetensi', $kompetensi)
                    ->whereHas('kompetensi', function ($q) use ($request) {
                        $q->where('readiness_bagian', $request->readiness_bagian);
                    })
                    ->where('staff', Auth::user()->id)
                    ->first();

                if (!$model) {
                    $model = new ReadinessMatrix;
                    $model->readiness_kompetensi = $kompetensi;
                    $model->staff = Auth::user()->id;
                }

                $model->staff_valid = $model->staff_valid ? $model->staff_valid : (in_array($kompetensi, $request->kompetensi) ? 1 : 0);
                $model->staff_valid_date = date('Y-m-d H:i:s');
                $model->atasan = User::where('username', $request->atasan)->first()->id;
                $model->save();
            }
            DB::commit();
            $message_type = 'success';
            $message = 'Data readiness berhasil ditambah.';
            return redirect()->route('readinessmatrix.index')->with($message_type, $message);
        } catch (\Throwable $th) {
            DB::rollback();
            $message_type = 'danger';
            $message = 'Data readiness gagal ditambah.';
            return redirect()->route('readinessmatrix.create')->withInput()->with($message_type, $message);
        }
    }

    public function show($id)
    {
        $bagian = ReadinessBagian::with(['kompetensi.matrix' => function ($matrix) {
            $matrix->where('readiness_matrix.staff', Auth::user()->id);
        }])
            ->whereHas('kompetensi.matrix', function ($q) {
                $q->where('staff', Auth::user()->id);
            })
            ->where('readiness_bagian.id', $id)
            ->get();

        return view('readinessmatrix.show')
            ->with('page', 'readinessmatrix')
            ->with('bagian', $bagian);
    }

    public function edit($id)
    {
        $kategori = ReadinessKategori::with([
            'jenis' => function ($jenis) {
                $jenis->with([
                    'bagian' => function ($bagian) {
                        $bagian->with('kompetensi')
                            ->whereHas('kompetensi');
                    }
                ])
                    ->whereHas('bagian');
            }
        ])
            ->find($id);

        return view('readinessmatrix.edit')
            ->with('page', 'readinessmatrix')
            ->with('kategori', $kategori);
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function delete($id)
    {
        //
    }
};
