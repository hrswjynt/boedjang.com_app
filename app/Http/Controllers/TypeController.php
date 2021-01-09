<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Type;
use App\Sop;
use DataTables;
use Auth;
use DB;

class TypeController extends Controller
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
        return view('type.index')->with('page','type');
    }

    public function create(){
        return view('type.create')->with('page','type');
    }

    public function store(Request $request)
    {   
        // dd($request->all());
        $validatedData = $this->validate($request, [
            'name'         => 'required',
        ]);
        $type = new Type;
        $type->name = $request->name;
        $type->description = $request->description;
        $type->save();
        return redirect()->route('type.index')->with('success','Data Jenis SOP '.$request->name.' berhasil disimpan.');
    }

    public function getData(){
        $data = Type::all();
        return $this->datatable($data);
    }

    public function datatable($data)
    {
        return Datatables::of($data)
            ->addColumn('action', function ($data) {
                $action = '<div class="btn-group">';
                $action.= '<a class="btn btn-sm btn-info btn-simple shadow" href="'.route("type.show",$data->id).'" title="Info"><i class="fa fa-search"></i> Info </a>';
                $action .='<a class="btn btn-sm btn-warning btn-simple shadow" href="'.route("type.edit",$data->id).'" title="Edit"><i class="fa fa-edit"></i> Edit</a>';
                $action .='<a href="'.route("type.delete",$data->id).'" class="btn btn-sm btn-danger btn-simple shadow typeDelete" title="Delete" data-id="'.$data->id.'"><i class="fa fa-trash"></i> Hapus</a>';
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
    
    public function show(Type $type)
    {   
        return view('type.show')->with('type', $type)->with('page','type');
    }
    
    public function edit(Type $type)
    {   
        return view('type.edit')->with('page','type')
                                        ->with('type', $type);
    }
    
    public function update(Request $request, Type $type)
    {
        $validatedData = $this->validate($request, [
            'name'         => 'required',
        ]);
        $type->name = $request->name;
        $type->description = $request->description;
        $type->save();
        return redirect()->route('type.index')->with('success','Data Jenis SOP '.$request->name.' berhasil diupdate.');
   }

   public function delete($id){
        DB::beginTransaction();
        $type = Type::find($id);
        $name = $type->name;
        if(Sop::where('type',$id)->first() !== null){
            return response()->json([
                'message' => 'Jenis SOP "'.$name.'" gagal dihapus, jenis telah digunakan.',
                'type'=> 'danger',
            ]);
        }
        try{
            $type->delete();
            DB::commit();
            return response()->json([
                'message' => 'Jenis SOP "'.$name.'" berhasil dihapus!',
                'type'=> 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Jenis SOP "'.$name.'" gagal dihapus!',
                'type'=> 'danger',
            ]);
        }
    }
}
