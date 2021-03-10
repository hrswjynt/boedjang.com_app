<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sop;
use App\SopHistory;
use App\SopNotification;
use App\Category;
use App\Type;
use App\Jabatan;
use App\SopRelationCategory;
use App\SopRelationJabatan;
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
        $type = Type::orderBy('sequence', 'ASC')->get();
        $jabatan = Jabatan::all();
        return view('sop.create')->with('page','sop')->with('category',$category)->with('type',$type)->with('jabatan',$jabatan);
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
        $sop->slug = strtolower(str_replace(" ","-",$slug));
        $sop->title = $request->title;
        $sop->type = $request->type;
        // $sop->jabatan = $request->jabatan;
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

        foreach ($request->jabatan as $jabatan) {
            $relation = new SopRelationJabatan;
            $relation->id_sop = $sop->id;
            $relation->id_jabatan = $jabatan;
            $relation->save();

            $sop->jabatan_display = $sop->jabatan_display.';'.Jabatan::find($jabatan)->name;
            $sop->save();
        }

        $notif = new SopNotification;
        $notif->sop = $sop->id;
        $notif->date = date('Y-m-d H:i:s');
        $notif->keterangan = 'SOP "'.$sop->title.'" telah dibuat oleh '.Auth::user()->name.'.';
        $notif->admin = Auth::user()->id;
        $notif->type = 1;
        $notif->save();

        return redirect()->route('sop.index')->with('success','Data SOP '.$request->title.' berhasil disimpan.');
    }

    public function getData(){
        $data = Sop::leftJoin('type','type.id','sop.type')->select('sop.id','sop.title','sop.slug','sop.publish','sop.google_drive','sop.youtube','sop.type','type.name as type_name')->get();
        return $this->datatable($data);
    }

    public function datatable($data)
    {
        return Datatables::of($data)
            ->addColumn('action', function ($data) {
                $action = '<div class="btn-group">';
                $action .='<a class="btn btn-sm btn-success btn-simple shadow" href="'.route("get_sop.history",$data->id).'" title="History Pembaca"><i class="fab fa-readme"></i></a>';
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
        $type = Type::orderBy('sequence', 'ASC')->get();
        $jabatan = Jabatan::all();
        $sopcategory = SopRelationCategory::where('id_sop',$sop->id)->get();
        $sopjabatan = SopRelationJabatan::where('id_sop',$sop->id)->get();
        foreach ($sopcategory as $sc) {
            $arrayc[] = $sc->id_category;
        }
        foreach ($sopjabatan as $sj) {
            $arrayj[] = $sj->id_jabatan;
        }
        return view('sop.show')->with('sop', $sop)
                                ->with('page','sop')
                                ->with('type',$type)
                                ->with('jabatan',$jabatan)
                                ->with('category',$category)
                                ->with('sopcategory',$arrayc)
                                ->with('sopjabatan',$arrayj);
    }
    
    public function edit(Sop $sop)
    {   
        $category = Category::all();
        $type = Type::orderBy('sequence', 'ASC')->get();
        $jabatan = Jabatan::all();
        $sopcategory = SopRelationCategory::where('id_sop',$sop->id)->get();
        $sopjabatan = SopRelationJabatan::where('id_sop',$sop->id)->get();
        foreach ($sopcategory as $sc) {
            $arrayc[] = $sc->id_category;
        }
        foreach ($sopjabatan as $sj) {
            $arrayj[] = $sj->id_jabatan;
        }
        // dd($arrayc);        
        return view('sop.edit')->with('page','sop')
                                ->with('sop', $sop)
                                ->with('type',$type)
                                ->with('jabatan',$jabatan)
                                ->with('category',$category)
                                ->with('sopcategory',$arrayc)
                                ->with('sopjabatan',$arrayj)
                                ;
    }
    
    public function update(Request $request, Sop $sop)
    {   
        // dd($request->all());
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
        $sop->slug = strtolower(str_replace(" ","-",$slug));
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
        $sop->jabatan_display = '';
        // $sop->jabatan = $request->jabatan;
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

        SopRelationJabatan::where('id_sop',$sop->id)->delete();
        foreach ($request->jabatan as $jabatan) {
            $relation = new SopRelationJabatan;
            $relation->id_sop = $sop->id;
            $relation->id_jabatan = $jabatan;
            $relation->save();

            $sop->jabatan_display = $sop->jabatan_display.';'.Jabatan::find($jabatan)->name;
            $sop->save();
        }

        $notif = new SopNotification;
        $notif->sop = $sop->id;
        $notif->date = date('Y-m-d H:i:s');
        $notif->keterangan = 'SOP "'.$sop->title.'" telah diedit oleh '.Auth::user()->name.'.';
        $notif->admin = Auth::user()->id;
        $notif->type = 2;
        $notif->save();

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
            $jabatan = SopRelationJabatan::where("id_sop", $id)->delete();

            $category = SopRelationCategory::where("id_sop", $id)->delete();

            $notif = new SopNotification;
            $notif->sop = $id;
            $notif->date = date('Y-m-d H:i:s');
            $notif->keterangan = 'SOP "'.$title.'" telah dihapus oleh '.Auth::user()->name.'.';
            $notif->admin = Auth::user()->id;
            $notif->type = 3;
            $notif->save();

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
        $jabatan_select = null;
        $sop = Sop::leftJoin('type','type.id','sop.type')->where('publish','1')->select('sop.*','type.name as type_name')->orderBy('updated_at','DESC')->paginate(9);
        $category = Category::all();
        $type = Type::orderBy('sequence', 'ASC')->get();
        $jabatan = Jabatan::all();
        // dd($sop);
        return view('sop.home')->with('page','sop_list')->with('sop',$sop)->with('category',$category)->with('type',$type)->with('category_select',$category_select)->with('type_select',$type_select)->with('search',$search)->with('jabatan',$jabatan)->with('jabatan_select',$jabatan_select);
    }

    public function getSop($slug)
    {   
        $sop = Sop::where('slug',$slug)->first();
        $category = SopRelationCategory::join('category','category.id','sop_relation_category.id_category')->select('category.*')->where('id_sop',$sop->id)->get();
        $jabatan = SopRelationJabatan::join('jabatan_sop','jabatan_sop.id','sop_relation_jabatan.id_jabatan')->select('jabatan_sop.*')->where('id_sop',$sop->id)->get();
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
        $jabatan_select = null;
        $query_jabatan = $request->jabatan;
        $query_category = $request->category;
        $query_type = $request->type;

        if($request->category == 'all'){
            $query_category = '%%';
        }else{
            $category_select = Category::find($request->category);
        }
        if($request->jabatan == 'all'){
            $query_jabatan = '%%';
        }else{
            $jabatan_select = Jabatan::find($request->jabatan);
        }
        if($request->type == 'all'){
            $query_type = '%%';
        }else{
            $type_select = Type::find($request->type);
        }

        $sop = Sop::leftJoin('type','type.id','sop.type')
                    ->leftJoin('sop_relation_jabatan','sop_relation_jabatan.id_sop','sop.id')
                    ->leftJoin('sop_relation_category','sop_relation_category.id_sop','sop.id')
                    ->join('category','category.id','sop_relation_category.id_category')
                    ->join('jabatan_sop','jabatan_sop.id','sop_relation_jabatan.id_jabatan')
                    ->where('sop.title','like','%'.$request->search.'%')
                    ->orderBy('sop.updated_at','DESC')
                    ->where('sop_relation_category.id_category','like',$query_category)
                    ->where('sop.type','like',$query_type)
                    ->where('sop_relation_jabatan.id_jabatan','like',$query_jabatan)
                    ->where('sop.publish','1')
                    ->groupBy('sop.id')
                    ->select('sop.*','type.name as type_name')->paginate(15);

        $category = Category::all();
        $type = Type::orderBy('sequence', 'ASC')->get();
        $jabatan = Jabatan::all();
        return view('sop.home')->with('page','sop_list')
                    ->with('sop',$sop)
                    ->with('category',$category)
                    ->with('category_select',$category_select)
                    ->with('type',$type)
                    ->with('type_select',$type_select)
                    ->with('jabatan',$jabatan)
                    ->with('jabatan_select',$jabatan_select)
                    ->with('search',$search);
    }

    public function readSop(Request $request)
    {   
        try {
            if(Auth::user()->role !== 1){
                $model = new SopHistory;
                $model->sop = $request->sop;
                $model->user = Auth::user()->id;
                $model->date = date('Y-m-d H:i:s');
                $model->save();
                return 'you have read the SOP';
            }else{
                return 'you have read the SOP Admin';
            }
        } catch (Exception $e) {
            return 'error';
        }
        
    }

    public function history(Request $request, $id)
    {   
        $data = SopHistory::join('users','users.id', 'sop_history.user')
                            ->select('sop_history.*','users.name as nama','users.username as nip', DB::raw('DATE_FORMAT(sop_history.date, "%d/%m/%Y %H:%i:%s") as date'))
                            ->where('sop_history.sop', $id)
                            ->where('users.role','!=', 1)
                            ->orderBy('sop_history.date', 'DESC')
                            ->get();
        $sop = Sop::find($id);
        // dd($data);        
        return view('sop.history')->with('page','sop')
                                ->with('sop', $sop)
                                ->with('data', $data)
                                ;
    }

    public function historyAll(Request $request)
    {   
        $data = SopHistory::join('users','users.id', 'sop_history.user')
                            ->join('sop','sop.id', 'sop_history.sop')
                            ->join('u1127775_absensi.Absen', 'u1127775_absensi.Absen.NIP', 'users.username')
                            ->select('sop_history.*','users.name as nama','users.username as nip', DB::raw('DATE_FORMAT(sop_history.date, "%d/%m/%Y %H:%i:%s") as date'), 'sop.title as title', 'u1127775_absensi.Absen.Cabang as cabang','u1127775_absensi.Absen.region as region')
                            ->orderBy('sop_history.date', 'DESC')
                            ->where('users.role','!=', 1)
                            ->get();       
        return view('sop.historyall')->with('page','history_sop')
                                ->with('data', $data)
                                ;
    }

    public function printHistoryAll(Request $request)
    {   
        $data = SopHistory::join('users','users.id', 'sop_history.user')
                            ->join('sop','sop.id', 'sop_history.sop')
                            ->join('u1127775_absensi.Absen', 'u1127775_absensi.Absen.NIP', 'users.username')
                            ->select('sop_history.*','users.name as nama','users.username as nip', DB::raw('DATE_FORMAT(sop_history.date, "%d/%m/%Y %H:%i:%s") as date'), 'sop.title as title', 'u1127775_absensi.Absen.Cabang as cabang','u1127775_absensi.Absen.region as region')
                            ->orderBy('sop_history.date', 'DESC')
                            ->where('users.role','!=', 1)
                            ->get();       
        return view('sop.historyallprint')->with('page','history_sop')
                                ->with('data', $data)
                                ;
    }

    public function notification(Request $request)
    {   
        $data = SopNotification::leftJoin('sop', 'sop.id','sop_notification.sop')->orderBy('sop_notification.date','DESC')->select('sop_notification.*',DB::raw('DATE_FORMAT(sop_notification.date, "%d/%m/%Y %H:%i:%s") as date'),'sop.slug')->limit(300)->get();
        // dd($data);    
        return view('sop.notification')->with('page','dashboard')
                                ->with('data', $data)
                                ;
    }
}
