<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ketentuan;
use DataTables;
use Auth;
use DB;
use File;
use Image;

class KetentuanController extends Controller
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
        return view('ketentuan.index')->with('page','ketentuan');
    }

    public function create(){
        return view('ketentuan.create')->with('page','ketentuan');
    }

    public function store(Request $request)
    {   
        // dd($request->all());
        $validatedData = $this->validate($request, [
            'title' => 'required',
        ]);

        $ketentuan = new Ketentuan;
        $slug = str_replace("/","",$request->title);
        $ketentuan->slug = strtolower(str_replace(" ","-",$slug));
        $ketentuan->title = $request->title;
        $ketentuan->content = $request->content;
        $ketentuan->publish = 0;
        $ketentuan->sequence = $request->sequence;
        $ketentuan->save();
        return redirect()->route('ketentuan.index')->with('success','Data Ketentuan '.$request->title.' berhasil disimpan.');
    }

    public function getData(){
        $data = Ketentuan::select('ketentuan.id','ketentuan.title','ketentuan.slug','ketentuan.publish')->get();
        return $this->datatable($data);
    }

    public function datatable($data)
    {
        return Datatables::of($data)
            ->addColumn('action', function ($data) {
                $action = '<div class="btn-group">';
                $action.= '<a class="btn btn-sm btn-info btn-simple shadow" href="'.route("ketentuan.show",$data->id).'" title="Info"><i class="fa fa-search"></i></a>';
                $action .='<a class="btn btn-sm btn-warning btn-simple shadow" href="'.route("ketentuan.edit",$data->id).'" title="Edit"><i class="fa fa-edit"></i></a>';
                $action .='<a href="'.route("ketentuan.delete",$data->id).'" class="btn btn-sm btn-danger btn-simple shadow ketentuanDelete" title="Delete" data-id="'.$data->id.'"><i class="fa fa-trash"></i></a>';
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
    
    public function show(Ketentuan $ketentuan)
    {   
        return view('ketentuan.show')->with('ketentuan', $ketentuan)
                                ->with('page','ketentuan');
    }
    
    public function edit(Ketentuan $ketentuan)
    {   
        return view('ketentuan.edit')->with('ketentuan', $ketentuan)
                                ->with('page','ketentuan');
    }
    
    public function update(Request $request, Ketentuan $ketentuan)
    {   
        // dd($request->all());
        $validatedData = $this->validate($request, [
            'title'         => 'required',
        ]);

        $slug = str_replace("/","",$request->title);
        $ketentuan->slug = strtolower(str_replace(" ","-",$slug));
        $ketentuan->title = $request->title;
        $ketentuan->content = $request->content;
        $ketentuan->publish = $request->publish;
        $ketentuan->sequence = $request->sequence;
        $ketentuan->save();
        return redirect()->route('ketentuan.index')->with('success','Data Ketentuan '.$request->title.' berhasil diupdate.');
   }

   public function delete($id){
        DB::beginTransaction();
        try{
            $ketentuan = Ketentuan::find($id);
            $title = $ketentuan->title;
            $noketentuanrm->delete();

            DB::commit();
            return response()->json([
                'message' => 'Ketentuan "'.$title.'" berhasil dihapus!',
                'type'=> 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Ketetuan "'.$title.'" gagal dihapus!',
                'type'=> 'danger',
            ]);
        }
    }

    public function getListKetentuan()
    {   
        $ketentuan = Ketentuan::orderBy('sequence', 'ASC')->where('publish',1)->get();
        return view('ketentuan.home')->with('page','ketentuan_list')->with('ketentuan', $ketentuan)->with('search', null);
    }

    public function getKetentuan($slug)
    {   
        $ketentuan = Ketentuan::where('slug',$slug)->first();
        if($ketentuan == null){
            return redirect()->route('ketentuan_list.index')->with('danger','Ketentuan yang dicari tidak ditemukan.');
        }
        return view('ketentuan.post')->with('page','ketentuan_list')->with('ketentuan',$ketentuan);
    }

    public function getSearch(Request $request){
        // dd($request->all());
        $search = $request->search;

        $ketentuan = Ketentuan::orderBy('sequence', 'ASC')->where('publish',1)->where('title','like','%'.$request->search.'%')->get();

        return view('ketentuan.home')->with('page','ketentuan_list')->with('ketentuan', $ketentuan)
                    ->with('search',$search);
    }

}
