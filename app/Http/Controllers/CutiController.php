<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\FormCuti;
use App\Karyawan;
use App\User;
use DataTables;
use Auth;
use DB;

class CutiController extends Controller
{

    function __construct()
    {

    }

    public function pengajuan()
    {   
        $karyawan = Karyawan::where('NIP',Auth::user()->username)->first();
        return view('formcuti.pengajuan')->with('page','formcuti')->with('karyawan',$karyawan);
    }

    public function pengajuanpost(Request $request)
    {   
        // dd($request->all());
        request()->validate([
            'tanggal_mulai' => 'required',
        ]);
        $formcuti = FormCuti::where('nip', Auth::user()->username)->where('status', 'Menunggu')->first();
        $karyawan = Karyawan::where('NIP', Auth::user()->username)->first();
        if($formcuti !== null){
            $message_type = 'danger';
            $message = 'Data Cuti "'.$karyawan->NIP ." - ".$karyawan->NAMA .'" gagal ditambahkan. Data cuti sebelumnya belum di proses.';
            return redirect()->route('formcuti.pengajuan')->with($message_type,$message);
        }
        DB::beginTransaction();
        $model = new FormCuti;
        $model->nip = $karyawan->NIP;
        $model->nama = $karyawan->NAMA;
        $model->lokasi_cuti = $request->lokasi_cuti;
        $model->tgl_mulai = $request->tanggal_mulai;
        $model->tgl_akhir = $request->tanggal_akhir;
        $model->off = $request->jumlah_off;
        $model->status = 'Menunggu';

        if(!$model->save()) {
            DB::rollback();
            $message_type = 'danger';
            $message = 'Data Cuti "'.$karyawan->NIP ." - ".$karyawan->NAMA .'" gagal ditambahkan.';
            return redirect()->route('formcuti.index')->with($message_type,$message);
        }else{
            DB::commit();
            $message_type = 'success';
            $message = 'Data Cuti "'.$karyawan->NIP ." - ".$karyawan->NAMA  .'" berhasil ditambahkan.';
            return redirect()->route('formcuti.index')->with($message_type,$message);
        }
        
    }

    public function index()
    {   
        return view('formcuti.index')->with('page','formcuti');
    }

    public function getData(){
        $data = FormCuti::where('nip',Auth::user()->username)->get();
        return Datatables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

}
