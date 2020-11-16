<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\AttLogCenter;
use App\Karyawan;
use Auth;
use DB;
use DataTables;

class AbsensiController extends Controller
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
        $karyawan = Karyawan::where('NIP',Auth::user()->username)->first();
        return view('absensi.index')->with('page','absensi')->with('karyawan',$karyawan);
    }

    public function getData(){
        if(date('d') >= 16){
            $date1 = date('Y-m-16');
            $date2 = date("Y-m-d", strtotime("+1 month", strtotime(date('15-m-Y'))));
        }else{
            $date1= date("Y-m-d", strtotime("-1 month", strtotime(date('16-m-Y'))));
            $date2= date('Y-m-15');
        }
        $data = AttLogCenter::orderBy('tgl_absen','DESC')->whereBetween('tgl_absen', [$date1,$date2])
                    ->where('nip', Auth::user()->username)->get();
        return Datatables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    public function getDataSearch($sdate, $edate){
        // dd($nama);
        $startdate = date_create($sdate);
        $startdate = date_format($startdate, 'Y-m-d');

        $enddate = date_create($edate);
        $enddate = date_format($enddate, 'Y-m-d');

        $data = AttLogCenter::whereBetween('tgl_absen',[$startdate,$enddate])->orderBy('tgl_absen','DESC')->where('nip', Auth::user()->username)->get();
        return Datatables::of($data)
            ->addIndexColumn()
            ->make(true);
    }
}
