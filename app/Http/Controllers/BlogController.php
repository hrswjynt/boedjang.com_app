<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Blog;
use DataTables;
use Auth;
use DB;

class BlogController extends Controller
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
        return view('blog.index')->with('page','blog');
    }

    public function create(){
        return view('blog.create')->with('page','blog');
    }

    public function store(Request $request)
    {   
        // dd($request->all());
        $validatedData = $this->validate($request, [
            'title'         => 'required',
            'slug'          => 'required',
            'description'   => 'required'
        ]);
        $imageName = null;
        if($request->gambar !== null){
            $imageName = time().'blog.'.request()->gambar->getClientOriginalExtension();
            request()->gambar->move(public_path('images/blog'), $imageName);
        }
        $blog = new Blog;
        $blog->slug = str_replace(" ","-",$request->slug);
        $blog->title = $request->title;
        $blog->content = $request->content;
        $blog->description = $request->description;
        $blog->publish = 0;
        $blog->save();

        return redirect()->back()->with('success','Data blog '.$request->title.' berhasil disimpan.');
    }

    public function getData(){
        $data = Blog::all();
        return $this->datatable($data);
    }

    public function datatable($data)
    {
        return Datatables::of($data)
            ->addColumn('action', function ($data) {
                $action = '<div class="btn-group">';
                $action.= '<a class="btn btn-sm btn-info btn-simple shadow" href="'.route("blog.show",$data->id).'" title="Info"><i class="fa fa-search"></i> Info </a>';
                $action .='<a class="btn btn-sm btn-warning btn-simple shadow" href="'.route("blog.edit",$data->id).'" title="Edit"><i class="fa fa-edit"></i> Edit</a>';
                $action .='<a href="'.route("blog.delete",$data->id).'" class="btn btn-sm btn-danger btn-simple shadow blogDelete" title="Delete" data-id="'.$data->id.'"><i class="fa fa-trash"></i> Hapus</a>';
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
    
    public function show(Blog $blog)
    {   
        return view('blog.show')->with('blog', $blog)->with('page','blog');
    }
    
    public function edit(Blog $blog)
    {   
        return view('blog.edit')->with('page','blog')
                                        ->with('blog', $blog);
    }
    
    public function update(Request $request, Blog $blog)
    {
        $validatedData = $this->validate($request, [
            'title'         => 'required',
            'slug'          => 'required',
            'description'   => 'required'
        ]);
        $imageName = null;
        if($request->gambar !== null){
            $imageName = time().'blog.'.request()->gambar->getClientOriginalExtension();
            request()->gambar->move(public_path('images/blog'), $imageName);
        }
        $blog->slug = str_replace(" ","-",$request->slug);
        $blog->title = $request->title;
        $blog->content = $request->content;
        $blog->description = $request->description;
        if($imageName != null){
            if($blog->gambar !==null){
                $image_path = public_path('images/blog'.$blog->gambar);
                if(File::exists($image_path)) {
                    File::delete($image_path);
                }
            }
            $blog->gambar = $imageName;
        }
        $blog->publish = $request->publish;
        $blog->save();
        return redirect()->route('blog.index')->with('success','Data blog '.$request->title.' berhasil diupdate.');
   }

   public function delete($id){
        DB::beginTransaction();
        try{
            $blog = Blog::find($id);
            $title = $blog->title;
            $blog->delete();
            DB::commit();
            return response()->json([
                'message' => 'Blog "'.$title.'" berhasil dihapus!',
                'type'=> 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Blog "'.$title.'" gagal dihapus!',
                'type'=> 'danger',
            ]);
        }
    }
}
