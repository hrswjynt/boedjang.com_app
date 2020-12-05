<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sop;
use App\Category;
use App\SopRelationCategory;
use DataTables;
use Auth;
use DB;
use File;

class SopController extends Controller
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
        return view('sop.index')->with('page','sop');
    }

    public function create(){
        $category = Category::all();
        return view('sop.create')->with('page','sop')->with('category',$category);
    }

    public function store(Request $request)
    {   
        // dd($request->all());
        $validatedData = $this->validate($request, [
            'title'         => 'required',
        ]);

        $imageName = null;
        if($request->gambar !== null){
            $imageName = time().'sop.'.request()->gambar->getClientOriginalExtension();
            request()->gambar->move(public_path('images/sop'), $imageName);
        }

        $sop = new Sop;
        $slug = str_replace("/","",$request->title);
        $sop->slug = str_replace(" ","-",$slug);
        $sop->title = $request->title;
        $sop->content = $request->content;
        $sop->google_drive = $request->google_drive;
        $sop->youtube = $request->youtube;
        $sop->gambar = $imageName;
        $sop->publish = 0;
        $sop->category_display = '';
        $sop->save();

        foreach ($request->category as $category) {
            $relation = new SopRelationCategory;
            $relation->id_sop = $sop->id;
            $relation->id_category = $category;
            $relation->save();

            $sop->category_display = $sop->category_display.';'.Category::find($category)->name;
            $sop->save();
        }

        return redirect()->route('sop.index')->with('success','Data SOP '.$request->title.' berhasil disimpan.');
    }

    public function getData(){
        $data = Sop::all();
        return $this->datatable($data);
    }

    public function datatable($data)
    {
        return Datatables::of($data)
            ->addColumn('action', function ($data) {
                $action = '<div class="btn-group">';
                $action.= '<a class="btn btn-sm btn-info btn-simple shadow" href="'.route("sop.show",$data->id).'" title="Info"><i class="fa fa-search"></i></a>';
                $action .='<a class="btn btn-sm btn-warning btn-simple shadow" href="'.route("sop.edit",$data->id).'" title="Edit"><i class="fa fa-edit"></i></a>';
                $action .='<a href="'.route("sop.delete",$data->id).'" class="btn btn-sm btn-danger btn-simple shadow sopDelete" title="Delete" data-id="'.$data->id.'"><i class="fa fa-trash"></i></a>';
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
    
    public function show(Sop $sop)
    {   
        $category = Category::all();
        $sopcategory = SopRelationCategory::where('id_sop',$sop->id)->get();
        foreach ($sopcategory as $sc) {
            $arrayc[] = $sc->id_category;
        }
        return view('sop.show')->with('sop', $sop)
                                ->with('page','sop')
                                ->with('category',$category)
                                ->with('sopcategory',$arrayc);
    }
    
    public function edit(Sop $sop)
    {   
        $category = Category::all();
        $sopcategory = SopRelationCategory::where('id_sop',$sop->id)->get();
        foreach ($sopcategory as $sc) {
            $arrayc[] = $sc->id_category;
        }
        // dd($arrayc);        
        return view('sop.edit')->with('page','sop')
                                ->with('sop', $sop)
                                ->with('category',$category)
                                ->with('sopcategory',$arrayc)
                                ;
    }
    
    public function update(Request $request, Sop $sop)
    {
        $validatedData = $this->validate($request, [
            'title'         => 'required',
        ]);
        $imageName = null;
        if($request->gambar !== null){
            $imageName = time().'sop.'.request()->gambar->getClientOriginalExtension();
            request()->gambar->move(public_path('images/sop'), $imageName);
        }
        $slug = str_replace("/","",$request->title);
        $sop->slug = str_replace(" ","-",$slug);
        $sop->title = $request->title;
        $sop->content = $request->content;
        if($imageName != null){
            if($sop->gambar !==null){
                $image_path = public_path('images/sop'.$sop->gambar);
                if(File::exists($image_path)) {
                    File::delete($image_path);
                }
            }
            $sop->gambar = $imageName;
        }
        $sop->category_display = '';
        $sop->publish = $request->publish;
        $sop->google_drive = $request->google_drive;
        $sop->youtube = $request->youtube;
        $sop->save();

        SopRelationCategory::where('id_sop',$sop->id)->delete();
        foreach ($request->category as $category) {
            $relation = new SopRelationCategory;
            $relation->id_sop = $sop->id;
            $relation->id_category = $category;
            $relation->save();

            $sop->category_display = $sop->category_display.';'.Category::find($category)->name;
            $sop->save();
        }
        return redirect()->route('sop.index')->with('success','Data SOP '.$request->title.' berhasil diupdate.');
   }

   public function delete($id){
        DB::beginTransaction();
        try{
            $sop = Sop::find($id);
            $title = $sop->title;
            $sop->delete();
            DB::commit();
            return response()->json([
                'message' => 'SOP "'.$title.'" berhasil dihapus!',
                'type'=> 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'SOP "'.$title.'" gagal dihapus!',
                'type'=> 'danger',
            ]);
        }
    }

    public function getList()
    {   
        $search = null;
        $category_select = null;
        $sop = Sop::where('publish','1')->orderBy('updated_at','DESC')->paginate(15);
        $category = Category::all();
        return view('sop.home')->with('page','sop_list')->with('sop',$sop)->with('category',$category)->with('category_select',$category_select)->with('search',$search);
    }

    public function getSop($slug)
    {   
        $sop = Sop::where('slug',$slug)->first();
        $category = SopRelationCategory::join('category','category.id','sop_relation_category.id_category')->select('category.*')->where('id_sop',$sop->id)->paginate(15);
        if($sop == null){
            return redirect()->route('sop_list.index')->with('danger','SOP yang dicari tidak ditemukan.');
        }

        return view('sop.post')->with('page','sop_list')->with('sop',$sop)->with('category',$category);
    }

    public function getSearch(Request $request){
        // dd($request->all());
        $search = $request->search;
        $category_select = null;
        if($request->category == 'all'){
            $sop = Sop::leftJoin('sop_relation_category','sop_relation_category.id_sop','sop.id')->join('category','category.id','sop_relation_category.id_category')->where('sop.slug','like','%'.$request->search.'%')->orderBy('sop.updated_at','DESC')->where('sop.publish','1')->select('sop.*')->groupBy('sop.id')->get();
        }else{
            $sop = Sop::leftJoin('sop_relation_category','sop_relation_category.id_sop','sop.id')->join('category','category.id','sop_relation_category.id_category')->where('sop.slug','like','%'.$request->search.'%')->orderBy('sop.updated_at','DESC')->where('sop_relation_category.id_category',$request->category)->where('sop.publish','1')->select('sop.*')->groupBy('sop.id')->get();
            $category_select = Category::find($request->category);
        }
        
        $category = Category::all();
        return view('sop.home')->with('page','sop_list')->with('sop',$sop)->with('category',$category)->with('category_select',$category_select)->with('search',$search);
    }
}
