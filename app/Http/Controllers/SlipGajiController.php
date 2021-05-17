<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use App\Karyawan;
use App\KaryawanKhusus;
use Auth;
use DB;

class SlipGajiController extends Controller
{

    function __construct()
    {

    }

    public function index()
    {   
        $karyawan = Karyawan::where('NIP',Auth::user()->username)->first();
        // if(date('d') >= 16){
        //     $date1= date("Y-m-d", strtotime("-1 month", strtotime(date('16-m-Y'))));
        //     $date2= date('Y-m-15');
        // }else{
            
        //     $date1 = date('Y-m-16');
        //     $date2 = date("Y-m-d", strtotime("+1 month", strtotime(date('15-m-Y'))));
        // }
        if(date('d') >= 16){
            $date1= date("Y-m", strtotime("-1 month"));
        }else{
            $date1 = date("Y-m", strtotime("-2 month"));
        }
        // dd($date1);
        return view('slipgaji')->with('page','slipgaji')->with('date1',$date1)->with('karyawan',$karyawan);
    }

    public function store(Request $request)
    {   
        $khusus = KaryawanKhusus::select('no_id')->get();
        $no_id = [];
        foreach ($khusus as $k) {
            $no_id[]= $k->no_id;
        }
        $no_id = implode(',', $no_id);
        // dd($request->all());
        $karyawan = Karyawan::where('NIP',Auth::user()->username)->first();
        if($karyawan == null){
            return 'Data karyawan tidak ditemukan.';
        }
        $array = array($no_id);
        $date1 = $request->sdate.'-16';
        $date2 = date("Y-m-d", strtotime("+1 month", strtotime(date($request->sdate.'-15'))));
        if(in_array($karyawan->No, $array)){
            $data = DB::select(DB::raw("SELECT
                    *,
                    FLOOR(((( total_gaji + total_tambahan )-( total_potongan )) + 999 ) / 1000 ) * 1000 AS gaji_bersih,
                    ROW_NUMBER() OVER ( ORDER BY NAMA ) AS 'No' 
                FROM
                    (
                    SELECT
                        *,
                        (
                            P_masuk + P_pulang + P_jam_krj + P_no_absen + P_extra + P_alfa_a + P_izin + P_sakit + P_alfa_b + p_plg_awal + P_kasbon + akm_alfa_a + akm_alfa_b + Angsuran + penangguhan + pot_lain 
                        ) AS total_potongan,
                        ( T_lembur + T_extra + BX + BK +tigabln) AS total_tambahan,
                    IF
                        ( masakerja < 1 OR absenstatus = 'Resign' OR absenstatus = 'Harian' OR maxperiod is not null, ((( GP + BB )/ period )* jml_kerja ), GP + BB ) AS total_gaji 
                    FROM
                        (
                        SELECT
                            b.NIP,
                            b.NAMA,
                            b.Cabang AS cabang,
                            b.Jabatan,
                            b.Grade,
                            0 AS P_masuk,
                            0 AS 'JP_masuk',
                            0 AS 'D_masuk',
                            0 AS 'P_pulang',
                            0 AS 'JP_pulang',
                            0 'D_pulang',
                            0 AS 'P_jam_krj',
                            0 AS 'JP_jam_krj',
                            0 AS 'D_jam_kerja',
                            0 'P_no_absen',
                            0 AS 'J_no_absen',
                            SUM(
                            IF
                            ( pot_extra IS NOT NULL, pot_extra, 0 )) AS 'P_extra',
                            SUM(
                            IF
                            ( pot_alfa_a IS NOT NULL, pot_alfa_a, 0 )) AS 'P_alfa_a',
                            SUM(
                            IF
                            ( pot_izin IS NOT NULL, pot_izin, 0 )) AS 'P_izin',
                            SUM(
                            IF
                            ( pot_sakit IS NOT NULL, pot_sakit, 0 )) AS 'P_sakit',
                            SUM(
                            IF
                            ( pot_alfa_b IS NOT NULL, pot_alfa_b, 0 )) AS 'P_alfa_b',
                            SUM(
                            IF
                            ( pulang_awal IS NOT NULL, pulang_awal, 0 )) AS 'P_plg_awal',
                            SUM(
                            IF
                            ( kasbon IS NOT NULL, kasbon, 0 )) AS 'P_kasbon',
                            SUM(
                            IF
                            ( lembur IS NOT NULL, lembur, 0 )) AS 'T_lembur',
                            '0' AS 'T_extra',
                            ifnull(( SELECT sum(total) FROM u1127775_absensi.Abs_bonus_karyawan WHERE u1127775_absensi.Abs_bonus_karyawan.nip = b.NIP and u1127775_absensi.Abs_bonus_karyawan.jenis = 2 and u1127775_absensi.Abs_bonus_karyawan.tanggal BETWEEN '".$date1."' AND '".$date2."'),'0') AS 'BX',
                            b.Gaji_pokok AS 'GP',
                            b.Bonus_bulanan AS 'BB',
                            ifnull(( SELECT sum(total) FROM u1127775_absensi.Abs_bonus_karyawan WHERE u1127775_absensi.Abs_bonus_karyawan.nip = b.NIP and u1127775_absensi.Abs_bonus_karyawan.jenis = 1 and u1127775_absensi.Abs_bonus_karyawan.tanggal BETWEEN '".$date1."' AND '".$date2."'),'0') AS 'BK',
                            DATEDIFF( '".$date2."', '".$date1."' )+ 1 AS 'jml_kerja',
                            null AS 'maxperiod',
                            ifnull(
                                FLOOR(
                                    ROUND(
                                        ABS((
                                                SUM(
                                                    TIME_TO_SEC(
                                                    CAST( time( IF ( time( timediff_masuk ) < time( '00:00:00' ), timediff_masuk, NULL )) AS time )))/ 60 
                                            )),
                                        0 
                                        )/ ROUND(
                                        AVG( TIME_TO_SEC( time( a.jam_kerja ))/ 60 )))*((
                                    AVG( gaji )+ AVG( bonus_bln ))* 0.03 
                                ),
                                0 
                            ) AS 'akm_alfa_a',
                            ROUND( b.Masa_kerja, 1 ) AS masa_kerja,
                            0 AS 'akm_alfa_b',
                            ifnull(( SELECT bpjs_ksht FROM u1127775_absensi.bpjs WHERE u1127775_absensi.bpjs.nip = b.NIP and u1127775_absensi.bpjs.tgl = '".$date2."'),'0') AS 'bpjs_ksht',
                            ifnull(( SELECT bpjs_tkrj FROM u1127775_absensi.bpjs WHERE u1127775_absensi.bpjs.nip = b.NIP and u1127775_absensi.bpjs.tgl = '".$date2."'),'0') AS 'bpjs_tkrj',
                            b.Masa_kerja AS 'masakerja',
                            b.STATUS AS 'absenstatus',
                            ifnull(( SELECT sum(total) FROM u1127775_absensi.Abs_angsuran WHERE u1127775_absensi.Abs_angsuran.nip = b.NIP and u1127775_absensi.Abs_angsuran.gaji = 1 and u1127775_absensi.Abs_angsuran.tanggal BETWEEN '".$date1."' AND '".$date2."'),'0') AS 'Angsuran',
                            b.penangguhan AS 'penangguhan',
                            ifnull( CAST( b.tambahan3bln AS INT ), 0 ) AS 'tigabln',
                            ".date('t',strtotime($date1))." AS period,
                            b.region,
                            date( b.Tanggal_Masuk ) AS 'tgl_masuk',
                            b.Bank,
                            b.No_Rek AS 'No Rek',
                            b.STATUS,
                            ifnull(( SELECT sum(total) FROM u1127775_absensi.Abs_potongan_lain WHERE u1127775_absensi.Abs_potongan_lain.nip = b.NIP and u1127775_absensi.Abs_potongan_lain.tanggal BETWEEN '".$date1."' AND '".$date2."'),'0') AS 'pot_lain' 
                        FROM
                            u1127775_absensi.Absen b
                            LEFT JOIN u1127775_absensi.Abs_att_log_center a ON a.nip = b.NIP 
                            WHERE   if(a.tgl_absen is not null, a.tgl_absen BETWEEN '".$date1."' 
                                AND '".$date2."',1=1) and
                            b.NO IN (".$no_id.") 
                            AND b.STATUS <> 'Resign'
                        GROUP BY
                            b.NIP  
                        ) AS REKAP 
                    WHERE
                        ( absenstatus = 'Resign' AND jml_kerja >= 1 ) 
                        OR ( absenstatus <> 'Resign' ) 
                    ) AS REKAP_AKHIR 
                WHERE
                    NIP = '".Auth::user()->username."'
                ORDER BY
                    region DESC,
                    masakerja DESC"));
        }else{
            $data = DB::select(DB::raw("SELECT
                    *,
                    FLOOR(((( total_gaji + total_tambahan )-( total_potongan )) + 999 ) / 1000 ) * 1000 AS gaji_bersih,
                    ROW_NUMBER() OVER ( ORDER BY NAMA ) AS 'No' 
                FROM
                    (
                    SELECT
                        *,
                        (
                            P_masuk + P_pulang + P_jam_krj + P_no_absen + P_extra + P_alfa_a + P_izin + P_sakit + P_alfa_b + p_plg_awal + P_kasbon + akm_alfa_a + akm_alfa_b + Angsuran + penangguhan + pot_lain 
                        ) AS total_potongan,
                        ( T_lembur + T_extra + BX + BK +tigabln) AS total_tambahan,
                    IF
                        ( masakerja < 1 OR absenstatus = 'Resign' OR absenstatus = 'Harian' OR maxperiod is not null, ((( GP + BB )/ period )* jml_kerja ), GP + BB ) AS total_gaji 
                    FROM
                        (  
                        SELECT
                            b.NIP,
                            b.NAMA,
                            b.Cabang AS cabang,
                            b.Jabatan,
                            b.Grade,
                            sum(
                            ifnull( a.pot_masuk, 0 )) AS P_masuk,
                            COUNT(
                            IF
                            ( pot_masuk > 0, 1, NULL )) AS 'JP_masuk',
                            ROUND(
                                ABS((
                                        SUM(
                                            TIME_TO_SEC(
                                            CAST( time( IF ( time( timediff_masuk ) < time( '00:00:00' ), timediff_masuk, NULL )) AS time )))/ 60 
                                    )),
                                0 
                            ) AS 'D_masuk',
                        IF
                            (
                                pot_pulang IS NULL,
                                0,
                            SUM( pot_pulang )) AS 'P_pulang',
                            COUNT(
                            IF
                            ( pot_pulang > 0, 1, NULL )) AS 'JP_pulang',
                            ROUND(
                                ABS((
                                        SUM(
                                            TIME_TO_SEC(
                                            CAST( time( IF ( time( timediff_pulang ) < time( '00:00:00' ), timediff_pulang, NULL )) AS time )))/ 60 
                                    )),
                                0 
                            ) AS 'D_pulang',
                        IF
                            (
                                pot_jam_kerja IS NULL,
                                0,
                            SUM( pot_jam_kerja )) AS 'P_jam_krj',
                            COUNT(
                            IF
                            ( pot_jam_kerja > 0, 1, NULL )) AS 'JP_jam_krj',
                            ROUND(
                                ABS((
                                        SUM(
                                            TIME_TO_SEC(
                                            CAST( time( IF ( time( timediff_jam_kerja ) < time( '00:00:00' ), timediff_jam_kerja, NULL )) AS time )))/ 60 
                                    )),
                                0 
                            ) AS 'D_jam_kerja',
                        IF
                            (
                                pot_no_absen IS NULL,
                                0,
                            SUM( pot_no_absen )) AS 'P_no_absen',
                            COUNT(
                            IF
                            ( pot_no_absen > 0, 1, NULL )) AS 'J_no_absen',
                            SUM(
                            IF
                            ( pot_extra IS NOT NULL, pot_extra, 0 )) AS 'P_extra',
                            SUM(
                            IF
                            ( pot_alfa_a IS NOT NULL, pot_alfa_a, 0 )) AS 'P_alfa_a',
                            SUM(
                            IF
                            ( pot_izin IS NOT NULL, pot_izin, 0 )) AS 'P_izin',
                            SUM(
                            IF
                            ( pot_sakit IS NOT NULL, pot_sakit, 0 )) AS 'P_sakit',
                            SUM(
                            IF
                            ( pot_alfa_b IS NOT NULL, pot_alfa_b, 0 )) AS 'P_alfa_b',
                            SUM(
                            IF
                            ( pulang_awal IS NOT NULL, pulang_awal, 0 )) AS 'P_plg_awal',
                            SUM(
                            IF
                            ( kasbon IS NOT NULL, kasbon, 0 )) AS 'P_kasbon',
                            SUM(
                            IF
                            ( lembur IS NOT NULL, lembur, 0 )) AS 'T_lembur',
                            '0' AS 'T_extra',
                            ifnull(( SELECT sum(total) FROM u1127775_absensi.Abs_bonus_karyawan WHERE u1127775_absensi.Abs_bonus_karyawan.nip = b.NIP and u1127775_absensi.Abs_bonus_karyawan.jenis = 2 and u1127775_absensi.Abs_bonus_karyawan.tanggal BETWEEN '".$date1."' AND '".$date2."'),'0') AS 'BX',
                        IF
                            (
                                AVG( a.gaji ) IS NULL,
                                b.Gaji_pokok,
                            AVG( a.gaji )) AS 'GP',
                        IF
                            (
                                AVG( a.bonus_bln ) IS NULL,
                                b.Bonus_bulanan,
                            AVG( a.bonus_bln )) AS 'BB',
                            ifnull(( SELECT sum(total) FROM u1127775_absensi.Abs_bonus_karyawan WHERE u1127775_absensi.Abs_bonus_karyawan.nip = b.NIP and u1127775_absensi.Abs_bonus_karyawan.jenis = 1 and u1127775_absensi.Abs_bonus_karyawan.tanggal BETWEEN '".$date1."' AND '".$date2."'),'0') AS 'BK',
                            COUNT( a.nip ) AS 'jml_kerja',
                            ".date('t',strtotime($date1))." AS 'maxperiod',
                            ifnull(
                                FLOOR(
                                    ROUND(
                                        ABS((
                                                SUM(
                                                    TIME_TO_SEC(
                                                    CAST( time( IF ( time( timediff_masuk ) < time( '00:00:00' ), timediff_masuk, NULL )) AS time )))/ 60 
                                            )),
                                        0 
                                        )/ ROUND(
                                        AVG( TIME_TO_SEC( time( a.jam_kerja ))/ 60 )))*((
                                    AVG( gaji )+ AVG( bonus_bln ))* 0.03 
                                ),
                                0 
                            ) AS 'akm_alfa_a',
                            ROUND( b.Masa_kerja, 1 ) AS masa_kerja,
                            ifnull(
                            IF
                                (
                                    b.Masa_kerja < 1.2
                                    OR b.Tanggal_Masuk < '".$date2."'
                                    OR b.STATUS = 'Resign' 
                                    OR b.STATUS = 'Harian',
                                    0,
                                    ((
                                        SELECT
                                            DATEDIFF( '".$date2."', '".$date1."' )+ 1 
                                            )- COUNT( a.nip ))*((
                                            AVG( gaji )+ AVG( bonus_bln ))* 0.05 
                                    )),
                                0 
                            ) AS 'akm_alfa_b',
                            ifnull(( SELECT bpjs_ksht FROM u1127775_absensi.bpjs WHERE u1127775_absensi.bpjs.nip = b.NIP and u1127775_absensi.bpjs.tgl = '".$date2."'),'0') AS 'bpjs_ksht',
                            ifnull(( SELECT bpjs_tkrj FROM u1127775_absensi.bpjs WHERE u1127775_absensi.bpjs.nip = b.NIP and u1127775_absensi.bpjs.tgl = '".$date2."'),'0') AS 'bpjs_tkrj',
                            b.Masa_kerja AS 'masakerja',
                            b.STATUS AS 'absenstatus',
                            ifnull(( SELECT sum(total) FROM u1127775_absensi.Abs_angsuran WHERE u1127775_absensi.Abs_angsuran.nip = b.NIP and u1127775_absensi.Abs_angsuran.gaji = 1 and u1127775_absensi.Abs_angsuran.tanggal BETWEEN '".$date1."' AND '".$date2."'),'0') AS 'Angsuran',
                            b.penangguhan AS 'penangguhan',
                            ifnull(( SELECT tambahan3bln FROM u1127775_absensi.Abs_att_log_center WHERE u1127775_absensi.Abs_att_log_center.nip = b.NIP and u1127775_absensi.Abs_att_log_center.tgl_absen = '".$date2."'),'0') AS 'tigabln',
                            ".date('t',strtotime($date1))." AS period,
                            b.region,
                            date( b.Tanggal_Masuk ) AS 'tgl_masuk',
                            b.Bank,
                            b.No_Rek AS 'No Rek',
                            b.STATUS,
                            ifnull(( SELECT sum(total) FROM u1127775_absensi.Abs_potongan_lain WHERE u1127775_absensi.Abs_potongan_lain.nip = b.NIP and u1127775_absensi.Abs_potongan_lain.tanggal BETWEEN '".$date1."' AND '".$date2."'),'0') AS 'pot_lain' 
                        FROM
                            u1127775_absensi.Absen b
                            LEFT JOIN u1127775_absensi.Abs_att_log_center a ON a.nip = b.NIP 
                        WHERE
                            a.tgl_absen BETWEEN '".$date1."' 
                            AND '".$date2."' 
                            AND b.NO NOT IN (".$no_id.") 
                        GROUP BY
                            b.NIP 
                        ) AS REKAP 
                    WHERE
                        ( absenstatus = 'Resign' AND jml_kerja >= 1 ) 
                        OR ( absenstatus <> 'Resign' ) 
                    ) AS REKAP_AKHIR 
                WHERE
                    NIP = '".Auth::user()->username."'
                ORDER BY
                    region DESC,
                    masakerja DESC"));
        }
        
        // dd($data);
        return view('slipgajidata')->with('page','slipgaji')->with('date1',$date1)->with('date2',$date2)->with('data',$data);
    }
    


}
