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

</div>
<!-- /.container-fluid -->
@endif
@endsection

@push('other-script')
<script type="text/javascript">
    localStorage.clear();
</script>
@endpush