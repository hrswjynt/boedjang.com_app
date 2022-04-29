<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Brand;
use App\KatalogAsset;
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
        $aset->brand = $request->brand;
        $aset->master_bahan = $request->master_bahan;
        $aset->name = $bahan->item;
        $aset->harga_acuan = $bahan->harga_acuan;
        $aset->description = $request->description;
        $aset->sequence = $request->sequence;
        $aset->gambar = $image_name;
        $aset->save();

        return redirect()->route('asset.index')->with('success', 'Data Katalog Aset ' . $bahan->item . ' berhasil disimpan.');
    }

    public function getData()
    {
        $data = KatalogAsset::join('brand', 'brand.id', 'katalog_asset.brand')->select('katalog_asset.*', 'brand.name as brand_name')->get();
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
        return view('katalogasset.show')
            ->with('asset', $asset)
            ->with('page', 'katalogasset')
            ->with('bahan', $bahan)
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
        return view('katalogasset.edit')
            ->with('page', 'katalogasset')
            ->with('asset', $asset)
            ->with('brand', $brand)
            ->with('bahan', $bahan);
    }

    public function update(Request $request, KatalogAsset $asset)
    {
        // dd($request->all());
        $validatedData = $this->validate($request, [
            'brand' => 'required',
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
        $asset->brand = $request->brand;
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
        $asset->save();

        return redirect()->route('asset.index')->with('success', 'Data Katalog Aset ' . $bahan->item . ' berhasil diupdate.');
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $asset = KatalogAsset::find($id);
            $gambar = $asset->gambar;
            $name = $asset->name;
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
        $asset = KatalogAsset::join('brand', 'brand.id', 'katalog_asset.brand')->select('katalog_asset.*', 'brand.name as brand_name')->orderBy('katalog_asset.sequence', 'ASC')->paginate(12);
        $brand = Brand::all();

        return view('katalogasset.home')->with('page', 'asset_list')->with('asset', $asset)->with('brand', $brand)->with('brand_select', $brand_select)->with('search', $search);
    }

    public function getAsset($id)
    {
        $asset = KatalogAsset::join('brand', 'brand.id', 'katalog_asset.brand')->where('katalog_asset.id', $id)
            ->select('katalog_asset.*', 'brand.name as brand_name')->first();
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

        $asset = KatalogAsset::join('brand', 'brand.id', 'katalog_asset.brand')
            ->where('katalog_asset.name', 'like', '%' . $request->search . '%')
            ->orderBy('katalog_asset.sequence', 'ASC')
            ->where('katalog_asset.brand', 'like', $query_brand)
            ->select('katalog_asset.*', 'brand.name as brand_name')->paginate(12);

        $brand = Brand::all();
        return view('katalogasset.home')->with('page', 'asset_list')
            ->with('asset', $asset)
            ->with('brand', $brand)
            ->with('brand_select', $brand_select)
            ->with('search', $search);
    }
}
