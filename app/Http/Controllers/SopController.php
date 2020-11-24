<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sop;
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
        return view('sop.create')->with('page','sop');
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
            $imageName = time().'sop.'.request()->gambar->getClientOriginalExtension();
            request()->gambar->move(public_path('images/sop'), $imageName);
        }

        $sop = new Sop;
        $sop->slug = str_replace(" ","-",$request->slug);
        $sop->title = $request->title;
        $sop->content = $request->content;
        $sop->description = $request->description;
        $sop->gambar = $imageName;
        $sop->publish = 0;
        $sop->save();

        return redirect()->back()->with('success','Data SOP '.$request->title.' berhasil disimpan.');
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
        return view('sop.show')->with('sop', $sop)->with('page','sop');
    }
    
    public function edit(Sop $sop)
    {   
        return view('sop.edit')->with('page','sop')
                                        ->with('sop', $sop);
    }
    
    public function update(Request $request, Sop $sop)
    {
        $validatedData = $this->validate($request, [
            'title'         => 'required',
            'slug'          => 'required',
            'description'   => 'required'
        ]);
        $imageName = null;
        if($request->gambar !== null){
            $imageName = time().'sop.'.request()->gambar->getClientOriginalExtension();
            request()->gambar->move(public_path('images/sop'), $imageName);
        }
        $sop->slug = str_replace(" ","-",$request->slug);
        $sop->title = $request->title;
        $sop->content = $request->content;
        $sop->description = $request->description;
        if($imageName != null){
            if($sop->gambar !==null){
                $image_path = public_path('images/sop'.$sop->gambar);
                if(File::exists($image_path)) {
                    File::delete($image_path);
                }
            }
            $sop->gambar = $imageName;
        }
        $sop->publish = $request->publish;
        $sop->save();
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
        $sop = Sop::orderBy('updated_at','DESC')->get();
        return view('sop.home')->with('page','sop_list')->with('sop',$sop);
    }

    public function getSop($slug)
    {   
        $sop = Sop::where('slug',$slug)->first();
        if($sop == null){
            return redirect()->route('sop_list.index')->with('danger','SOP yang dicari tidak ditemukan.');
        }

        return view('sop.post')->with('page','sop_list')->with('sop',$sop);
    }
}
