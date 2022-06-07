<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Karyawan;
use App\Bpm;
use App\BpmDivision;
use App\BpmRelationDivision;
use DataTables;
use Auth;
use DB;
use File;
use Image;

class BpmController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('bpm.index')->with('page', 'bpm');
    }

    public function create()
    {
        $division = BpmDivision::all();
        return view('bpm.create')->with('page', 'bpm')->with('division', $division);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validatedData = $this->validate($request, [
            'title' => 'required',
            // 'gambar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:20480'
        ]);

        $image_name = null;
        if ($request->gambar !== null) {
            $image = $request->file('gambar');
            $image_name = time() . 'bpm.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images/bpm');
            $resize_image = Image::make($image->getRealPath());
            // $resize_image->resize(null, null, function($constraint){
            //     $constraint->aspectRatio();
            // })->save($destinationPath . '/' . $image_name);
            $resize_image->save($destinationPath . '/' . $image_name);
        }

        $bpm = new Bpm;
        $slug = str_replace("/", "", $request->title);
        $bpm->slug = strtolower(str_replace(" ", "-", $slug));
        $bpm->title = $request->title;
        $bpm->content = $request->content;
        $bpm->gambar = $image_name;
        $bpm->publish = 0;
        $bpm->division_display = '';
        $bpm->save();

        foreach ($request->division as $division) {
            $relation = new BpmRelationDivision;
            $relation->id_bpm = $bpm->id;
            $relation->id_division = $division;
            $relation->save();

            $bpm->division_display = $bpm->division_display . ';' . BpmDivision::find($division)->name;
            $bpm->save();
        }
        return redirect()->route('bpm.index')->with('success', 'Data BPM ' . $request->title . ' berhasil disimpan.');
    }

    public function getData()
    {
        $data = BPM::select('bpm.id', 'bpm.title', 'bpm.slug', 'bpm.publish')->get();
        return $this->datatable($data);
    }

    public function datatable($data)
    {
        return Datatables::of($data)
            ->addColumn('action', function ($data) {
                $action = '<div class="btn-group">';
                $action .= '<a class="btn btn-sm btn-info btn-simple shadow" href="' . route("bpm.show", $data->id) . '" title="Info"><i class="fa fa-search"></i></a>';
                $action .= '<a class="btn btn-sm btn-warning btn-simple shadow" href="' . route("bpm.edit", $data->id) . '" title="Edit"><i class="fa fa-edit"></i></a>';
                $action .= '<a href="' . route("bpm.delete", $data->id) . '" class="btn btn-sm btn-danger btn-simple shadow bpmDelete" title="Delete" data-id="' . $data->id . '"><i class="fa fa-trash"></i></a>';
                if ($action == '') {
                    $action .= 'None';
                }
                $action .= '</div>';
                return $action;
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
    }

    public function show(Bpm $bpm)
    {
        $division = BpmDivision::all();
        $bpmdivision = BpmRelationDivision::where('id_bpm', $bpm->id)->get();
        foreach ($bpmdivision as $bd) {
            $arrayd[] = $bd->id_division;
        }
        return view('bpm.show')->with('bpm', $bpm)
            ->with('page', 'bpm')
            ->with('division', $division)
            ->with('bpmdivision', $arrayd);
    }

    public function edit(Bpm $bpm)
    {
        $arrayd = [];
        $division = BpmDivision::all();
        $bpmdivision = BpmRelationDivision::where('id_bpm', $bpm->id)->get();
        foreach ($bpmdivision as $bd) {
            $arrayd[] = $bd->id_division;
        }
        // dd($arrayd);
        return view('bpm.edit')->with('page', 'bpm')
            ->with('bpm', $bpm)
            ->with('division', $division)
            ->with('bpmdivision', $arrayd);
    }

    public function update(Request $request, Bpm $bpm)
    {
        $validatedData = $this->validate($request, [
            'title'         => 'required',
        ]);
        $image_name = null;
        if ($request->gambar !== null) {
            $image = $request->file('gambar');
            $image_name = time() . 'bpm.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images/bpm');
            $resize_image = Image::make($image->getRealPath());
            // $resize_image->resize(null, null, function($constraint){
            //     $constraint->aspectRatio();
            // })->save($destinationPath . '/' . $image_name);
            $resize_image->save($destinationPath . '/' . $image_name);
        }
        $slug = str_replace("/", "", $request->title);
        $bpm->slug = strtolower(str_replace(" ", "-", $slug));
        $bpm->title = $request->title;
        $bpm->content = $request->content;
        if ($image_name != null) {
            if ($bpm->gambar !== null) {
                $image_path = public_path('/images/bpm/' . $bpm->gambar);
                if (File::exists($image_path)) {
                    File::delete($image_path);
                }
            }
            $bpm->gambar = $image_name;
        }
        $bpm->division_display = '';
        $bpm->publish = $request->publish;
        $bpm->save();

        BpmRelationDivision::where('id_bpm', $bpm->id)->delete();
        foreach ($request->division as $division) {
            $relation = new BpmRelationDivision;
            $relation->id_bpm = $bpm->id;
            $relation->id_division = $division;
            $relation->save();
            $bpm->division_display = $bpm->division_display . ';' . BpmDivision::find($division)->name;
            $bpm->save();
        }

        return redirect()->route('bpm.index')->with('success', 'Data BPM ' . $request->title . ' berhasil diupdate.');
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $bpm = Bpm::find($id);
            $title = $bpm->title;
            $gambar = $bpm->gambar;
            $bpm->delete();
            if ($gambar !== null) {
                $image_path = public_path('/images/bpm/' . $gambar);
                if (File::exists($image_path)) {
                    File::delete($image_path);
                }
            }
            $division = BpmRelationDivision::where("id_bpm", $id)->delete();
            DB::commit();
            return response()->json([
                'message' => 'BPM "' . $title . '" berhasil dihapus!',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'BPM "' . $title . '" gagal dihapus!',
                'type' => 'danger',
            ]);
        }
    }

    public function getList()
    {
        $karyawan = Karyawan::where('NIP', Auth::user()->username)->first();
        if (Auth::user()->role == 5 && $karyawan->Cabang !== "HeadOffice") {
            $message_type = 'danger';
            $message = 'Fitur BPM belum dapat diakses.';
            return redirect()->route('dashboard')->with($message_type, $message);
        }
        $search = null;
        $division_select = null;
        $bpm = Bpm::where('publish', '1')->orderBy('updated_at', 'DESC')->paginate(6);
        $division = BpmDivision::all();
        return view('bpm.home')->with('page', 'bpm_list')->with('bpm', $bpm)->with('search', $search)->with('division', $division)->with('division_select', $division_select);
    }

    public function getBpm($slug)
    {
        $karyawan = Karyawan::where('NIP', Auth::user()->username)->first();
        if (Auth::user()->role == 5 && $karyawan->Cabang !== "HeadOffice") {
            $message_type = 'danger';
            $message = 'Fitur BPM belum dapat diakses.';
            return redirect()->route('dashboard')->with($message_type, $message);
        }
        $bpm = Bpm::where('slug', $slug)->first();
        $division = BpmRelationDivision::join('bpm_division', 'bpm_division.id', 'bpm_relation_division.id_division')->select('bpm_division.*')->where('id_bpm', $bpm->id)->get();
        if ($bpm == null) {
            return redirect()->route('bpm_list.index')->with('danger', 'BPM yang dicari tidak ditemukan.');
        }
        return view('bpm.post')->with('page', 'bpm_list')->with('bpm', $bpm)->with('division', $division);
    }

    public function getSearch(Request $request)
    {
        // dd($request->all());
        $karyawan = Karyawan::where('NIP', Auth::user()->username)->first();
        if (Auth::user()->role == 5 && $karyawan->Cabang !== "HeadOffice") {
            $message_type = 'warning';
            $message = 'Fitur BPM belum dapat diakses.';
            return redirect()->route('dashboard')->with($message_type, $message);
        }
        $search = $request->search;
        $division_select = null;
        $query_division = $request->division;

        if ($request->division == 'all') {
            $query_division = '%%';
        } else {
            $division_select = BpmDivision::find($request->division);
        }


        $bpm = Bpm::leftJoin('bpm_relation_division', 'bpm_relation_division.id_bpm', 'bpm.id')
            ->join('bpm_division', 'bpm_division.id', 'bpm_relation_division.id_division')
            ->where('bpm.title', 'like', '%' . $request->search . '%')
            ->orderBy('bpm.updated_at', 'DESC')
            ->where('bpm_relation_division.id_division', 'like', $query_division)
            ->where('bpm.publish', '1')
            ->groupBy('bpm.id')
            ->select('bpm.*')->paginate(6);

        $division = BpmDivision::all();
        return view('bpm.home')->with('page', 'division_list')
            ->with('bpm', $bpm)
            ->with('division', $division)
            ->with('division_select', $division_select)
            ->with('search', $search);
    }
}
