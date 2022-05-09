<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Brand;
use App\KatalogAsset;
use App\KatalogAssetRelation;
use DataTables;
use Auth;
use DB;
use File;
use Image;

class KatalogAssetController extends Controller
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
        return view('katalogasset.index')->with('page', 'katalogasset');
    }

    public function create()
    {
        $brand = Brand::all();
        $bahan = DB::table('u1127775_finance.master_bahan')
            ->where('region', 1)
            ->whereIn('kode', [7, 8])
            ->where('is_deleted', 0)
            ->get();
        return view('katalogasset.create')->with('page', 'katalogasset')->with('brand', $brand)->with('bahan', $bahan);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validatedData = $this->validate($request, [
            'brand' => 'required',
            'master_bahan' => 'required',
            'gambar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:20480'
        ]);

        $cek = KatalogAsset::where('master_bahan', $request->master_bahan)->first();
        if ($cek !== null) {
            return redirect()->route('asset.index')->with('danger', 'Data Katalog gagal ditambahkan! Master Bahan sudah ada di katalog aset.');
        }

        $bahan = DB::table('u1127775_finance.master_bahan')
            ->where('id', $request->master_bahan)
            ->where('region', 1)
            ->whereIn('kode', [7, 8])
            ->where('is_deleted', 0)
            ->first();
        if ($bahan == null) {
            return redirect()->route('asset.index')->with('danger', 'Data Katalog gagal ditambahkan! Master Bahan tidak ditemukan.');
        }

        $image_name = null;
        if ($request->gambar !== null) {
            $image = $request->file('gambar');
            $image_name = time() . 'aset.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images/aset');
            $resize_image = Image::make($image->getRealPath());
            $resize_image->resize(null, 300, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath . '/' . $image_name);
        }

        $aset = new KatalogAsset();
        // $aset->brand = $request->brand;
        $aset->master_bahan = $request->master_bahan;
        $aset->name = $bahan->item;
        $aset->harga_acuan = $bahan->harga_acuan;
        $aset->description = $request->description;
        $aset->sequence = $request->sequence;
        $aset->gambar = $image_name;
        $aset->brand_display = '';
        $aset->save();

        foreach ($request->brand as $brand) {
            $relation = new KatalogAssetRelation;
            $relation->id_katalog_asset = $aset->id;
            $relation->id_brand = $brand;
            $relation->save();

            $aset->brand_display = $aset->brand_display . ';' . Brand::find($brand)->name;
            $aset->save();
        }

        return redirect()->route('asset.index')->with('success', 'Data Katalog Aset ' . $bahan->item . ' berhasil disimpan.');
    }

    public function getData()
    {
        $data = KatalogAsset::leftJoin('katalog_asset_relation', 'katalog_asset_relation.id_katalog_asset', 'katalog_asset.id')->join('brand', 'brand.id', 'katalog_asset_relation.id_brand')->select('katalog_asset.*')->orderBy('katalog_asset.sequence', 'ASC')->groupBy('katalog_asset.id')->get();
        return $this->datatable($data);
    }

    public function datatable($data)
    {
        return Datatables::of($data)
            ->addColumn('action', function ($data) {
                $action = '<div class="btn-group">';
                $action .= '<a class="btn btn-sm btn-info btn-simple shadow" href="' . route("asset.show", $data->id) . '" title="Info"><i class="fa fa-search"></i></a>';
                $action .= '<a class="btn btn-sm btn-warning btn-simple shadow" href="' . route("asset.edit", $data->id) . '" title="Edit"><i class="fa fa-edit"></i></a>';
                $action .= '<a href="' . route("asset.delete", $data->id) . '" class="btn btn-sm btn-danger btn-simple shadow assetDelete" title="Delete" data-id="' . $data->id . '"><i class="fa fa-trash"></i></a>';
                if ($action == '') {
                    $action .= 'None';
                }
                $action .= '</div>';
                return $action;
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
    }

    public function show(KatalogAsset $asset)
    {
        $brand = Brand::all();
        $bahan = DB::table('u1127775_finance.master_bahan')
            ->where('region', 1)
            ->whereIn('kode', [7, 8])
            ->where('is_deleted', 0)
            ->get();
        $relationbrand = KatalogAssetRelation::where('id_katalog_asset', $asset->id)->get();
        foreach ($relationbrand as $rb) {
            $arrayrb[] = $rb->id_brand;
        }
        return view('katalogasset.show')
            ->with('asset', $asset)
            ->with('page', 'katalogasset')
            ->with('bahan', $bahan)
            ->with('relationbrand', $arrayrb)
            ->with('brand', $brand);
    }

    public function edit(KatalogAsset $asset)
    {
        $brand = Brand::all();
        $bahan = DB::table('u1127775_finance.master_bahan')
            ->where('region', 1)
            ->whereIn('kode', [7, 8])
            ->where('is_deleted', 0)
            ->get();
        $relationbrand = KatalogAssetRelation::where('id_katalog_asset', $asset->id)->get();
        foreach ($relationbrand as $rb) {
            $arrayrb[] = $rb->id_brand;
        }
        return view('katalogasset.edit')
            ->with('page', 'katalogasset')
            ->with('asset', $asset)
            ->with('brand', $brand)
            ->with('relationbrand', $arrayrb)
            ->with('bahan', $bahan);
    }

    public function update(Request $request, KatalogAsset $asset)
    {
        // dd($request->all());
        $validatedData = $this->validate($request, [
            // 'brand' => 'required',
            'master_bahan' => 'required',
            'gambar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:20480'
        ]);

        $cek = KatalogAsset::where('master_bahan', $request->master_bahan)->whereNotIn('id', [$asset->id])->first();
        if ($cek !== null) {
            return redirect()->route('asset.index')->with('danger', 'Data Katalog gagal diupdate! Master Bahan sudah ada di katalog aset.');
        }

        $bahan = DB::table('u1127775_finance.master_bahan')
            ->where('id', $request->master_bahan)
            ->where('region', 1)
            ->whereIn('kode', [7, 8])
            ->where('is_deleted', 0)
            ->first();
        if ($bahan == null) {
            return redirect()->route('asset.index')->with('danger', 'Data Katalog gagal ditambahkan! Data Katalog gagal diupdate! Master Bahan tidak ditemukan..');
        }
        $image_name = null;
        if ($request->gambar !== null) {
            $image = $request->file('gambar');
            $image_name = time() . 'aset.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images/aset');
            $resize_image = Image::make($image->getRealPath());
            $resize_image->resize(null, 300, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath . '/' . $image_name);
        }
        if ($image_name != null) {
            if ($asset->gambar !== null) {
                $image_path = public_path('/images/aset/' . $asset->gambar);
                if (File::exists($image_path)) {
                    File::delete($image_path);
                }
            }
            $asset->gambar = $image_name;
        }
        $asset->master_bahan = $request->master_bahan;
        $asset->name = $bahan->item;
        $asset->harga_acuan = $bahan->harga_acuan;
        $asset->description = $request->description;
        $asset->sequence = $request->sequence;
        $asset->brand_display = '';
        $asset->save();

        KatalogAssetRelation::where('id_katalog_asset', $asset->id)->delete();
        foreach ($request->brand as $brand) {
            $relation = new KatalogAssetRelation;
            $relation->id_katalog_asset = $asset->id;
            $relation->id_brand = $brand;
            $relation->save();

            $asset->brand_display = $asset->brand_display . ';' . Brand::find($brand)->name;
            $asset->save();
        }

        return redirect()->route('asset.index')->with('success', 'Data Katalog Aset ' . $bahan->item . ' berhasil diupdate.');
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $asset = KatalogAsset::find($id);
            $gambar = $asset->gambar;
            $name = $asset->name;
            KatalogAssetRelation::where("id_katalog_asset", $id)->delete();
            $asset->delete();
            if ($gambar !== null) {
                $image_path = public_path('/images/aset/' . $gambar);
                if (File::exists($image_path)) {
                    File::delete($image_path);
                }
            }
            DB::commit();
            return response()->json([
                'message' => 'Data Katalog Aset "' . $name . '" berhasil dihapus!',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Data Katalog Aset "' . $name . '" gagal dihapus!',
                'type' => 'danger',
            ]);
        }
    }

    public function getListAsset()
    {
        // dd($request->all());
        $search = null;
        $brand_select = null;
        $asset = KatalogAsset::leftJoin('katalog_asset_relation', 'katalog_asset_relation.id_katalog_asset', 'katalog_asset.id')->join('brand', 'brand.id', 'katalog_asset_relation.id_brand')->select('katalog_asset.*')->orderBy('katalog_asset.sequence', 'ASC')->paginate(12);
        $brand = Brand::all();

        return view('katalogasset.home')->with('page', 'asset_list')->with('asset', $asset)->with('brand', $brand)->with('brand_select', $brand_select)->with('search', $search);
    }

    public function getAsset($id)
    {
        $asset = KatalogAsset::leftJoin('katalog_asset_relation', 'katalog_asset_relation.id_katalog_asset', 'katalog_asset.id')->join('brand', 'brand.id', 'katalog_asset_relation.id_brand')->select('katalog_asset.*')->orderBy('katalog_asset.sequence', 'ASC')->where('katalog_asset.id', $id)->first();
        if ($asset == null) {
            return redirect()->route('asset_list.index')->with('danger', 'Katalog Aset yang dicari tidak ditemukan.');
        }
        return view('katalogasset.post')->with('page', 'asset_list')->with('asset', $asset);
    }

    public function getSearch(Request $request)
    {
        // dd($request->all());
        $search = $request->search;
        $brand_select = null;
        $query_brand = $request->brand;

        if ($request->brand == 'all') {
            $query_brand = '%%';
        } else {
            $brand_select = Brand::find($request->brand);
        }

        $asset = KatalogAsset::leftJoin('katalog_asset_relation', 'katalog_asset_relation.id_katalog_asset', 'katalog_asset.id')
            ->join('brand', 'brand.id', 'katalog_asset_relation.id_brand')
            ->select('katalog_asset.*')
            ->where('katalog_asset.name', 'like', '%' . $request->search . '%')
            ->where('katalog_asset_relation.id_brand', 'like', $query_brand)
            ->orderBy('katalog_asset.sequence', 'ASC')
            ->paginate(12);

        $brand = Brand::all();
        return view('katalogasset.home')->with('page', 'asset_list')
            ->with('asset', $asset)
            ->with('brand', $brand)
            ->with('brand_select', $brand_select)
            ->with('search', $search);
    }
}
