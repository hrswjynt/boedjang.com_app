<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bab;
use App\SubBab;
use DataTables;
use Auth;
use DB;
use File;
use Image;

class SubBabController extends Controller
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
        return view('subbab.index')->with('page','subbab');
    }

    public function create(){
        $bab = Bab::orderBy('sequence', 'ASC')->get();
        return view('subbab.create')->with('page','subbab')->with('bab',$bab);
    }

    public function store(Request $request)
    {   
        // dd($request->all());
        $validatedData = $this->validate($request, [
            'title' => 'required',
        ]);

        $subbab = new SubBab;
        $slug = str_replace("/","",$request->title);
        $subbab->slug = strtolower(str_replace(" ","-",$slug));
        $subbab->title = $request->title;
        $subbab->bab = $request->bab;
        $subbab->content = $request->content;
        $subbab->publish = 0;
        $subbab->sequence = $request->sequence;
        $subbab->save();
        return redirect()->route('subbab.index')->with('success','Data Sub Bab '.$request->title.' berhasil disimpan.');
    }

    public function getData(){
        $data = SubBab::leftJoin('bab','bab.id','sub_bab.bab')->select('sub_bab.id','sub_bab.title','sub_bab.slug','sub_bab.publish','sub_bab.bab','bab.name as bab_name')->get();
        // ->orderBy('bab.sequence', 'ASC')->orderBy('sub_bab.sequence', 'ASC')
        return $this->datatable($data);
    }

    public function datatable($data)
    {
        return Datatables::of($data)
            ->addColumn('action', function ($data) {
                $action = '<div class="btn-group">';
                $action.= '<a class="btn btn-sm btn-info btn-simple shadow" href="'.route("subbab.show",$data->id).'" title="Info"><i class="fa fa-search"></i></a>';
                $action .='<a class="btn btn-sm btn-warning btn-simple shadow" href="'.route("subbab.edit",$data->id).'" title="Edit"><i class="fa fa-edit"></i></a>';
                $action .='<a href="'.route("subbab.delete",$data->id).'" class="btn btn-sm btn-danger btn-simple shadow subbabDelete" title="Delete" data-id="'.$data->id.'"><i class="fa fa-trash"></i></a>';
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
    
    public function show(SubBab $subbab)
    {   
        $bab = Bab::orderBy('sequence', 'ASC')->get();
        return view('subbab.show')->with('subbab', $subbab)
                                ->with('page','subbab')
                                ->with('bab',$bab);
    }
    
    public function edit(SubBab $subbab)
    {   
        $bab = Bab::orderBy('sequence', 'ASC')->get();
        return view('subbab.edit')->with('subbab', $subbab)
                                ->with('page','subbab')
                                ->with('bab',$bab);
    }
    
    public function update(Request $request, SubBab $subbab)
    {   
        // dd($request->all());
        $validatedData = $this->validate($request, [
            'title'         => 'required',
        ]);

        $slug = str_replace("/","",$request->title);
        $subbab->slug = strtolower(str_replace(" ","-",$slug));
        $subbab->title = $request->title;
        $subbab->bab = $request->bab;
        $subbab->content = $request->content;
        $subbab->publish = $request->publish;
        $subbab->sequence = $request->sequence;
        $subbab->save();
        return redirect()->route('subbab.index')->with('success','Data Sub Bab '.$request->title.' berhasil diupdate.');
   }

   public function delete($id){
        DB::beginTransaction();
        try{
            $subbab = SubBab::find($id);
            $title = $subbab->title;
            $subbab->delete();

            DB::commit();
            return response()->json([
                'message' => 'Sub Bab "'.$title.'" berhasil dihapus!',
                'type'=> 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Sub Bab "'.$title.'" gagal dihapus!',
                'type'=> 'danger',
            ]);
        }
    }

    public function getListBukuSaku()
    {   
        $bab = Bab::orderBy('sequence', 'ASC')->get();
        // dd($sop);
        return view('subbab.home')->with('page','bukusaku_list')->with('bab',$bab);
    }

    public function getBukuSaku($slug)
    {   
        $subbab = SubBab::where('slug',$slug)->first();
        if($subbab == null){
            return redirect()->route('bukusaku_list.index')->with('danger','Peraturan Perusahaan yang dicari tidak ditemukan.');
        }
        $bab = SubBab::leftJoin('bab','bab.id','sub_bab.bab')->where('sub_bab.id',$subbab->id)->select('bab.name')->first();
        return view('subbab.post')->with('page','bukusaku_list')->with('subbab',$subbab)->with('bab',$bab);
    }

}
