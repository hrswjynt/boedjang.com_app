<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jabatan;
use App\Sop;
use DataTables;
use Auth;
use DB;

class JabatanController extends Controller
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
        return view('jabatan.index')->with('page','jabatan');
    }

    public function create(){
        return view('jabatan.create')->with('page','jabatan');
    }

    public function store(Request $request)
    {   
        // dd($request->all());
        $validatedData = $this->validate($request, [
            'name'         => 'required',
        ]);
        $jabatan = new Jabatan;
        $jabatan->name = $request->name;
        $jabatan->description = $request->description;
        $jabatan->save();
        return redirect()->route('jabatan.index')->with('success','Data Jabatan SOP '.$request->name.' berhasil disimpan.');
    }

    public function getData(){
        $data = Jabatan::all();
        return $this->datatable($data);
    }

    public function datatable($data)
    {
        return Datatables::of($data)
            ->addColumn('action', function ($data) {
                $action = '<div class="btn-group">';
                $action.= '<a class="btn btn-sm btn-info btn-simple shadow" href="'.route("jabatan.show",$data->id).'" title="Info"><i class="fa fa-search"></i> Info </a>';
                $action .='<a class="btn btn-sm btn-warning btn-simple shadow" href="'.route("jabatan.edit",$data->id).'" title="Edit"><i class="fa fa-edit"></i> Edit</a>';
                $action .='<a href="'.route("jabatan.delete",$data->id).'" class="btn btn-sm btn-danger btn-simple shadow jabatanDelete" title="Delete" data-id="'.$data->id.'"><i class="fa fa-trash"></i> Hapus</a>';
                if($action == ''){
                    $action .= 'None';
                }
                $action .= '</div>';
                return $action;
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
    }
    
    public function show(Jabatan $jabatan)
    {   
        return view('jabatan.show')->with('jabatan', $jabatan)->with('page','jabatan');
    }
    
    public function edit(Jabatan $jabatan)
    {   
        return view('jabatan.edit')->with('page','jabatan')
                                        ->with('jabatan', $jabatan);
    }
    
    public function update(Request $request, Jabatan $jabatan)
    {
        $validatedData = $this->validate($request, [
            'name'         => 'required',
        ]);
        $jabatan->name = $request->name;
        $jabatan->description = $request->description;
        $jabatan->save();
        return redirect()->route('jabatan.index')->with('success','Data Jabatan SOP '.$request->name.' berhasil diupdate.');
   }

   public function delete($id){
        DB::beginTransaction();
        $jabatan = Jabatan::find($id);
        $name = $jabatan->name;
        if(Sop::where('jabatan',$id)->first() !== null){
            return response()->json([
                'message' => 'Jenis SOP "'.$name.'" gagal dihapus, jenis telah digunakan.',
                'jabatan'=> 'danger',
            ]);
        }
        try{
            $jabatan->delete();
            DB::commit();
            return response()->json([
                'message' => 'Jenis SOP "'.$name.'" berhasil dihapus!',
                'jabatan'=> 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Jenis SOP "'.$name.'" gagal dihapus!',
                'jabatan'=> 'danger',
            ]);
        }
    }
}
