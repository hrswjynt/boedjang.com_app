<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\AttLogCenter;
use App\Karyawan;
use App\SanksiPegawai;
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
        $sanksi = SanksiPegawai::where('status',1)->where('nip',$karyawan->NIP)->first();
        if(date('d') >= 16){
            $date1 = date('Y-m-16');
            $date2 = date("Y-m-d", strtotime("+1 month", strtotime(date('15-m-Y'))));
        }else{
            $date1= date("Y-m-d", strtotime("-1 month", strtotime(date('16-m-Y'))));
            $date2= date('Y-m-15');
        }

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

        if(Auth::user()->role == 2){
        $cabang = DB::table('u1127775_absensi.Abs_list_outlet')->where('nama',$karyawan->Cabang)->where('koderegion', $karyawan->region)->first();

        $checklist = DB::select(DB::raw("SELECT u1127775_finance.checklist_group.nama, (select count(*) from u1127775_finance.checklist_harian ch inner join u1127775_finance.user u on u.user = ch.user inner join u1127775_finance.checklist c on c.id = ch.id_checklist inner join u1127775_finance.checklist_group cg on cg.id = c.grup where u.cabang = '".$cabang->id."' and (ch.status='0' or ch.status='1') and date(ch.tgl) = curdate() and cg.id = u1127775_finance.checklist_group.id ) 'progress' ,count(*) 'target' from u1127775_finance.checklist inner join u1127775_finance.checklist_cabang on checklist_cabang.id_checklist = checklist.id inner join u1127775_finance.checklist_group on u1127775_finance.checklist_group.id = u1127775_finance.checklist.grup where u1127775_finance.checklist_cabang.cabang = '".$cabang->id."' group by u1127775_finance.checklist_group.id"));

        $penjualan_bulan_ini = DB::select(DB::raw("SELECT avg(u1127775_finance.closing.penjualan) 'penjualan' from u1127775_finance.closing inner join u1127775_finance.user on u1127775_finance.user.user = u1127775_finance.closing.user where u1127775_finance.user.cabang='".$cabang->id."' and concat(year(u1127775_finance.closing.date),'-',month(u1127775_finance.closing.date)) = concat(year(curdate()),'-',month(curdate()))"));
        $penjualan_bulan_lalu = DB::select(DB::raw("SELECT avg(u1127775_finance.closing.penjualan) 'penjualan' from u1127775_finance.closing inner join u1127775_finance.user on u1127775_finance.user.user = u1127775_finance.closing.user where u1127775_finance.user.cabang='".$cabang->id."' and concat(year(u1127775_finance.closing.date),'-',month(u1127775_finance.closing.date)) = concat(year(date_sub(curdate(), interval 1 month)),'-',month(date_sub(curdate(), interval 1 month)))"));

        $sales = 0;
        if ($penjualan_bulan_lalu[0]->penjualan != 0){
            $sales = ($penjualan_bulan_ini[0]->penjualan/$penjualan_bulan_lalu[0]->penjualan)*100;
        }

        $data = DB::select(DB::raw("SELECT sum(u1127775_finance.closing.penjualan) 'jumlah', 'Penjualan' as 'tipe' from u1127775_finance.closing inner join u1127775_finance.user on u1127775_finance.user.user = u1127775_finance.closing.user inner join u1127775_finance.cabang on u1127775_finance.cabang.id = u1127775_finance.user.cabang where u1127775_finance.user.cabang='".$cabang->id."' and concat(year(u1127775_finance.closing.date),'-',month(u1127775_finance.closing.date)) = concat(year(curdate()),'-',month(curdate()) )
    
            union select sum(u1127775_finance.request_order.harga), 'Pengambilan Bahan (RO)' from u1127775_finance.request_order inner join u1127775_finance.user on u1127775_finance.user.user = u1127775_finance.request_order.user inner join u1127775_finance.cabang on u1127775_finance.cabang.id = u1127775_finance.user.cabang where u1127775_finance.user.cabang='".$cabang->id."'  and concat(year(u1127775_finance.request_order.tanggal),'-',month(u1127775_finance.request_order.tanggal)) = concat(year(curdate() ),'-',month(curdate()) ) and u1127775_finance.request_order.status='1'

            union (select u1127775_finance.so_gudang.total, 'Stok Awal' from u1127775_finance.so_gudang inner join u1127775_finance.user on u1127775_finance.user.user = u1127775_finance.so_gudang.user where u1127775_finance.user.cabang='".$cabang->id."' and (u1127775_finance.so_gudang.tgl) < concat(year(curdate()),'-',month(curdate()),'-01') order by u1127775_finance.so_gudang.tgl desc limit 1)

            union (select u1127775_finance.so_gudang.total, 'Stok Akhir' from u1127775_finance.so_gudang inner join u1127775_finance.user on u1127775_finance.user.user = u1127775_finance.so_gudang.user where u1127775_finance.user.cabang='".$cabang->id."' and (u1127775_finance.so_gudang.tgl) <= last_day(curdate()) order by u1127775_finance.so_gudang.tgl desc limit 1)

            union select sum(u1127775_finance.belanja.total), 'Belanja Cabang' from u1127775_finance.belanja inner join u1127775_finance.cabang on u1127775_finance.cabang.id = u1127775_finance.belanja.cabang inner join u1127775_finance.master_bahan on master_bahan.id = belanja.bahan inner join u1127775_finance.kode on u1127775_finance.kode.id = u1127775_finance.master_bahan.kode where u1127775_finance.cabang.id= '".$cabang->id."' and u1127775_finance.kode.kategori = '1' and concat(year(u1127775_finance.belanja.tgl),'-',month(u1127775_finance.belanja.tgl)) = concat(year(curdate() ),'-',month(curdate() ))

            union select sum(u1127775_finance.transfer_bahan.total), 'Transfer-in Bahan' from u1127775_finance.transfer_bahan inner join u1127775_finance.cabang on u1127775_finance.cabang.id = u1127775_finance.transfer_bahan.cab_tujuan where u1127775_finance.cabang.id = '".$cabang->id."'  and concat(year(u1127775_finance.transfer_bahan.tgl),'-',month(u1127775_finance.transfer_bahan.tgl)) = concat(year(curdate()),'-',month(curdate()) ) and u1127775_finance.transfer_bahan.status='1'

            union select sum(u1127775_finance.transfer_bahan.total), 'Transfer-out Bahan' from u1127775_finance.transfer_bahan inner join u1127775_finance.user on u1127775_finance.user.user = u1127775_finance.transfer_bahan.user inner join u1127775_finance.cabang on u1127775_finance.cabang.id = u1127775_finance.user.cabang where u1127775_finance.user.cabang = '".$cabang->id."'  and concat(year(u1127775_finance.transfer_bahan.tgl),'-',month(u1127775_finance.transfer_bahan.tgl)) = concat(year(curdate()),'-',month(curdate())) and u1127775_finance.transfer_bahan.status='1'

            union select sum(u1127775_finance.goods_receive_notes_detail.total), 'Bahan Dari Supplier' from u1127775_finance.goods_receive_notes_detail inner join u1127775_finance.goods_receive_notes on u1127775_finance.goods_receive_notes.id = u1127775_finance.goods_receive_notes_detail.id_grn inner join u1127775_finance.user on u1127775_finance.user.user = u1127775_finance.goods_receive_notes.user inner join u1127775_finance.cabang on u1127775_finance.cabang.id = u1127775_finance.user.cabang where u1127775_finance.user.cabang = '".$cabang->id."'  and concat(year(u1127775_finance.goods_receive_notes.tgl),'-',month(u1127775_finance.goods_receive_notes.tgl)) = concat(year(curdate()),'-',month(curdate()))

            union select sum(u1127775_finance.closing.selisih), 'Selisih Closing Kasir' from u1127775_finance.closing inner join u1127775_finance.user on u1127775_finance.user.user = u1127775_finance.closing.user inner join u1127775_finance.cabang on u1127775_finance.cabang.id=u1127775_finance.user.cabang where u1127775_finance.user.cabang = '".$cabang->id."'  and concat(year(u1127775_finance.closing.date),'-',month(u1127775_finance.closing.date)) = concat(year(curdate()),'-',month(curdate()))
            
            union select sum(u1127775_finance.belanja.total), 'Biaya Operasional' from u1127775_finance.belanja inner join u1127775_finance.cabang on u1127775_finance.cabang.id = u1127775_finance.belanja.cabang inner join u1127775_finance.master_bahan on u1127775_finance.master_bahan.id = u1127775_finance.belanja.bahan inner join u1127775_finance.kode on u1127775_finance.kode.id = u1127775_finance.master_bahan.kode where u1127775_finance.cabang.id= '".$cabang->id."' and u1127775_finance.kode.kategori = '2' and concat(year(u1127775_finance.belanja.tgl),'-',month(u1127775_finance.belanja.tgl)) = concat(year(curdate()),'-',month(curdate()))"));
        $hitung_hpp = ["Pengambilan Bahan (RO)", "Transfer-in Bahan", "Transfer-out Bahan", "Belanja Cabang", "Bahan Dari Supplier", "Stok Awal", "Stok Akhir"];
        $total_hpp = 0;
        $data_penjualan = 0;
        $data_operasional = 0;
        foreach ($data as $d ) {
            if(in_array($d->tipe, $hitung_hpp)){
                if($d->tipe == 'Transfer-out Bahan' || $d->tipe == 'Stok Akhir'){
                    $total_hpp += (float)($d->jumlah * -1);
                }
                else {
                    $total_hpp += (float)($d->jumlah);
                }
            }else if($d->tipe == 'Penjualan'){
                $data_penjualan = $d->jumlah;
            }
            else if($d->tipe == 'Biaya Operasional'){
                $data_operasional = $d->jumlah;
            }
        }
        // dd($data);
        $selisih = DB::select(DB::raw("SELECT sum(u1127775_finance.closing.selisih) 'selisih' from u1127775_finance.closing inner join u1127775_finance.user on u1127775_finance.user.user = u1127775_finance.closing.user where u1127775_finance.user.cabang = '".$cabang->id."' and concat(year(u1127775_finance.closing.date),'-',month(u1127775_finance.closing.date)) = concat(year(curdate()),'-',month(curdate()))"));

        $today = DB::select(DB::raw("SELECT AVG(convert(time, TIME)) 'average' FROM u1127775_time_table.data where id_outlet = '".$cabang->id."' and date(convert(start, date)) = curdate()"));
        $yesterday = DB::select(DB::raw("SELECT AVG(convert(time, TIME)) 'average' FROM u1127775_time_table.data where id_outlet = '".$cabang->id."' and date(convert(start, date)) = date_sub(curdate(), interval 1 day)"));
        $rekap_selisih_bahan = DB::select(DB::raw("SELECT u1127775_finance.master_bahan.item, sum(u1127775_finance.so_cabang.selisih) 'selisih' from u1127775_finance.so_cabang inner join u1127775_finance.master_bahan on u1127775_finance.master_bahan.id = u1127775_finance.so_cabang.bahan inner join u1127775_finance.user on u1127775_finance.user.user = u1127775_finance.so_cabang.user where u1127775_finance.user.cabang = '".$cabang->id."' and concat(year(u1127775_finance.so_cabang.tgl),'-',month(u1127775_finance.so_cabang.tgl)) = concat(year(curdate()),'-',month(curdate()) ) group by u1127775_finance.so_cabang.bahan"));
        // dd($rekap_selisih_bahan);
        $jum_karyawan_telat = DB::select(DB::raw("select count(a.id) as jumlah from `u1127775_absensi`.`Abs_att_log_center` as a where a.timediff_masuk < '00:00:00' and REPLACE(a.timediff_masuk, '-', '') >= '00:00:59' and a.timediff_masuk is not null and a.timediff_masuk <> '' and a.`tgl_absen` = '". date('Y-m-d', strtotime("-1 days"))."' and (a.status <> 'Hari-OFF' or a.status is null) and a.region ='".Auth::user()->region ."' and a.cabang ='".Auth::user()->cabang ."'"));
        $jum_karyawan_telat = $jum_karyawan_telat[0]->jumlah;

        $jum_izinalfa = AttLogCenter::where('tgl_absen', date('Y-m-d', strtotime("-1 days")))
                                    ->whereNotNull('status')
                                    ->where('cabang', Auth::user()->cabang)
                                    ->where('region', Auth::user()->region)
                                    ->whereIn('status', ['Izin','Sakit','Pulang Awal','Alfa B'])
                                    ->count();

        $jum_tidak_absen = DB::select(DB::raw("select count(a.id) as jumlah from `u1127775_absensi`.`Abs_att_log_center` as a where a.`tgl_absen` = '". date('Y-m-d', strtotime("-1 days"))."' and (a.`status` is null or (a.`jam_masuk` is not null or a.`jam_pulang` is not null)) and (a.`jam_kerja`< '01:01:01' or a.`jam_masuk` is null or a.`jam_pulang` is null) and a.region ='".Auth::user()->region ."' and a.cabang ='".Auth::user()->cabang ."'"));
        $jum_tidak_absen = $jum_tidak_absen[0]->jumlah;

        $jum_tanpa_ket = DB::select(DB::raw("select count(a.NIP) as jumlah from `u1127775_absensi`.Absen a LEFT JOIN (select b.nip, b.nama, tgl_absen from `u1127775_absensi`.Abs_att_log_center b where b.tgl_absen = '". date('Y-m-d', strtotime("-1 days"))."')  b on b.nip = a.NIP where b.tgl_absen is null and a.Status <> 'Resign' and a.No not in (1669,1673,1669,1671,1672,1678,1679,1690,1765,1798,1890,1947,1960,1962) and a.region ='".Auth::user()->region ."' and a.Cabang ='".Auth::user()->cabang ."'"));
        $jum_tanpa_ket = $jum_tanpa_ket[0]->jumlah;

        return view('home')->with('page','dashboard')
                            ->with('karyawan',$karyawan)
                            ->with('jumlah_telat',$jumlah_telat)
                            ->with('total_telat',$total_telat)
                            ->with('checklist',$checklist)
                            ->with('sales',$sales)
                            ->with('data_penjualan',$data_penjualan)
                            ->with('data_operasional',$data_operasional)
                            ->with('total_hpp',$total_hpp)
                            ->with('selisih',$selisih[0]->selisih)
                            ->with('today',gmdate("H:i:s", $today[0]->average))
                            ->with('yesterday',gmdate("H:i:s", $today[0]->average-$yesterday[0]->average))
                            ->with('diff',$today[0]->average-$yesterday[0]->average)
                            ->with('rekap_selisih_bahan',$rekap_selisih_bahan)
                            ->with('jum_karyawan_telat', $jum_karyawan_telat)
                            ->with('jum_izinalfa',$jum_izinalfa)
                            ->with('jum_tidak_absen',$jum_tidak_absen)
                            ->with('jum_tanpa_ket', $jum_tanpa_ket)
                            ->with('sanksi', $sanksi)
                            ;
        }else{
            return view('home')->with('page','dashboard')
                            ->with('karyawan',$karyawan)
                            ->with('jumlah_telat',$jumlah_telat)
                            ->with('total_telat',$total_telat)
                            ->with('sanksi', $sanksi)
                            ;
        }
    }

    public function datadiri()
    {   
        $karyawan = Karyawan::where('NIP',Auth::user()->username)->first();
        return view('datadiri')->with('page','datadiri')->with('karyawan',$karyawan);
    }
}
