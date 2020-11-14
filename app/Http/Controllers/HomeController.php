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
        return view('home')->with('page','dashboard')->with('karyawan',$karyawan);
    }
}
