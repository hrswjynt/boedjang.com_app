<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\FeedbackDataHeader;
use App\FeedbackDataDetail;
use App\Karyawan;
use App\User;
use DataTables;
use Auth;
use DB;

class FeedbackController extends Controller
{

    function __construct()
    {
    }

    public function pengajuan()
    {
        $karyawan = Karyawan::where('NIP', Auth::user()->username)->first();
        // $atasan = Karyawan::where(function($query){
        //     $query->orWhere('Jabatan', 'like', '%Supervisor%');
        //     $query->orWhere('Jabatan', 'like', '%Manager%');
        //     $query->orWhere('Jabatan', 'like', '%Manajer%');
        //     $query->orWhere('Jabatan', 'like', '%Direktur%');
        //     $query->orWhere('Jabatan', 'like', '%Leader%');
        //     $query->orWhere('Cabang', 'like', '%HeadOffice%');
        $atasan = Karyawan::where('region', $karyawan->region)->whereNotIn('Status', ['Resign'])->get();
        $feedback = DB::table('feedback')->join('feedback_kategori', 'feedback_kategori.id', 'feedback.kategori')
            ->select('feedback.*', 'feedback_kategori.nama as kategori_nama')
            ->get();
        return view('feedback.pengajuan')->with('page', 'feedback')->with('karyawan', $karyawan)->with('feedback', $feedback)->with('atasan', $atasan);
    }

    public function pengajuanpost(Request $request)
    {
        // dd($request->all());
        // request()->validate([
        //     'tgl' => 'required',
        // ]);
        // dd($request->radio);
        $karyawan = Karyawan::where('NIP', Auth::user()->username)->first();
        $date1 = date("Y-m-01 H:i:s", strtotime("-1 day", strtotime(date('Y-m-d H:i:s'))));
        $date2 = date('Y-m-t H:i:s');
        $checkfeedback = FeedbackDataHeader::where('user', Auth::user()->id)
            ->where('atasan', $request->atasan)
            ->whereBetween('tgl', [$date1, $date2])
            ->first();
        // dd($checkfeedback);
        if ($checkfeedback !== null) {
            $message_type = 'danger';
            $message = 'Data Feedback Atasan "' . $karyawan->NIP . " - " . $karyawan->NAMA . '" gagal ditambahkan. Karyawan telah mengisi data dengan atasan yang sama sebelumnya.';
            return redirect()->route('feedback.index')->with($message_type, $message);
        }
        DB::beginTransaction();
        $header = new FeedbackDataHeader;
        $header->tgl = date('Y-m-d H:i:s');
        $header->user = Auth::user()->id;
        $header->outlet_name = $karyawan->Cabang;
        $header->alasan1 = $request->alasan1;
        $header->alasan2 = $request->alasan2;
        $header->alasan3 = $request->alasan3;
        $header->atasan = $request->atasan;
        $header->puas = $request->puas;
        $header->save();

        if (!$header->save()) {
            DB::rollback();
            $message_type = 'danger';
            $message = 'Data Feedback Atasan "' . $karyawan->NIP . " - " . $karyawan->NAMA . '" gagal ditambahkan.';
            return redirect()->route('feedback.index')->with($message_type, $message);
        } else {
            foreach ($request->radio as $index => $value) {
                // dd($value.' '.$index);
                $detail = new FeedbackDataDetail;
                $detail->header_id = $header->id;
                $detail->feedback = $index;
                $detail->poin = $value;
                $detail->save();
            }
            DB::commit();
            $message_type = 'success';
            $message = 'Data Feedback Atasan "' . $karyawan->NIP . " - " . $karyawan->NAMA  . '" berhasil ditambahkan.';
            return redirect()->route('feedback.index')->with($message_type, $message);
        }
    }

    public function index()
    {
        return view('feedback.index')->with('page', 'feedback');
    }

    public function getData()
    {
        $data = FeedbackDataHeader::join('users', 'users.id', 'feedback_data_header.user')->join('users as atasan', 'atasan.username', 'feedback_data_header.atasan')->where('feedback_data_header.user', Auth::user()->id)->select('feedback_data_header.*', 'users.name', 'atasan.name as atasan_name')->get();
        return Datatables::of($data)
            ->addIndexColumn()
            ->make(true);
    }


