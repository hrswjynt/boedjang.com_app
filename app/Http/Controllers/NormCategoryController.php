<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\NormCategory;
use App\Norm;
use DataTables;
use Auth;
use DB;

class NormCategoryController extends Controller
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
        return view('normcategory.index')->with('page','normcategory');
    }

    public function create(){
        return view('normcategory.create')->with('page','normcategory');
    }

    public function store(Request $request)
    {   
        // dd($request->all());
        $validatedData = $this->validate($request, [
            'name'         => 'required',
        ]);
        $normcategory = new NormCategory;
        $normcategory->name = $request->name;
        $normcategory->description = $request->description;
        $normcategory->save();
        return redirect()->route('normcategory.index')->with('success','Data Kategori Norm '.$request->name.' berhasil disimpan.');
    }

    public function getData(){
        $data = NormCategory::all();
        return $this->datatable($data);
    }

    public function datatable($data)
    {
        return Datatables::of($data)
            ->addColumn('action', function ($data) {
                $action = '<div class="btn-group">';
                $action.= '<a class="btn btn-sm btn-info btn-simple shadow" href="'.route("normcategory.show",$data->id).'" title="Info"><i class="fa fa-search"></i> Info </a>';
                $action .='<a class="btn btn-sm btn-warning btn-simple shadow" href="'.route("normcategory.edit",$data->id).'" title="Edit"><i class="fa fa-edit"></i> Edit</a>';
                $action .='<a href="'.route("normcategory.delete",$data->id).'" class="btn btn-sm btn-danger btn-simple shadow normcategoryDelete" title="Delete" data-id="'.$data->id.'"><i class="fa fa-trash"></i> Hapus</a>';
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
    
    public function show(NormCategory $normcategory)
    {   
        return view('normcategory.show')->with('normcategory', $normcategory)->with('page','normcategory');
    }
    
    public function edit(NormCategory $normcategory)
    {   
        return view('normcategory.edit')->with('page','normcategory')
                                        ->with('normcategory', $normcategory);
    }
    
    public function update(Request $request, NormCategory $normcategory)
    {
        $validatedData = $this->validate($request, [
            'name'         => 'required',
        ]);
        $normcategory->name = $request->name;
        $normcategory->description = $request->description;
        $normcategory->save();
        return redirect()->route('normcategory.index')->with('success','Data Kategori Norm '.$request->name.' berhasil diupdate.');
   }

   public function delete($id){
        DB::beginTransaction();
        $normcategory = NormCategory::find($id);
        $name = $normcategory->name;
        if(Norm::where('norm_category',$id)->first() !== null){
            return response()->json([
                'message' => 'Kategori Norm "'.$name.'" gagal dihapus, Kategori telah digunakan.',
                'type'=> 'danger',
            ]);
        }
        try{
            $normcategory->delete();
            DB::commit();
            return response()->json([
                'message' => 'Kategori Norm "'.$name.'" berhasil dihapus!',
                'type'=> 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Kategori Norm "'.$name.'" gagal dihapus!',
                'type'=> 'danger',
            ]);
        }
    }
}
