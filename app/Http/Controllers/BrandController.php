<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Brand;
use App\KatalogAsset;
use DataTables;
use Auth;
use DB;

class BrandController extends Controller
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
        return view('brand.index')->with('page', 'brand');
    }

    public function create()
    {
        return view('brand.create')->with('page', 'brand');
    }

    public function store(Request $request)
    {
        $validatedData = $this->validate($request, [
            'name'         => 'required',
        ]);
        $brand = new Brand;
        $brand->name = $request->name;
        $brand->description = $request->description;
        $brand->save();
        return redirect()->route('brand.index')->with('success', 'Data Brand ' . $request->name . ' berhasil disimpan.');
    }

    public function getData()
    {
        $data = Brand::all();
        return $this->datatable($data);
    }

    public function datatable($data)
    {
        return Datatables::of($data)
            ->addColumn('action', function ($data) {
                $action = '<div class="btn-group">';
                $action .= '<a class="btn btn-sm btn-info btn-simple shadow" href="' . route("brand.show", $data->id) . '" title="Info"><i class="fa fa-search"></i> Info </a>';
                $action .= '<a class="btn btn-sm btn-warning btn-simple shadow" href="' . route("brand.edit", $data->id) . '" title="Edit"><i class="fa fa-edit"></i> Edit</a>';
                $action .= '<a href="' . route("brand.delete", $data->id) . '" class="btn btn-sm btn-danger btn-simple shadow brandDelete" title="Delete" data-id="' . $data->id . '"><i class="fa fa-trash"></i> Hapus</a>';
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

    public function show(Brand $brand)
    {
        return view('brand.show')->with('brand', $brand)->with('page', 'brand');
    }

    public function edit(Brand $brand)
    {
        return view('brand.edit')->with('page', 'brand')
            ->with('brand', $brand);
    }

    public function update(Request $request, Brand $brand)
    {
        $validatedData = $this->validate($request, [
            'name'         => 'required',
        ]);
        $brand->name = $request->name;
        $brand->description = $request->description;
        $brand->save();
        return redirect()->route('brand.index')->with('success', 'Data Brand ' . $request->name . ' berhasil diupdate.');
    }

    public function delete($id)
    {
        DB::beginTransaction();
        $brand = Brand::find($id);
        $name = $brand->name;
        if (KatalogAsset::where('brand', $id)->first() !== null) {
            return response()->json([
                'message' => 'Brand "' . $name . '" gagal dihapus, Brand telah digunakan.',
                'type' => 'danger',
            ]);
        }
        try {
            $brand->delete();
            DB::commit();
            return response()->json([
                'message' => 'Brand "' . $name . '" berhasil dihapus!',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Brand "' . $name . '" gagal dihapus!',
                'type' => 'danger',
            ]);
        }
    }
}
