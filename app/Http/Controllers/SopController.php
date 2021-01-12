<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sop;
use App\Category;
use App\Type;
use App\SopRelationCategory;
use DataTables;
use Auth;
use DB;
use File;
use Image;

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
        $type = Type::all();
        return view('sop.create')->with('page','sop')->with('category',$category)->with('type',$type);
    }

    public function store(Request $request)
    {   
        // dd($request->all());
        $validatedData = $this->validate($request, [
            'title' => 'required',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:20480'
        ]);

        $imageName = null;
        if($request->gambar !== null){
            // $imageName = time().'sop.'.request()->gambar->getClientOriginalExtension();
            // request()->gambar->move(public_path('images/sop'), $imageName);

            $image = $request->file('gambar');
            $image_name = time() . 'sop.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images/sop');
            $resize_image = Image::make($image->getRealPath());
            $resize_image->resize(null, 300, function($constraint){
                $constraint->aspectRatio();
            })->save($destinationPath . '/' . $image_name);
            // $image->move($destinationPath, $image_name);
        }

        $sop = new Sop;
        $slug = str_replace("/","",$request->title);
        $sop->slug = str_replace(" ","-",$slug);
        $sop->title = $request->title;
        $sop->type = $request->type;
        $sop->content = $request->content;
        $sop->google_drive = $request->google_drive;
        $sop->youtube = $request->youtube;
        $sop->gambar = $image_name;
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
        $data = Sop::leftJoin('type','type.id','sop.type')->select('sop.*', 'type.name as type_name')->get();
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
        $type = Type::all();
        $sopcategory = SopRelationCategory::where('id_sop',$sop->id)->get();
        foreach ($sopcategory as $sc) {
            $arrayc[] = $sc->id_category;
        }
        return view('sop.show')->with('sop', $sop)
                                ->with('page','sop')
                                ->with('type',$type)
                                ->with('category',$category)
                                ->with('sopcategory',$arrayc);
    }
    
    public function edit(Sop $sop)
    {   
        $category = Category::all();
        $type = Type::all();
        $sopcategory = SopRelationCategory::where('id_sop',$sop->id)->get();
        foreach ($sopcategory as $sc) {
            $arrayc[] = $sc->id_category;
        }
        // dd($arrayc);        
        return view('sop.edit')->with('page','sop')
                                ->with('sop', $sop)
                                ->with('type',$type)
                                ->with('category',$category)
                                ->with('sopcategory',$arrayc)
                                ;
    }
    
    public function update(Request $request, Sop $sop)
    {
        $validatedData = $this->validate($request, [
            'title'         => 'required',
        ]);
        $image_name = null;
        if($request->gambar !== null){
            // $imageName = time().'sop.'.request()->gambar->getClientOriginalExtension();
            // request()->gambar->move(public_path('images/sop'), $imageName);

            $image = $request->file('gambar');
            $image_name = time() . 'sop.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images/sop');
            $resize_image = Image::make($image->getRealPath());
            $resize_image->resize(null, 300, function($constraint){
                $constraint->aspectRatio();
            })->save($destinationPath . '/' . $image_name);
        }
        $slug = str_replace("/","",$request->title);
        $sop->slug = str_replace(" ","-",$slug);
        $sop->title = $request->title;
        $sop->content = $request->content;
        if($image_name != null){
            if($sop->gambar !==null){
                $image_path = public_path('/images/sop/'.$sop->gambar);
                if(File::exists($image_path)) {
                    File::delete($image_path);
                }
            }
            $sop->gambar = $image_name;
        }
        $sop->category_display = '';
        $sop->publish = $request->publish;
        $sop->google_drive = $request->google_drive;
        $sop->youtube = $request->youtube;
        $sop->type = $request->type;
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
            $gambar = $sop->gambar;
            $sop->delete();
            if($gambar !==null){
                $image_path = public_path('/images/sop/'.$gambar);
                if(File::exists($image_path)) {
                    File::delete($image_path);
                }
            }
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
        $type_select = null;
        $sop = Sop::where('publish','1')->orderBy('updated_at','DESC')->paginate(15);
        $category = Category::all();
        $type = Type::all();
        return view('sop.home')->with('page','sop_list')->with('sop',$sop)->with('category',$category)->with('type',$type)->with('category_select',$category_select)->with('type_select',$type_select)->with('search',$search);
    }

    public function getSop($slug)
    {   
        $sop = Sop::where('slug',$slug)->first();
        $category = SopRelationCategory::join('category','category.id','sop_relation_category.id_category')->select('category.*')->where('id_sop',$sop->id)->get();
        if($sop == null){
            return redirect()->route('sop_list.index')->with('danger','SOP yang dicari tidak ditemukan.');
        }

        return view('sop.post')->with('page','sop_list')->with('sop',$sop)->with('category',$category);
    }

    public function getSearch(Request $request){
        // dd($request->all());
        $search = $request->search;
        $category_select = null;
        $type_select = null;
        if($request->category == 'all'){
            if($request->type == 'all'){
                $sop = Sop::leftJoin('sop_relation_category','sop_relation_category.id_sop','sop.id')->join('category','category.id','sop_relation_category.id_category')->where('sop.title','like','%'.$request->search.'%')->orderBy('sop.updated_at','DESC')->where('sop.publish','1')->select('sop.*')->groupBy('sop.id')->paginate(15);
            }else{
                $sop = Sop::leftJoin('sop_relation_category','sop_relation_category.id_sop','sop.id')->join('category','category.id','sop_relation_category.id_category')->where('sop.title','like','%'.$request->search.'%')->orderBy('sop.updated_at','DESC')->where('sop.publish','1')->where('sop.type',$request->type)->select('sop.*')->groupBy('sop.id')->paginate(15);
                $type_select = Type::find($request->type);
            }
        }else{
            if($request->type == 'all'){
                $sop = Sop::leftJoin('sop_relation_category','sop_relation_category.id_sop','sop.id')->join('category','category.id','sop_relation_category.id_category')->where('sop.title','like','%'.$request->search.'%')->orderBy('sop.updated_at','DESC')->where('sop_relation_category.id_category',$request->category)->where('sop.publish','1')->select('sop.*')->groupBy('sop.id')->paginate(15);
                $category_select = Category::find($request->category);
            }else{
                $sop = Sop::leftJoin('sop_relation_category','sop_relation_category.id_sop','sop.id')->join('category','category.id','sop_relation_category.id_category')->where('sop.title','like','%'.$request->search.'%')->orderBy('sop.updated_at','DESC')->where('sop_relation_category.id_category',$request->category)->where('sop.type',$request->type)->where('sop.publish','1')->select('sop.*')->groupBy('sop.id')->paginate(15);
                $category_select = Category::find($request->category);
                $type_select = Type::find($request->type);
            }
        }
        $category = Category::all();
        $type = Type::all();
        return view('sop.home')->with('page','sop_list')
                    ->with('sop',$sop)
                    ->with('category',$category)
                    ->with('category_select',$category_select)
                    ->with('type',$type)
                    ->with('type_select',$type_select)
                    ->with('search',$search);
    }
}
