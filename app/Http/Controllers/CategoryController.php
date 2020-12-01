<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use DataTables;
use Auth;
use DB;

class CategoryController extends Controller
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
        return view('category.index')->with('page','category');
    }

    public function create(){
        return view('category.create')->with('page','category');
    }

    public function store(Request $request)
    {   
        // dd($request->all());
        $validatedData = $this->validate($request, [
            'name'         => 'required',
        ]);
        $category = new Category;
        $category->name = $request->name;
        $category->description = $request->description;
        $category->save();
        return redirect()->route('category.index')->with('success','Data Kategori '.$request->name.' berhasil disimpan.');
    }

    public function getData(){
        $data = Category::all();
        return $this->datatable($data);
    }

    public function datatable($data)
    {
        return Datatables::of($data)
            ->addColumn('action', function ($data) {
                $action = '<div class="btn-group">';
                $action.= '<a class="btn btn-sm btn-info btn-simple shadow" href="'.route("category.show",$data->id).'" title="Info"><i class="fa fa-search"></i> Info </a>';
                $action .='<a class="btn btn-sm btn-warning btn-simple shadow" href="'.route("category.edit",$data->id).'" title="Edit"><i class="fa fa-edit"></i> Edit</a>';
                $action .='<a href="'.route("category.delete",$data->id).'" class="btn btn-sm btn-danger btn-simple shadow categoryDelete" title="Delete" data-id="'.$data->id.'"><i class="fa fa-trash"></i> Hapus</a>';
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
    
    public function show(Category $category)
    {   
        return view('category.show')->with('category', $category)->with('page','category');
    }
    
    public function edit(Category $category)
    {   
        return view('category.edit')->with('page','category')
                                        ->with('category', $category);
    }
    
    public function update(Request $request, Category $category)
    {
        $validatedData = $this->validate($request, [
            'name'         => 'required',
        ]);
        $category->name = $request->name;
        $category->description = $request->description;
        $category->save();
        return redirect()->route('category.index')->with('success','Data Kategori '.$request->name.' berhasil diupdate.');
   }

   public function delete($id){
        DB::beginTransaction();
        try{
            $category = Category::find($id);
            $name = $category->name;
            $category->delete();
            DB::commit();
            return response()->json([
                'message' => 'Kategori "'.$name.'" berhasil dihapus!',
                'type'=> 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Kategori "'.$name.'" gagal dihapus!',
                'type'=> 'danger',
            ]);
        }
    }
}
