<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BukuPedomanDivision;
use App\BukuPedomanRelationDivision;
use App\BukuPedoman;
use DataTables;
use Auth;
use DB;

class BukuPedomanDivisionController extends Controller
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
        return view('bukupedomandivision.index')->with('page','bukupedomandivision');
    }

    public function create(){
        return view('bukupedomandivision.create')->with('page','bukupedomandivision');
    }

    public function store(Request $request)
    {   
        // dd($request->all());
        $validatedData = $this->validate($request, [
            'name'         => 'required',
        ]);
        $bukupedomandivision = new BukuPedomanDivision;
        $bukupedomandivision->name = $request->name;
        $bukupedomandivision->description = $request->description;
        $bukupedomandivision->save();
        return redirect()->route('bukupedomandivision.index')->with('success','Data Divisi Buku Pedoman '.$request->name.' berhasil disimpan.');
    }

    public function getData(){
        $data = BukuPedomanDivision::all();
        return $this->datatable($data);
    }

    public function datatable($data)
    {
        return Datatables::of($data)
            ->addColumn('action', function ($data) {
                $action = '<div class="btn-group">';
                $action.= '<a class="btn btn-sm btn-info btn-simple shadow" href="'.route("bukupedomandivision.show",$data->id).'" title="Info"><i class="fa fa-search"></i> Info </a>';
                $action .='<a class="btn btn-sm btn-warning btn-simple shadow" href="'.route("bukupedomandivision.edit",$data->id).'" title="Edit"><i class="fa fa-edit"></i> Edit</a>';
                $action .='<a href="'.route("bukupedomandivision.delete",$data->id).'" class="btn btn-sm btn-danger btn-simple shadow bukupedomandivisionDelete" title="Delete" data-id="'.$data->id.'"><i class="fa fa-trash"></i> Hapus</a>';
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
    
    public function show(BukuPedomanDivision $bukupedomandivision)
    {   
        return view('bukupedomandivision.show')->with('bukupedomandivision', $bukupedomandivision)->with('page','bukupedomandivision');
    }
    
    public function edit(BukuPedomanDivision $bukupedomandivision)
    {   
        return view('bukupedomandivision.edit')->with('page','bukupedomandivision')
                                        ->with('bukupedomandivision', $bukupedomandivision);
    }
    
    public function update(Request $request, BukuPedomanDivision $bukupedomandivision)
    {
        $validatedData = $this->validate($request, [
            'name'         => 'required',
        ]);
        $bukupedomandivision->name = $request->name;
        $bukupedomandivision->description = $request->description;
        $bukupedomandivision->save();
        return redirect()->route('bukupedomandivision.index')->with('success','Data Divisi Buku Pedoman'.$request->name.' berhasil diupdate.');
   }

   public function delete($id){
        DB::beginTransaction();
        $division = BukuPedomanDivision::find($id);
        $name = $division->name;
        if(BukuPedomanRelationDivision::where('id_division',$id)->first() !== null){
            return response()->json([
                'message' => 'Divisi Buku Pedoman "'.$name.'" gagal dihapus, jenis telah digunakan.',
                'type'=> 'danger',
            ]);
        }
        try{
            $division->delete();
            DB::commit();
            return response()->json([
                'message' => 'Divisi Buku Pedoman "'.$name.'" berhasil dihapus!',
                'type'=> 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Divisi Buku Pedoman "'.$name.'" gagal dihapus!',
                'type'=> 'danger',
            ]);
        }
    }
}
