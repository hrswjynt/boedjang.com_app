<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Norm;
use DataTables;
use Auth;
use DB;
use File;
use Image;

class NormController extends Controller
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
        return view('norm.index')->with('page','norm');
    }

    public function create(){
        return view('norm.create')->with('page','norm');
    }

    public function store(Request $request)
    {   
        // dd($request->all());
        $validatedData = $this->validate($request, [
            'title' => 'required',
        ]);

        $norm = new Norm;
        $slug = str_replace("/","",$request->title);
        $norm->slug = strtolower(str_replace(" ","-",$slug));
        $norm->title = $request->title;
        $norm->content = $request->content;
        $norm->publish = 0;
        $norm->sequence = $request->sequence;
        $norm->save();
        return redirect()->route('norm.index')->with('success','Data Norm '.$request->title.' berhasil disimpan.');
    }

    public function getData(){
        $data = Norm::select('norm.id','norm.title','norm.slug','norm.publish')->get();
        return $this->datatable($data);
    }

    public function datatable($data)
    {
        return Datatables::of($data)
            ->addColumn('action', function ($data) {
                $action = '<div class="btn-group">';
                $action.= '<a class="btn btn-sm btn-info btn-simple shadow" href="'.route("norm.show",$data->id).'" title="Info"><i class="fa fa-search"></i></a>';
                $action .='<a class="btn btn-sm btn-warning btn-simple shadow" href="'.route("norm.edit",$data->id).'" title="Edit"><i class="fa fa-edit"></i></a>';
                $action .='<a href="'.route("norm.delete",$data->id).'" class="btn btn-sm btn-danger btn-simple shadow normDelete" title="Delete" data-id="'.$data->id.'"><i class="fa fa-trash"></i></a>';
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
    
    public function show(Norm $norm)
    {   
        return view('norm.show')->with('norm', $norm)
                                ->with('page','norm');
    }
    
    public function edit(Norm $norm)
    {   
        return view('norm.edit')->with('norm', $norm)
                                ->with('page','norm');
    }
    
    public function update(Request $request, Norm $norm)
    {   
        // dd($request->all());
        $validatedData = $this->validate($request, [
            'title'         => 'required',
        ]);

        $slug = str_replace("/","",$request->title);
        $norm->slug = strtolower(str_replace(" ","-",$slug));
        $norm->title = $request->title;
        $norm->content = $request->content;
        $norm->publish = $request->publish;
        $norm->sequence = $request->sequence;
        $norm->save();
        return redirect()->route('norm.index')->with('success','Data Norm '.$request->title.' berhasil diupdate.');
   }

   public function delete($id){
        DB::beginTransaction();
        try{
            $norm = Norm::find($id);
            $title = $norm->title;
            $norm->delete();

            DB::commit();
            return response()->json([
                'message' => 'Norm "'.$title.'" berhasil dihapus!',
                'type'=> 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Norm "'.$title.'" gagal dihapus!',
                'type'=> 'danger',
            ]);
        }
    }

    public function getListNorm()
    {   
        $norm = Norm::orderBy('sequence', 'ASC')->where('publish',1)->get();
        return view('norm.home')->with('page','norm_list')->with('norm', $norm);
    }

    public function getNorm($slug)
    {   
        $norm = Norm::where('slug',$slug)->first();
        if($norm == null){
            return redirect()->route('norm_list.index')->with('danger','Norm yang dicari tidak ditemukan.');
        }
        return view('norm.post')->with('page','norm_list')->with('norm',$norm);
    }

}
