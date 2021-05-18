<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Slip Gaji Karyawan</title>
        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ asset('landing/assets/img/boedjang.png')}}" />
        <style type="text/css">
        body {
            background: rgb(204,204,204);
            font-family: Calibri;
        }
        page {
            background: white;
            display: block;
            margin: 0 auto;
            page-break-after:always;
            /*margin-bottom: 0.5cm;*/
            /*box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);*/
        }
        page[size="A4"] {
            width: 21cm;
            height: 29.7cm;
        }
        page[size="A4"][layout="landscape"] {
            width: 29.7cm;
            height: 21cm;
        }
        page[size="A3"] {
            width: 29.7cm;
            height: 42cm;
        }
        page[size="A3"][layout="landscape"] {
            width: 42cm;
            height: 29.7cm;
        }
        page[size="A5"] {
            width: 14.8cm;
            height: 21cm;
        }
        page[size="A5"][layout="landscape"] {
            width: 21cm;
            height: 14.8cm;
        }
        page[size="kertas80"] {
            width: 8cm;
            height: 8cm;
        }
        @media print {
            body, page {
                margin: 0;
                box-shadow: 0;
            }
        }

        .f10{
            font-size: 10px;
        }
        .f9{
            font-size: 9px;
        }
        .f8{
            font-size: 8px;
        }

        .txt-center{
            text-align: center
        }

        .txt-right{
            text-align: right
        }

        .txt-left{
            text-align: left
        }

        .bold{
            font-weight: 700;
        }

        table {
            border-collapse: collapse;
        }

        th, td {
            padding: 1px;
        }

        .b-none{
            border: none;
        }

        .lr-none{
            border-left: none;
            border-right: none;
        }

        .l-none{
            border-left: none;
        }
        </style>
    </head>
    <body>
        <!-- <page size="A4"></page>
        <page size="A4"></page>
        <page size="A4" layout="landscape"></page>
        <page size="A5"></page>
        <page size="A5" layout="landscape"></page>
        <page size="A3"></page> -->
        <?php $no=0;?>
        @foreach($data as $d)
        <?php $no++;?>
        <page size="kertas80">
            <table width="98%" border="1px black solid" style="margin: 3px">
                <tr>
                    <td width="10%" rowspan="3" class="txt-center">
                        <img src="{{ asset('landing/assets/img/boedjang.png')}}" style="width: 30px;" >
                    </td>
                    <td width="40%" class="f10 txt-center bold b-none">
                        SLIP GAJI
                    </td>
                    <td width="10%" class="f10 txt-center" rowspan="3">
                        <table>
                            <tr>
                                <td>No</td>
                                <td>:</td>
                                <td>{{$no}}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td width="60%" class="f9 txt-center bold b-none">
                        BOEDJANG GRUP INDONESIA
                    </td>
                </tr>
                <tr>
                    <td width="60%" class="f8 txt-center">
                        Jl. Teuku Umar, Komplek Ruko Pontianak Mall, Pontianak
                    </td>
                    
                </tr>
            </table>
            <table style="margin-left: 10px;margin-bottom: 10px">
                <tr>
                    <td class="f9 bold">Nama</td>
                    <td class="f9 bold">:</td>
                    <td class="f9 bold">{{$d->NAMA}}</td>
                </tr>
                <tr>
                    <td class="f9 bold">NIP</td>
                    <td class="f9 bold">:</td>
                    <td class="f9 ">{{$d->NIP}}</td>
                </tr>
                <tr>
                    <td class="f9 bold">Jabatan</td>
                    <td class="f9 bold">:</td>
                    <td class="f9 ">{{$d->Jabatan}}</td>
                </tr>
                <!-- <tr>
                    <td class="f9 bold">Golongan/Grade</td>
                    <td class="f9 bold">:</td>
                    <td class="f9 ">{{$d->Grade}}</td>
                </tr> -->
                <tr>
                    <td class="f9 bold">Cabang</td>
                    <td class="f9 bold">:</td>
                    <td class="f9 ">{{$d->cabang}}</td>
                </tr>
                <tr>
                    <td class="f9 bold">Periode</td>
                    <td class="f9 bold">:</td>
                    <td class="f9 ">{{date('d F Y', strtotime($date1))}} s/d {{date('d F Y', strtotime($date2))}}</td>
                </tr>
            </table>
            <table>
                <tr>
                    <td>
                        <table border="1px solid black" class="f9" style="margin-left: 7px">
                            <tr>
                                <td class="">GAJI POKOK</td>
                                <td class="">:</td>
                                <td class="txt-left lr-none">Rp.</td>
                                <td class="txt-right l-none">
                                @if($d->GP != null || $d->GP != 0)
                                {{number_format($d->total_gaji*($d->GP/($d->BB+$d->GP)),0,',','.')}}
                                @else
                                {{number_format(0,0,',','.')}}
                                @endif
                                </td>
                            </tr>
                            <tr>
                                <td>BONUS BULANAN</td>
                                <td>:</td>
                                <td class="txt-left lr-none">Rp.</td>
                                <td class="txt-right l-none">
                                @if($d->BB != null || $d->BB != 0)
                                {{number_format($d->total_gaji*($d->BB/($d->BB+$d->GP)),0,',','.')}}
                                @else
                                {{number_format(0,0,',','.')}}
                                @endif
                                </td>
                            </tr>
                            <tr>
                                <td >BONUS KHUSUS</td>
                                <td >:</td>
                                <td class="txt-left lr-none">Rp.</td>
                                <td class="txt-right l-none">{{number_format($d->BK,0,',','.')}}</td>
                            </tr>
                            <tr>
                                <td >LEMBUR</td>
                                <td >:</td>
                                <td class="txt-left lr-none">Rp.</td>
                                <td class="txt-right l-none">{{number_format($d->T_lembur,0,',','.')}}</td>
                            </tr>
                            <?php
                                if($d->BX == ''){
                                    $bonuskhusus = 0;
                                }else{
                                    $bonuskhusus = $d->BX;
                                }
                            ?>
                            <tr>
                                <td class="f8">Tambahan tidak tetap</td>
                                <td>:</td>
                                <td class="txt-left lr-none">Rp.</td>
                                <td class="txt-right l-none">{{number_format($bonuskhusus,0,',','.')}}</td>
                            </tr>
                            <tr>
                                <td class="f8">Tambahan Masa Kerja</td>
                                <td>:</td>
                                <td class="txt-left lr-none">Rp.</td>
                                <td class="txt-right l-none">{{number_format($d->tigabln,0,',','.')}}</td>
                            </tr>
                            <tr>
                                <td >BPJS Kesehatan</td>
                                <td >:</td>
                                <td class="txt-left lr-none">Rp.</td>
                                @if(is_numeric($d->bpjs_ksht))
                                <td class="txt-right l-none">{{number_format($d->bpjs_ksht,0,',','.')}}</td>
                                @else
                                <td class="txt-right l-none">0</td>
                                @endif
                            </tr>
                            <tr>
                                <td>BPJS Tenaga Kerja</td>
                                <td>:</td>
                                <td class="txt-left lr-none">Rp.</td>
                                @if(is_numeric($d->bpjs_tkrj))
                                <td class="txt-right l-none">{{number_format($d->bpjs_tkrj,0,',','.')}}</td>
                                @else
                                <td class="txt-right l-none">0</td>
                                @endif
                            </tr>

                            <?php
                                if(is_numeric($d->bpjs_ksht)){
                                    $bpjs_ksht = $d->bpjs_ksht;
                                }else{
                                    $bpjs_ksht = 0;
                                }
                                if(is_numeric($d->bpjs_tkrj)){
                                    $bpjs_tkrj = $d->bpjs_tkrj;
                                }else{
                                    $bpjs_tkrj = 0;
                                }
                                $totaltambah = $d->total_gaji+$d->BK+$d->T_lembur+$bonuskhusus+$d->tigabln+$bpjs_ksht+$bpjs_tkrj; 
                            ?>
                            <tr class="bold">
                                <td>TOTAL</td>
                                <td>:</td>
                                <td class="txt-left lr-none">Rp.</td>
                                <td class="txt-right l-none">{{number_format($totaltambah,0,',','.')}}</td>
                            </tr>

                        </table>
                    </td>
                    <td>
                        <table border="1px solid black" class="f9" style="margin-left: 5px">
                            <tr>
                                <td >TELAT</td>
                                <td >:</td>
                                <td class="txt-left lr-none">Rp.</td>
                                <td class="txt-right l-none" style="min-width: 40px">-{{number_format($d->P_masuk+$d->P_pulang,0,',','.')}}</td>
                            </tr>
                            <tr>
                                <td>ALFA B & IZIN</td>
                                <td>:</td>
                                <td class="txt-left lr-none">Rp.</td>
                                <td class="txt-right l-none">-{{number_format($d->P_alfa_a+$d->P_izin+$d->P_sakit+$d->P_alfa_b+$d->P_plg_awal+$d->akm_alfa_b,0,',','.')}}</td>
                            </tr>
                            <tr>
                                <td>Akm Alfa A</td>
                                <td>:</td>
                                <td class="txt-left lr-none">Rp.</td>
                                <td class="txt-right l-none">-{{number_format($d->akm_alfa_a,0,',','.')}}</td>
                            </tr>
                            <tr>
                                <td>KASBON</td>
                                <td>:</td>
                                <td class="txt-left lr-none">Rp.</td>
                                <td class="txt-right l-none">-{{number_format($d->P_kasbon,0,',','.')}}</td>
                            </tr>
                            <tr>
                                <td>PINJAMAN</td>
                                <td>:</td>
                                <td class="txt-left lr-none">Rp.</td>
                                <td class="txt-right l-none">-{{number_format($d->Angsuran,0,',','.')}}</td>
                            </tr>
                            <tr>
                                <td>BPJS Kesehatan</td>
                                <td>:</td>
                                <td class="txt-left lr-none">Rp.</td>
                                @if(is_numeric($d->bpjs_ksht))
                                <td class="txt-right l-none">-{{number_format($d->bpjs_ksht,0,',','.')}}</td>
                                @else
                                <td class="txt-right l-none">-0</td>
                                @endif
                            </tr>
                            <tr>
                                <td>BPJS Tenaga Kerja</td>
                                <td>:</td>
                                <td class="txt-left lr-none">Rp.</td>
                                @if(is_numeric($d->bpjs_tkrj))
                                <td class="txt-right l-none">-{{number_format($d->bpjs_tkrj,0,',','.')}}</td>
                                @else
                                <td class="txt-right l-none">-0</td>
                                @endif
                            </tr>
                            <tr>
                                <td>Pot. Lain-lain</td>
                                <td>:</td>
                                <td class="txt-left lr-none">Rp.</td>
                                <td class="txt-right l-none">-{{number_format($d->pot_lain+$d->P_no_absen,0,',','.')}}</td>
                            </tr>
                            <?php
                                if(is_numeric($d->bpjs_ksht)){
                                    $mbpjs_ksht = $d->bpjs_ksht;
                                }else{
                                    $mbpjs_ksht = 0;
                                }
                                if(is_numeric($d->bpjs_tkrj)){
                                    $mbpjs_tkrj = $d->bpjs_tkrj;
                                }else{
                                    $mbpjs_tkrj = 0;
                                }
                                $totalpot = $d->P_masuk+$d->P_alfa_a+$d->P_izin+$d->P_sakit+$d->P_alfa_b+$d->P_plg_awal+$d->akm_alfa_a+$d->akm_alfa_b+$d->P_kasbon+$d->Angsuran+$mbpjs_ksht+$mbpjs_tkrj+$d->P_extra+$d->pot_lain+$d->P_no_absen; 
                            ?>
                            <tr class="bold">
                                <td>TOTAL</td>
                                <td>:</td>
                                <td class="txt-left lr-none">Rp.</td>
                                <td class="txt-right l-none">-{{number_format($totalpot,0,',','.')}}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <table style="margin-top: 5px">
                <tr>
                    <td width="60%">
                        <table border="1px solid black" class="f9" style="margin-left: 7px">
                            <tr>
                                <td>" Jangan pernah menghitung apa yang telah kamu terima, Tapi ingatlah apa yang telah kamu berikan. "</td>
                            </tr>
                        </table>
                    </td>
                    <td width="40%">
                        <table border="1px solid black" class="f9" style="margin-left: 5px;">
                            <tr>
                                <td class="txt-center" style="padding-left: 25px;padding-right: 25px">GAJI BERSIH</td>
                            </tr>
                            <tr>
                                <td class="txt-center" style="padding: 5px">Rp.{{number_format(round((ceil(($totaltambah-$totalpot)/1000)*1000),-3),0,',','.')}}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            
        </page>
        @endforeach
        
        
    </body>
</html>