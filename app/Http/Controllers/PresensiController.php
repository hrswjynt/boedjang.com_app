<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use App\Karyawan;
use App\AttLogCenter;
use App\AttLogMesin;
use App\PresensiOnline;
use Auth;
use DB;
use Image;

class PresensiController extends Controller
{

    function __construct()
    {

    }

    public function table()
    {   
        return view('presensi.table')->with('page','presensi');
    }

    public function getData(){
        $data = PresensiOnline::where('nip',Auth::user()->username)->orderBy('date', 'DESC')->get();
        return Datatables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    public function index(Request $request)
    {   
        $karyawan = Karyawan::where('NIP',Auth::user()->username)->first();
        $data = PresensiOnline::where('nip',Auth::user()->username)
                                ->whereDate('date', date('Y-m-d'))
                                ->where('status', 0)
                                ->count();
        $cek_spam = false;
        if($data > 3){
            $cek_spam = true;
        }
        return view('presensi.index')->with('page','presensi')->with('karyawan',$karyawan)->with('cek_spam',$cek_spam);
    }

    public function store(Request $request)
    {   
        // dd($request->all());
        $karyawan = Karyawan::where('NIP',Auth::user()->username)->first();
        // $cek_masuk = PresensiOnline::getPresensi(1)->first();
        // $cek_pulang = PresensiOnline::getPresensi(2)->first();
        // if($request->jenis === 1 || $request->jenis === '1'){
        //     if($cek_masuk !== null){
        //         return redirect()->route('absensi.index')->with('danger','Gagal input presensi online, data presensi masuk hari ini '.$karyawan->NAMA.' telah ada di database.');
        //     }
        // }else if($request->jenis === 2 || $request->jenis === '2'){
        //     if($cek_pulang !== null){
        //         return redirect()->route('absensi.index')->with('danger','Gagal input presensi online, data presensi keluar hari ini '.$karyawan->NAMA.' telah ada di database.');
        //     }
        // }
        
        $img = $request->image;
        $fileName = $karyawan->NIP.uniqid() . '.png';
        list($type, $data) = explode(';', $img);
        list(, $data)      = explode(',', $data);
        $data = base64_decode($data);
        $destinationPath = public_path('/images/presensi');
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
        $presensi->region = $karyawan->region;
        $presensi->cabang = $karyawan->Cabang;
        $presensi->status = 0;
        if($presensi->save()){
            if($presensi->status == 1){
                $log = new AttLogMesin;
                $log->sn = 'presensi_online';
                $log->scan_date = $presensi->date;
                $log->pin = $presensi->nip;
                $log->save();
            }
        }
        

        return redirect()->route('presensi.table')->with('success','Data presensi '.$karyawan->NAMA.' berhasil disimpan.');
    }
    
}
