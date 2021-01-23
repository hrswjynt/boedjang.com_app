<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tag;
use App\BlogRelationTag;
use DataTables;
use Auth;
use DB;

class TagController extends Controller
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
        return view('tag.index')->with('page','tag');
    }

    public function create(){
        return view('tag.create')->with('page','tag');
    }

    public function store(Request $request)
    {   
        // dd($request->all());
        $validatedData = $this->validate($request, [
            'name'         => 'required',
        ]);
        $tag = new Tag;
        $tag->name = $request->name;
        $tag->description = $request->description;
        $tag->save();
        return redirect()->route('tag.index')->with('success','Data Tag '.$request->name.' berhasil disimpan.');
    }

    public function getData(){
        $data = Tag::all();
        return $this->datatable($data);
    }

    public function datatable($data)
    {
        return Datatables::of($data)
            ->addColumn('action', function ($data) {
                $action = '<div class="btn-group">';
                $action.= '<a class="btn btn-sm btn-info btn-simple shadow" href="'.route("tag.show",$data->id).'" title="Info"><i class="fa fa-search"></i> Info </a>';
                $action .='<a class="btn btn-sm btn-warning btn-simple shadow" href="'.route("tag.edit",$data->id).'" title="Edit"><i class="fa fa-edit"></i> Edit</a>';
                $action .='<a href="'.route("tag.delete",$data->id).'" class="btn btn-sm btn-danger btn-simple shadow tagDelete" title="Delete" data-id="'.$data->id.'"><i class="fa fa-trash"></i> Hapus</a>';
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
    
    public function show(Tag $tag)
    {   
        return view('tag.show')->with('tag', $tag)->with('page','tag');
    }
    
    public function edit(Tag $tag)
    {   
        return view('tag.edit')->with('page','tag')
                                        ->with('tag', $tag);
    }
    
    public function update(Request $request, Tag $tag)
    {
        $validatedData = $this->validate($request, [
            'name'         => 'required',
        ]);
        $tag->name = $request->name;
        $tag->description = $request->description;
        $tag->save();
        return redirect()->route('tag.index')->with('success','Data Tag '.$request->name.' berhasil diupdate.');
   }

   public function delete($id){
        DB::beginTransaction();
        $tag = Tag::find($id);
        $name = $tag->name;
        if(BlogRelationTag::where('id_tag',$id)->first() !== null){
            return response()->json([
                'message' => 'Tag "'.$name.'" gagal dihapus, tag telah digunakan.',
                'type'=> 'danger',
            ]);
        }
        try{
            $tag->delete();
            DB::commit();
            return response()->json([
                'message' => 'Tag "'.$name.'" berhasil dihapus!',
                'type'=> 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Tag "'.$name.'" gagal dihapus!',
                'type'=> 'danger',
            ]);
        }
    }
}
