<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Karyawan;
use App\BukuPedoman;
use App\BukuPedomanDivision;
use App\BukuPedomanRelationDivision;
use App\BukuPedomanRelationSop;
use App\Sop;
use DataTables;
use Auth;
use DB;
use File;
use Image;

class BukuPedomanController extends Controller
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
        return view('bukupedoman.index')->with('page','bukupedoman');
    }

    public function create(){
        $division = BukuPedomanDivision::all();
        $sop = Sop::where('publish', 1)->get();
        return view('bukupedoman.create')->with('page','bukupedoman')->with('division',$division)->with('sop',$sop);
    }

    public function store(Request $request)
    {   
        // dd($request->all());
        $validatedData = $this->validate($request, [
            'title' => 'required',
            'gambar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:20480'
        ]);

        $image_name = null;
        if($request->gambar !== null){
            $image = $request->file('gambar');
            $image_name = time() . 'bukupedoman.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images/bukupedoman');
            $resize_image = Image::make($image->getRealPath());
            // $resize_image->resize(null, null, function($constraint){
            //     $constraint->aspectRatio();
            // })->save($destinationPath . '/' . $image_name);
            $resize_image->save($destinationPath . '/' . $image_name);
        }

        $bukupedoman = new BukuPedoman;
        $slug = str_replace("/","",$request->title);
        $bukupedoman->slug = strtolower(str_replace(" ","-",$slug));
        $bukupedoman->title = $request->title;
        $bukupedoman->content = $request->content;
        $bukupedoman->gambar = $image_name;
        $bukupedoman->publish = 0;
        $bukupedoman->reader = $request->reader;
        $bukupedoman->division_display = '';
        $bukupedoman->save();

        foreach ($request->division as $division) {
            $relation = new BukuPedomanRelationDivision;
            $relation->id_buku_pedoman = $bukupedoman->id;
            $relation->id_division = $division;
            $relation->save();

            $bukupedoman->division_display = $bukupedoman->division_display.';'.BukuPedomanDivision::find($division)->name;
            $bukupedoman->save();
        }

        foreach ($request->sop as $sop) {
            $relation = new BukuPedomanRelationSop;
            $relation->id_buku_pedoman = $bukupedoman->id;
            $relation->id_sop = $sop;
            $relation->save();
        }

        return redirect()->route('bukupedoman.index')->with('success','Data Buku Pedoman '.$request->title.' berhasil disimpan.');
    }

    public function getData(){
        $data = BukuPedoman::select('buku_pedoman.id','buku_pedoman.title','buku_pedoman.slug','buku_pedoman.publish')->get();
        return $this->datatable($data);
    }

    public function datatable($data)
    {
        return Datatables::of($data)
            ->addColumn('action', function ($data) {
                $action = '<div class="btn-group">';
                $action.= '<a class="btn btn-sm btn-info btn-simple shadow" href="'.route("bukupedoman.show",$data->id).'" title="Info"><i class="fa fa-search"></i></a>';
                $action .='<a class="btn btn-sm btn-warning btn-simple shadow" href="'.route("bukupedoman.edit",$data->id).'" title="Edit"><i class="fa fa-edit"></i></a>';
                $action .='<a href="'.route("bukupedoman.delete",$data->id).'" class="btn btn-sm btn-danger btn-simple shadow bukupedomanDelete" title="Delete" data-id="'.$data->id.'"><i class="fa fa-trash"></i></a>';
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
    
    public function show(BukuPedoman $bukupedoman)
    {   
        $division = BukuPedomanDivision::all();
        $bukupedomandivision = BukuPedomanRelationDivision::where('id_buku_pedoman',$bukupedoman->id)->get();
        foreach ($bukupedomandivision as $bd) {
            $arrayd[] = $bd->id_division;
        }
        return view('bukupedoman.show')->with('bukupedoman', $bukupedoman)
                                ->with('page','bukupedoman')
                                ->with('division',$division)
                                ->with('bukupedomandivision',$arrayd);
    }
    
    public function edit(BukuPedoman $bukupedoman)
    {   
        $arrayd = [];
        $arrays = [];
        $division = BukuPedomanDivision::all();
        $sop = Sop::where('publish', 1)->get();
        $soprelate = BukuPedomanRelationSop::where('id_buku_pedoman', $bukupedoman->id)->get();
        foreach ($soprelate as $sr) {
            $arrays[] = $sr->id_sop;
        }
        $bukupedomandivision = BukuPedomanRelationDivision::where('id_buku_pedoman',$bukupedoman->id)->get();
        foreach ($bukupedomandivision as $bd) {
            $arrayd[] = $bd->id_division;
        }
        // dd($arrayd);
        return view('bukupedoman.edit')->with('page','bukupedoman')
                                ->with('bukupedoman', $bukupedoman)
                                ->with('division',$division)
                                ->with('sop',$sop)
                                ->with('soprelate',$arrays)
                                ->with('bukupedomandivision',$arrayd)
                                ;
    }
    
    public function update(Request $request, BukuPedoman $bukupedoman)
    {   
        $validatedData = $this->validate($request, [
            'title'         => 'required',
        ]);
        $image_name = null;
        if($request->gambar !== null){
            $image = $request->file('gambar');
            $image_name = time() . 'bukupedoman.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images/bukupedoman');
            $resize_image = Image::make($image->getRealPath());
            // $resize_image->resize(null, null, function($constraint){
            //     $constraint->aspectRatio();
            // })->save($destinationPath . '/' . $image_name);
            $resize_image->save($destinationPath . '/' . $image_name);
        }
        $slug = str_replace("/","",$request->title);
        $bukupedoman->slug = strtolower(str_replace(" ","-",$slug));
        $bukupedoman->title = $request->title;
        $bukupedoman->content = $request->content;
        if($image_name != null){
            if($bukupedoman->gambar !==null){
                $image_path = public_path('/images/bukupedoman/'.$bukupedoman->gambar);
                if(File::exists($image_path)) {
                    File::delete($image_path);
                }
            }
            $bukupedoman->gambar = $image_name;
        }
        $bukupedoman->division_display = '';
        $bukupedoman->publish = $request->publish;
        $bukupedoman->reader = $request->reader;
        $bukupedoman->save();

        BukuPedomanRelationDivision::where('id_buku_pedoman',$bukupedoman->id)->delete();
        foreach ($request->division as $division) {
            $relation = new BukuPedomanRelationDivision;
            $relation->id_buku_pedoman = $bukupedoman->id;
            $relation->id_division = $division;
            $relation->save();
            $bukupedoman->division_display = $bukupedoman->division_display.';'.BukuPedomanDivision::find($division)->name;
            $bukupedoman->save();
        }
        BukuPedomanRelationSop::where('id_buku_pedoman',$bukupedoman->id)->delete();
        foreach ($request->sop as $sop) {
            $relation = new BukuPedomanRelationSop;
            $relation->id_buku_pedoman = $bukupedoman->id;
            $relation->id_sop = $sop;
            $relation->save();
        }

        return redirect()->route('bukupedoman.index')->with('success','Data Buku Pedoman '.$request->title.' berhasil diupdate.');
   }

   public function delete($id){
        DB::beginTransaction();
        try{
            $bukupedoman = BukuPedoman::find($id);
            $title = $bukupedoman->title;
            $gambar = $bukupedoman->gambar;
            $bukupedoman->delete();
            if($gambar !==null){
                $image_path = public_path('/images/bukupedoman/'.$gambar);
                if(File::exists($image_path)) {
                    File::delete($image_path);
                }
            }
            $division = BukuPedomanRelationDivision::where("id_buku_pedoman", $id)->delete();
            $sop = BukuPedomanRelationSop::where("id_buku_pedoman", $id)->delete();
            DB::commit();
            return response()->json([
                'message' => 'Buku Pedoman "'.$title.'" berhasil dihapus!',
                'type'=> 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Buku Pedoman "'.$title.'" gagal dihapus!',
                'type'=> 'danger',
            ]);
        }
    }

    public function getList()
    {   
        $karyawan = Karyawan::where('NIP',Auth::user()->username)->first();
        // if(Auth::user()->role == 5 && $karyawan->Cabang !== "HeadOffice"){
        //     $message_type = 'danger';
        //     $message = 'Fitur Buku Pedoman belum dapat diakses.';
        //     return redirect()->route('dashboard')->with($message_type,$message);
        // }
        $search = null;
        $division_select = null;
        if(Auth::user()->role == 5){
            $bukupedoman = BukuPedoman::where('publish','1')->where('reader',1)->orderBy('updated_at','DESC')->paginate(6);
        }else{
            $bukupedoman = BukuPedoman::where('publish','1')->orderBy('updated_at','DESC')->paginate(6);
        }
        
        $division = BukuPedomanDivision::all();
        return view('bukupedoman.home')->with('page','bukupedoman_list')->with('bukupedoman',$bukupedoman)->with('search',$search)->with('division',$division)->with('division_select',$division_select);
    }

    public function getBukuPedoman($slug)
    {   
        $karyawan = Karyawan::where('NIP',Auth::user()->username)->first();
        // if(Auth::user()->role == 5 && $karyawan->Cabang !== "HeadOffice"){
        //     $message_type = 'danger';
        //     $message = 'Fitur Buku Pedoman belum dapat diakses.';
        //     return redirect()->route('dashboard')->with($message_type,$message);
        // }
        if(Auth::user()->role == 5){
            $bukupedoman = BukuPedoman::where('slug',$slug)->where('reader',1)->first();
        }else{
            $bukupedoman = BukuPedoman::where('slug',$slug)->first();
        }
        if($bukupedoman == null){
            return redirect()->route('bukupedoman_list.index')->with('danger','Buku Pedoman yang dicari tidak ditemukan.');
        }
        $division = BukuPedomanRelationDivision::join('bpm_division','bpm_division.id','buku_pedoman_relation_division.id_division')->select('bpm_division.*')->where('id_buku_pedoman',$bukupedoman->id)->get();
        $sop = BukuPedomanRelationSop::join('sop','sop.id','buku_pedoman_relation_sop.id_sop')->select('sop.*')->where('id_buku_pedoman',$bukupedoman->id)->get();
        
        return view('bukupedoman.post')->with('page','bukupedoman_list')->with('bukupedoman',$bukupedoman)->with('division',$division)->with('sop',$sop);
    }

    public function getSearch(Request $request){
        // dd($request->all());
        $karyawan = Karyawan::where('NIP',Auth::user()->username)->first();
        if(Auth::user()->role == 5 && $karyawan->Cabang !== "HeadOffice"){
            $message_type = 'warning';
            $message = 'Fitur Buku Pedoman belum dapat diakses.';
            return redirect()->route('dashboard')->with($message_type,$message);
        }
        $search = $request->search;
        $division_select = null;
        $query_division = $request->division;

        if($request->division == 'all'){
            $query_division = '%%';
        }else{
            $division_select = BukuPedomanDivision::find($request->division);
        }


        $bukupedoman = BukuPedoman::leftJoin('buku_pedoman_relation_division','buku_pedoman_relation_division.id_buku_pedoman','buku_pedoman.id')
                    ->join('bpm_division','bpm_division.id','buku_pedoman_relation_division.id_division')
                    ->where('buku_pedoman.title','like','%'.$request->search.'%')
                    ->orderBy('buku_pedoman.updated_at','DESC')
                    ->where('buku_pedoman_relation_division.id_division','like',$query_division)
                    ->where('buku_pedoman.publish','1')
                    ->groupBy('buku_pedoman.id')
                    ->select('buku_pedoman.*')->paginate(6);
        // dd($bukupedoman);
        $division = BukuPedomanDivision::all();
        return view('bukupedoman.home')->with('page','bukupedoman_list')
                    ->with('bukupedoman',$bukupedoman)
                    ->with('division',$division)
                    ->with('division_select',$division_select)
                    ->with('search',$search);
    }

}
