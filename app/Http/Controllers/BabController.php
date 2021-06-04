<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bab;
use App\SubBab;
use DataTables;
use Auth;
use DB;

class BabController extends Controller
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
        return view('bab.index')->with('page','bab');
    }

    public function create(){
        return view('bab.create')->with('page','bab');
    }

    public function store(Request $request)
    {   
        // dd($request->all());
        $validatedData = $this->validate($request, [
            'name'         => 'required',
        ]);
        $bab = new Bab;
        $bab->name = $request->name;
        $bab->description = $request->description;
        $bab->sequence = $request->sequence;
        $bab->save();
        return redirect()->route('bab.index')->with('success','Data Bab '.$request->name.' berhasil disimpan.');
    }

    public function getData(){
        $data = Bab::all();
        return $this->datatable($data);
    }

    public function datatable($data)
    {
        return Datatables::of($data)
            ->addColumn('action', function ($data) {
                $action = '<div class="btn-group">';
                $action.= '<a class="btn btn-sm btn-info btn-simple shadow" href="'.route("bab.show",$data->id).'" title="Info"><i class="fa fa-search"></i> Info </a>';
                $action .='<a class="btn btn-sm btn-warning btn-simple shadow" href="'.route("bab.edit",$data->id).'" title="Edit"><i class="fa fa-edit"></i> Edit</a>';
                $action .='<a href="'.route("bab.delete",$data->id).'" class="btn btn-sm btn-danger btn-simple shadow babDelete" title="Delete" data-id="'.$data->id.'"><i class="fa fa-trash"></i> Hapus</a>';
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
    
    public function show(Bab $bab)
    {   
        return view('bab.show')->with('bab', $bab)->with('page','bab');
    }
    
    public function edit(Bab $bab)
    {   
        return view('bab.edit')->with('page','bab')
                                        ->with('bab', $bab);
    }
    
    public function update(Request $request, Bab $bab)
    {
        $validatedData = $this->validate($request, [
            'name'         => 'required',
        ]);
        $bab->name = $request->name;
        $bab->description = $request->description;
        $bab->sequence = $request->sequence;
        $bab->save();
        return redirect()->route('bab.index')->with('success','Data Bab '.$request->name.' berhasil diupdate.');
   }

   public function delete($id){
        DB::beginTransaction();
        $bab = Bab::find($id);
        $name = $bab->name;
        if(SubBab::where('bab',$id)->first() !== null){
            return response()->json([
                'message' => 'Bab "'.$name.'" gagal dihapus, jenis telah digunakan.',
                'type'=> 'danger',
            ]);
        }
        try{
            $bab->delete();
            DB::commit();
            return response()->json([
                'message' => 'Bab "'.$name.'" berhasil dihapus!',
                'type'=> 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Bab "'.$name.'" gagal dihapus!',
                'type'=> 'danger',
            ]);
        }
    }
}
