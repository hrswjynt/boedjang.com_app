<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BpmDivision;
use App\BpmRelationDivision;
use App\Bpm;
use DataTables;
use Auth;
use DB;

class BpmDivisionController extends Controller
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
        return view('bpmdivision.index')->with('page','bpmdivision');
    }

    public function create(){
        return view('bpmdivision.create')->with('page','bpmdivision');
    }

    public function store(Request $request)
    {   
        // dd($request->all());
        $validatedData = $this->validate($request, [
            'name'         => 'required',
        ]);
        $bpmdivision = new BpmDivision;
        $bpmdivision->name = $request->name;
        $bpmdivision->description = $request->description;
        $bpmdivision->save();
        return redirect()->route('bpmdivision.index')->with('success','Data Divisi BPM '.$request->name.' berhasil disimpan.');
    }

    public function getData(){
        $data = BpmDivision::all();
        return $this->datatable($data);
    }

    public function datatable($data)
    {
        return Datatables::of($data)
            ->addColumn('action', function ($data) {
                $action = '<div class="btn-group">';
                $action.= '<a class="btn btn-sm btn-info btn-simple shadow" href="'.route("bpmdivision.show",$data->id).'" title="Info"><i class="fa fa-search"></i> Info </a>';
                $action .='<a class="btn btn-sm btn-warning btn-simple shadow" href="'.route("bpmdivision.edit",$data->id).'" title="Edit"><i class="fa fa-edit"></i> Edit</a>';
                $action .='<a href="'.route("bpmdivision.delete",$data->id).'" class="btn btn-sm btn-danger btn-simple shadow bpmdivisionDelete" title="Delete" data-id="'.$data->id.'"><i class="fa fa-trash"></i> Hapus</a>';
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
    
    public function show(BpmDivision $bpmdivision)
    {   
        return view('bpmdivision.show')->with('bpmdivision', $bpmdivision)->with('page','bpmdivision');
    }
    
    public function edit(BpmDivision $bpmdivision)
    {   
        return view('bpmdivision.edit')->with('page','bpmdivision')
                                        ->with('bpmdivision', $bpmdivision);
    }
    
    public function update(Request $request, BpmDivision $bpmdivision)
    {
        $validatedData = $this->validate($request, [
            'name'         => 'required',
        ]);
        $bpmdivision->name = $request->name;
        $bpmdivision->description = $request->description;
        $bpmdivision->save();
        return redirect()->route('bpmdivision.index')->with('success','Data Divisi BPM'.$request->name.' berhasil diupdate.');
   }

   public function delete($id){
        DB::beginTransaction();
        $division = BpmDivision::find($id);
        $name = $division->name;
        if(BpmRelationDivision::where('id_division',$id)->first() !== null){
            return response()->json([
                'message' => 'Divisi BPM "'.$name.'" gagal dihapus, jenis telah digunakan.',
                'type'=> 'danger',
            ]);
        }
        try{
            $division->delete();
            DB::commit();
            return response()->json([
                'message' => 'Divisi BPM "'.$name.'" berhasil dihapus!',
                'type'=> 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Divisi BPM "'.$name.'" gagal dihapus!',
                'type'=> 'danger',
            ]);
        }
    }
}