    public function indexLaporan()
    {
        $feedback = DB::table('feedback')->get();
        $date1 = date("Y-m-d", strtotime("-1 month", strtotime(date('Y-m-01'))));
        $date2 = date('Y-m-t');

        $data = DB::select(DB::raw("SELECT fdh.id,(CASE WHEN fdd.poin = 1 THEN 'Sangat Tidak Setuju' WHEN fdd.poin = 2 THEN 'Tidak Setuju' WHEN fdd.poin = 3 THEN 'Setuju' ELSE 'Sangat Setuju' END) as poin_nama, fdd.header_id, fdh.tgl, fdh.`user`, u.`name`, u.username as nip, fdh.outlet_name as outlet_nama, fdh.atasan, u2.`name` as atasan_nama, fdh.alasan1, fdh.alasan2, fdh.alasan3, fk.nama as kategori_nama, fdd.feedback as feedback_id, f.isi, fdd.poin, fdh.puas, absen.Cabang as cabang, absen.Jabatan as jabatan FROM u1127775_boedjang.feedback_data_detail fdd INNER JOIN u1127775_boedjang.feedback_data_header fdh ON fdd.header_id = fdh.id INNER JOIN u1127775_boedjang.feedback f on f.id = fdd.feedback INNER JOIN u1127775_boedjang.feedback_kategori fk on fk.id = f.kategori INNER JOIN u1127775_boedjang.users u on u.id = fdh.`user` INNER JOIN u1127775_boedjang.users u2 on u2.username = fdh.atasan INNER JOIN u1127775_absensi.Absen as absen on absen.NIP = u.username where date(fdh.tgl) between '" . $date1 . "' and '" . $date2 . "' ORDER BY fdh.id"));
        // dd(($data));
        return view('feedbacklaporan.index')->with('page', 'feedbacklaporan')->with('feedback', $feedback)->with('data', $data)->with('date1', $date1)->with('date2', $date2);
    }

    public function indexSearchLaporan(Request $request)
    {
        // dd($request->all());
        $startdate = date_create($request->sdate);
        $startdate = date_format($startdate, 'Y-m-d');

        $enddate = date_create($request->edate);
        $enddate = date_format($enddate, 'Y-m-d');
        $karyawan = Karyawan::where('NIP', Auth::user()->username)->first();
        $feedback = DB::table('feedback')->get();
        $data = DB::select(DB::raw("SELECT fdh.id,(CASE WHEN fdd.poin = 1 THEN 'Sangat Tidak Setuju' WHEN fdd.poin = 2 THEN 'Tidak Setuju' WHEN fdd.poin = 3 THEN 'Setuju' ELSE 'Sangat Setuju' END) as poin_nama, fdd.header_id, fdh.tgl, fdh.`user`, u.`name`, u.username as nip, fdh.outlet_name as outlet_nama, fdh.atasan, u2.`name` as atasan_nama, fdh.alasan1, fdh.alasan2, fdh.alasan3, fk.nama as kategori_nama, fdd.feedback as feedback_id, f.isi, fdd.poin, fdh.puas, absen.Cabang as cabang, absen.Jabatan as jabatan FROM u1127775_boedjang.feedback_data_detail fdd INNER JOIN u1127775_boedjang.feedback_data_header fdh ON fdd.header_id = fdh.id INNER JOIN u1127775_boedjang.feedback f on f.id = fdd.feedback INNER JOIN u1127775_boedjang.feedback_kategori fk on fk.id = f.kategori INNER JOIN u1127775_boedjang.users u on u.id = fdh.`user` INNER JOIN u1127775_boedjang.users u2 on u2.username = fdh.atasan INNER JOIN u1127775_absensi.Absen as absen on absen.NIP = u.username where date(fdh.tgl) between '" . $startdate . "' and '" . $enddate . "' ORDER BY fdh.id"));

        return view('feedbacklaporan.index')->with('page', 'feedbacklaporan')->with('feedback', $feedback)->with('data', $data)->with('date1', $startdate)->with('date2', $enddate);
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $fdh = FeedbackDataHeader::find($id);

            $name = $fdh->id . " " . $fdh->outlet_name;
            // dd($fdd);
            if (FeedbackDataDetail::where('header_id', $id)->delete()) {
                $fdh->delete();
            }

            DB::commit();
            return response()->json([
                'message' => 'Feedback ID "' . $name . '" berhasil dihapus!',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            // DB::rollback();
            return response()->json([
                'message' => 'Feedback ID "' . $name . '" gagal dihapus!',
                'type' => 'danger',
            ]);
        }
    }
}
