<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use DataTables;
use Auth;
use DB;
use File;
use Image;

class ItemController extends Controller
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
        return view('item.index')->with('page','item');
    }

    public function create(){
        return view('item.create')->with('page','item');
    }

    public function store(Request $request)
    {   
        $validatedData = $this->validate($request, [
            'name' => 'required',
            'gambar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:20480'
        ]);
        $image_name = null;
        if($request->gambar !== null){
            $image = $request->file('gambar');
            $image_name = time() . 'item.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images/item');
            $resize_image = Image::make($image->getRealPath());
            $resize_image->resize(null, 300, function($constraint){
                $constraint->aspectRatio();
            })->save($destinationPath . '/' . $image_name);
        }

        $item = new Item;
        $slug = str_replace("/","",$request->name);
        $item->slug = strtolower(str_replace(" ","-",$slug));
        $item->name = $request->name;
        $item->content = $request->content;
        $item->gambar = $image_name;
        $item->publish = 0;
        $item->save();

        return redirect()->route('item.index')->with('success','Data Barang & Bahan '.$request->name.' berhasil disimpan.');
    }

    public function getData(){
        $data = Item::all();
        return $this->datatable($data);
    }

    public function datatable($data)
    {
        return Datatables::of($data)
            ->addColumn('action', function ($data) {
                $action = '<div class="btn-group">';
                $action.= '<a class="btn btn-sm btn-info btn-simple shadow" href="'.route("item.show",$data->id).'" title="Info"><i class="fa fa-search"></i></a>';
                $action .='<a class="btn btn-sm btn-warning btn-simple shadow" href="'.route("item.edit",$data->id).'" title="Edit"><i class="fa fa-edit"></i></a>';
                $action .='<a href="'.route("item.delete",$data->id).'" class="btn btn-sm btn-danger btn-simple shadow itemDelete" title="Delete" data-id="'.$data->id.'"><i class="fa fa-trash"></i></a>';
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
    
    public function show(Item $item)
    {   
        return view('item.show')->with('item', $item)
                                ->with('page','item');
    }
    
    public function edit(Item $item)
    {      
        return view('item.edit')->with('page','item')
                                ->with('item', $item)
                                ;
    }
    
    public function update(Request $request, Item $item)
    {   
        // dd($request->all());
        $validatedData = $this->validate($request, [
            'name'         => 'required',
        ]);
        $image_name = null;
        if($request->gambar !== null){
            $image = $request->file('gambar');
            $image_name = time() . 'item.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images/item');
            $resize_image = Image::make($image->getRealPath());
            $resize_image->resize(null, 300, function($constraint){
                $constraint->aspectRatio();
            })->save($destinationPath . '/' . $image_name);
        }
        $slug = str_replace("/","",$request->name);
        $item->slug = strtolower(str_replace(" ","-",$slug));
        $item->name = $request->name;
        $item->content = $request->content;
        $item->publish = $request->publish;
        if($image_name != null){
            if($item->gambar !==null){
                $image_path = public_path('/images/item/'.$item->gambar);
                if(File::exists($image_path)) {
                    File::delete($image_path);
                }
            }
            $item->gambar = $image_name;
        }
        $item->save();
        return redirect()->route('item.index')->with('success','Data Barang & Bahan '.$request->name.' berhasil diupdate.');
   }

   public function delete($id){
        DB::beginTransaction();
        try{
            $item = Item::find($id);
            $name = $item->name;
            $gambar = $item->gambar;
            $item->delete();
            if($gambar !==null){
                $image_path = public_path('/images/item/'.$gambar);
                if(File::exists($image_path)) {
                    File::delete($image_path);
                }
            }

            DB::commit();
            return response()->json([
                'message' => 'Barang & Bahan "'.$name.'" berhasil dihapus!',
                'type'=> 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Barang & Bahan "'.$name.'" gagal dihapus!',
                'type'=> 'danger',
            ]);
        }
    }

    public function getList()
    {   
        $search = null;
        $item = Item::where('publish','1')->paginate(9);
        return view('item.home')->with('page','item_list')->with('item',$item)->with('search',$search);
    }

    public function getItem($slug)
    {   
        $item = Item::where('slug',$slug)->first();
        if($item == null){
            return redirect()->route('item_list.index')->with('danger','Barang & Bahan yang dicari tidak ditemukan.');
        }
        return view('item.post')->with('page','item_list')->with('item',$item);
    }

    public function getSearch(Request $request){
        // dd($request->all());
        $search = $request->search;

        $item = Item::where('item.name','like','%'.$request->search.'%')
                    ->orderBy('item.updated_at','DESC')
                    ->where('item.publish','1')
                    ->groupBy('item.id')
                    ->paginate(9);
        return view('item.home')->with('page','item_list')
                    ->with('item',$item)
                    ->with('search',$search);
    }

}
