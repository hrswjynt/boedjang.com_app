@extends('layouts.app_admin')
@section('content')
<!-- Begin Page Content -->
@if($karyawan != null)
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <h1 style="zoom:75%" class="h3 mb-0 text-gray-800">Halo <b>{{Auth::user()->name}}</b> Selamat Datang di Boedjang Group!</h1>
        <!--         <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
    </div>
    <!-- Content Row -->
    @if ($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible" id="success-alert">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <p>{!! $message !!}</p>
    </div>
    @endif
    @if ($message = Session::get('danger'))
    <div class="alert alert-danger alert-dismissible" id="danger-alert">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <p>{!! $message !!}</p>
    </div>
    @endif
    @if(Auth::user()->role == 2)
    <hr>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <a target="__blank" class="btn btn-primary" href="https://finance.boedjang.com/dashboard/"><h1 class="h3 mb-0 text-white"><i class="fas fa-share"></i> Finance</h1></a>
    </div>
    <div class="row">
        <div class="col-lg-4 p-2">
            <div class="card text-dark bg-white h-100">
                <div class="card-body" style="position: relative">
                    <h1 class="card-title ">
                    Checklist
                    </h1>
                    <div class="card-text" id="checklist_list">
                        @foreach($checklist as $c)
                        <p class="mb-0 font-weight-light" style="font-size: 14px">{{$c->nama}}</p>
                        <div class="progress mb-1">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: {{round($c->progress/$c->target*100)}}%;" aria-valuenow="{{round($c->progress/$c->target*100)}}" aria-valuemin="0" aria-valuemax="100">{{round($c->progress/$c->target*100)}}%</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="row">
                <div class="col-6 p-2" id="sales_dari_bulan_lalu">
                    <div class="card text-white bg-primary mb-3 h-100">
                        <div class="card-body text-center">
                            <h1 class="card-title">{{round($sales,1)}}%</h1>
                            <p class="card-text">% rata2 sales dari bulan lalu</p>
                        </div>
                    </div></div>
                    <div class="col-6 p-2">
                        <div class="card text-white bg-warning mb-3 h-100">
                            <div class="card-body text-center">
                                <h1 class="card-title" id="hpp">{{round(($total_hpp/$data_penjualan)*100,1)}}%</h1>
                                <p class="card-text">% HPP</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 p-2">
                        <div class="card text-white bg-success mb-3 h-100">
                            <div class="card-body text-center">
                                <h1 class="card-title" id="operasional">{{round(($data_operasional/$data_penjualan)*100,1)}}%</h1>
                                <p class="card-text">% Operasional</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 p-2">
                        <div class="card text-white bg-info h-100">
                            <div class="card-body text-center">
                                <h3 class="card-title" id="total_selisih_kasir">Rp{{number_format($selisih,0,',','.')}}</h3>
                                <p class="card-text">Total Selisih Kasir</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 p-2">
                        <div class="card text-dark bg-white h-100">
                            <div class="card-body text-center py-2">
                                <div class="row">
                                    <div class="col-6">
                                        <h3 class="mb-0" id="speed_avg">{{$today}}</h3>
                                        <p class="mb-0">Speed Rata2 hari ini</p>
                                    </div>
                                    <div class="col-6">
                                        @if($diff < 0)
                                        <h3 class="mb-0 text-success" id="speed_diff">{{$yesterday}}</h3>
                                        @else
                                        <h3 class="mb-0 text-danger" id="speed_diff">{{$yesterday}}</h3>
                                        @endif
                                        <p class="mb-0" id="diff_text">Dibanding Kemarin</p>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12 p-2">
                <div class="card text-white bg-gradient-danger mb-3 h-100">
                    <div class="card-header text-center bg-danger">
                        Rekap Selisih Bahan
                    </div>
                    <div class="card-body d-flex justify-content-between">
                        <table class="table table-sm table-borderless text-white">
                            <tbody id="rekap_selisih_bahan">
                                @foreach($rekap_selisih_bahan as $r)
                                <tr>
                                    <td>{{$r->item}}</td>
                                    <td class="text-right">{{$r->selisih}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <a target="__blank" class="btn btn-info" href="https://absensi.boedjang.com/"><h1 class="h3 mb-0 text-white"><i class="fas fa-share"></i> Absensi</h1></a>
        </div>
        <div class="row">
            <div class="col-xl-6 col-md-6" style="margin-bottom: 10px">
                <div class="card card-stats bg-primary text-white">
                    <!-- Card body -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase mb-0">Karyawan Telat</h5>
                                <h5 class="card-title text-uppercase mb-0">{{date('d/m/Y', strtotime("-1 days"))}}</h5>
                                <span class="h2 font-weight-bold mb-0">{{$jum_karyawan_telat}} orang</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape text-white rounded-circle shadow">
                                    <i class="fa fa-user"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-6" style="margin-bottom: 10px">
                <div class="card card-stats bg-info text-white">
                    <!-- Card body -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase mb-0">Karyawan Alfa/Izin</h5>
                                <h5 class="card-title text-uppercase mb-0">{{date('d/m/Y', strtotime("-1 days"))}}</h5>
                                <span class="h2 font-weight-bold mb-0">{{$jum_izinalfa}} orang</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape text-white rounded-circle shadow">
                                    <i class="fa fa-user"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-6 col-md-6" style="margin-bottom: 10px">
                <div class="card card-stats bg-warning text-white">
                    <!-- Card body -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase mb-0">Karyawan Tidak Absen</h5>
                                <h5 class="card-title text-uppercase mb-0">{{date('d/m/Y', strtotime("-1 days"))}}</h5>
                                <span class="h2 font-weight-bold mb-0">{{$jum_tidak_absen}} orang</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape text-white rounded-circle shadow">
                                    <i class="fa fa-user"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-6" style="margin-bottom: 10px">
                <div class="card card-stats bg-danger text-white">
                    <!-- Card body -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase mb-0">Karyawan Tanpa Keterangan</h5>
                                <h5 class="card-title text-uppercase mb-0">{{date('d/m/Y', strtotime("-1 days"))}}</h5>
                                <span class="h2 font-weight-bold mb-0">{{$jum_tanpa_ket}} orang</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape text-white rounded-circle shadow">
                                    <i class="fa fa-user"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 style="zoom:75%" class="h3 mb-0 text-gray-800">Data <b>{{Auth::user()->name}}</b>
        </div>
        @endif
        <div class="row">
            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Masa Kerja</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-700">{{$karyawan->Masa_kerja}} Bulan</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-tag fa-2x text-gray-500"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Jabatan - Grade</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-700">{{$karyawan->Jabatan}} - {{$karyawan->Grade}}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-briefcase fa-2x text-gray-500"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Gaji Pokok</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-700">Rp.{{number_format($karyawan->Gaji_pokok,0,',','.')}}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-money-bill-wave fa-2x text-gray-500"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Bonus Bulanan</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-700">Rp.{{number_format($karyawan->Bonus_bulanan,0,',','.')}}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fab fa-btc fa-2x text-gray-500"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Content Row -->
        <div class="row">
            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Jumlah terlambat <p style="zoom:80%">2 Bulan Terakhir</p> </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-700">{{$jumlah_telat}} Kali</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-minus fa-2x text-gray-500"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Pot. Terlambat <p style="zoom:80%">2 Bulan Terakhir</p> </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-700">Rp.{{number_format($total_telat,0,',','.')}}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-search-minus fa-2x text-gray-500"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Cabang</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-700">{{$karyawan->Cabang}} {{strtoupper($karyawan->region)}}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-warehouse fa-2x text-gray-500"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Pending Requests Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Jam Kerja</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-700">{{$karyawan->Jam_Kerja}}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-gray-500"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
        @endif
        @endsection
        @push('other-script')
        <script type="text/javascript">
        localStorage.clear();
        </script>
        @endpush