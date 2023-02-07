<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use App\Karyawan;
use App\PresensiOnline;
use Auth;
use DB;
use Image;

class PresensiController extends Controller
{

    function __construct()
    {

    }

    public function index(Request $request)
    {   
        $karyawan = Karyawan::where('NIP',Auth::user()->username)->first();
        return view('presensi.index')->with('page','presensi')->with('karyawan',$karyawan);
    }

    public function store(Request $request)
    {   
        // dd($request->all());
        $karyawan = Karyawan::where('NIP',Auth::user()->username)->first();

        $img = $request->image;
        $fileName = $karyawan->NIP.uniqid() . '.png';
        list($type, $data) = explode(';', $img);
        list(, $data)      = explode(',', $data);
        $data = base64_decode($data);
        $destinationPath = public_path('/images/profile');
        $resize_image = Image::make($data);
        $resize_image->save($destinationPath . '/' . $fileName);
        $coor = explode(',&,', $request->lokasi);
        // dd($coor);
        $presensi = new PresensiOnline;
        $presensi->date = date('Y-m-d H:i:s');
        $presensi->ip = $request->ip;
        $presensi->nip = $karyawan->NIP;
        $presensi->gambar = $fileName;
        $presensi->latitude = $coor[0];
        $presensi->longitude = $coor[1];
        $presensi->jenis_absen = $request->jenis;
        $presensi->status = 1;
        $presensi->save();

        return redirect()->route('absensi.index')->with('success','Data presensi '.$karyawan->NAMA.' berhasil disimpan.');
    }
    


}
