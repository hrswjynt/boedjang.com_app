<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Karyawan;
use App\User;
use DataTables;
use Auth;
use DB;

class TicketController extends Controller
{

    function __construct()
    {

    }

    public function pengajuan()
    {   
        $karyawan = Karyawan::where('NIP',Auth::user()->username)->first();
        return view('ticket.pengajuan')->with('page','ticketpengajuan')->with('karyawan',$karyawan);
    }

    public function pengajuanpost(Request $request)
    {   
        // dd($request->all());
        request()->validate([
            'tanggal_mulai' => 'required',
        ]);
        DB::beginTransaction();
        $karyawan = Karyawan::where('NIP',Auth::user()->username)->first();
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
            return redirect()->route('formcuti.pengajuan')->with($message_type,$message);
        }else{
            DB::commit();
            $message_type = 'success';
            $message = 'Data Cuti "'.$karyawan->NIP ." - ".$karyawan->NAMA  .'" berhasil ditambahkan.';
            return redirect()->route('formcuti.pengajuan')->with($message_type,$message);
        }
        
    }

    public function index()
    {   
        return view('ticket.index')->with('page','ticket');
    }

    public function getData(){
        // $data = FormCuti::where('nip',Auth::user()->username)->get();
        $headers = [
            'Authorization: 1HbTMNR9/fJjoLiFTwUJdui0V68EOeNPToX0/zwTg1r8ih4fuavNy',
            'Content-Type: application/json',
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://192.168.100.32:1438/tickets');
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );
        // curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);  
        $response = curl_exec($ch);
        $res = json_decode($response);
        // dd($res);
        return Datatables::of($res)
            ->addIndexColumn()
            ->make(true);
    }

}
