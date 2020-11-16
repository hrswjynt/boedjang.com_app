<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\AttLogCenter;
use App\Karyawan;
use Auth;
use DB;

class HomeController extends Controller
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
        
        $date1= date("Y-m-d", strtotime("-2 month"));
        $date2= date('Y-m-d');
        $jumlah_telat = AttLogCenter::whereBetween('tgl_absen', [$date1,$date2])
                                    ->where('nip', Auth::user()->username)
                                    ->where('pot_masuk','>',0)
                                    ->where('pot_masuk','!=','')
                                    ->whereNotNull('pot_masuk')
                                    ->count();
        $total_telat = AttLogCenter::whereBetween('tgl_absen', [$date1,$date2])
                                    ->where('nip', Auth::user()->username)
                                    ->where('pot_masuk','>',0)
                                    ->where('pot_masuk','!=','')
                                    ->whereNotNull('pot_masuk')
                                    ->sum('pot_masuk');
        return view('home')->with('page','dashboard')->with('karyawan',$karyawan)->with('jumlah_telat',$jumlah_telat)->with('total_telat',$total_telat);
    }

    public function datadiri()
    {   
        $karyawan = Karyawan::where('NIP',Auth::user()->username)->first();
        return view('datadiri')->with('page','datadiri')->with('karyawan',$karyawan);
    }
}
