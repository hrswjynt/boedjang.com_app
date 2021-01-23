<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Blog;
use App\Tag;
use App\BlogRelationTag;
use DataTables;
use Auth;
use DB;
use File;
use Image;

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
        $tag = Tag::all();
        return view('blog.create')->with('page','blog')->with('tag',$tag);
    }

    public function store(Request $request)
    {   
        // dd($request->all());
        $validatedData = $this->validate($request, [
            'title' => 'required',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:20480'
        ]);

        $image_name = null;
        if($request->gambar !== null){
            $image = $request->file('gambar');
            $image_name = time() . 'blog.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images/blog');
            $resize_image = Image::make($image->getRealPath());
            $resize_image->resize(null, 500, function($constraint){
                $constraint->aspectRatio();
            })->save($destinationPath . '/' . $image_name);
        }
        $blog = new Blog;
        $slug = str_replace("/","",$request->title);
        $blog->slug = strtolower(str_replace(" ","-",$slug));
        $blog->title = $request->title;
        $blog->content = $request->content;
        $blog->gambar = $image_name;
        $blog->publish = 0;
        $blog->tag_display = '';
        $blog->author = Auth::user()->id;
        $blog->save();

        foreach ($request->tag as $tag) {
            $relation = new BlogRelationTag;
            $relation->id_blog = $blog->id;
            $relation->id_tag = $tag;
            $relation->save();

            $blog->tag_display = $blog->tag_display.';'.Tag::find($tag)->name;
            $blog->save();
        }

        return redirect()->route('blog.index')->with('success','Data blog '.$request->title.' berhasil disimpan.');
    }

    public function getData(){
        $data = Blog::join('users', 'users.id','blogs.author')->select('blogs.*', 'users.name as author_name')->get();
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
        $tag = Tag::all();
        $blogtag = BlogRelationTag::where('id_blog',$blog->id)->get();
        foreach ($blogtag as $bt) {
            $arrayt[] = $bt->id_tag;
        }
        return view('blog.show')->with('blog', $blog)
                                ->with('tag', $tag)
                                ->with('blogtag', $arrayt)
                                ->with('page','blog');
    }
    
    public function edit(Blog $blog)
    {   
        $tag = Tag::all();
        $blogtag = BlogRelationTag::where('id_blog',$blog->id)->get();
        foreach ($blogtag as $bt) {
            $arrayt[] = $bt->id_tag;
        }
        return view('blog.edit')->with('page','blog')
                                    ->with('tag', $tag)
                                    ->with('blogtag', $arrayt)
                                    ->with('blog', $blog);
    }
    
    public function update(Request $request, Blog $blog)
    {
        $validatedData = $this->validate($request, [
            'title'         => 'required',
        ]);

        $image_name = null;
        if($request->gambar !== null){
            $image = $request->file('gambar');
            $image_name = time() . 'blog.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images/blog');
            $resize_image = Image::make($image->getRealPath());
            $resize_image->resize(null, 300, function($constraint){
                $constraint->aspectRatio();
            })->save($destinationPath . '/' . $image_name);
        }
        $slug = str_replace("/","",$request->title);
        $blog->slug = strtolower(str_replace(" ","-",$slug));
        $blog->title = $request->title;
        $blog->content = $request->content;
        if($image_name != null){
            if($blog->gambar !==null){
                $image_path = public_path('/images/blog/'.$blog->gambar);
                if(File::exists($image_path)) {
                    File::delete($image_path);
                }
            }
            $blog->gambar = $image_name;
        }
        $blog->tag_display = '';
        $blog->publish = $request->publish;
        $blog->author = Auth::user()->id;
        $blog->save();

        BlogRelationTag::where('id_blog',$blog->id)->delete();
        foreach ($request->tag as $tag) {
            $relation = new BlogRelationTag;
            $relation->id_blog = $blog->id;
            $relation->id_tag = $tag;
            $relation->save();

            $blog->tag_display = $blog->tag_display.';'.Tag::find($tag)->name;
            $blog->save();
        }
        return redirect()->route('blog.index')->with('success','Data Blog '.$request->title.' berhasil diupdate.');
   }

   public function delete($id){
        DB::beginTransaction();
        try{
            $blog = Blog::find($id);
            $title = $blog->title;
            $gambar = $blog->gambar;
            $blog->delete();
            if($gambar !==null){
                $image_path = public_path('/images/blog/'.$gambar);
                if(File::exists($image_path)) {
                    File::delete($image_path);
                }
            }
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
