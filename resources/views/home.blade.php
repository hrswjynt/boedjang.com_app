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
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Bonus Bulanan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-700">Rp.{{number_format($karyawan->Bonus_bulanan,0,',','.')}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fab fa-btc fa-2x text-gray-500"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Jabatan - Grade</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-700">{{$karyawan->Jabatan}} - {{$karyawan->Grade}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-briefcase fa-2x text-gray-500"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Content Row -->
    <div class="row">
        <!-- Area Chart -->
        <div class="col-xl-12 col-lg-7">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold">Data Diri</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="bmd-label-floating">NIP </label>
                                        <input name="NIP" type="text"
                                        class="form-control" value="{{$karyawan->NIP}}" id="NIP" maxlength="50" disabled="">
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="bmd-label-floating">NIK </label>
                                        <input name="NIK" type="text"
                                        class="form-control" value="{{$karyawan->NIK}}" id="NIK" maxlength="50" disabled="">
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="bmd-label-floating">Nama</label>
                                        <input name="NAMA" type="text"
                                        class="form-control" value="{{$karyawan->NAMA}}" maxlength="50" disabled="">
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="bmd-label-floating">Cabang</label>
                                        <input type="text"
                                        class="form-control" value="{{$karyawan->Cabang}} - {{$karyawan->region}}" maxlength="50" disabled="">
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="bmd-label-floating">Jabatan</label>
                                        <input type="text"
                                        class="form-control" value="{{$karyawan->Jabatan}}" maxlength="50" disabled="">
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="bmd-label-floating">Agama</label>
                                        <input type="text"
                                        class="form-control" value="{{$karyawan->Agama}}" disabled="">
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="bmd-label-floating">Tanggal Masuk</label>
                                        <input type="date"
                                        class="form-control" value="{{$karyawan->Tanggal_Masuk}}" disabled="">
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="bmd-label-floating">Masa Kerja</label>
                                        <input type="text"
                                        class="form-control" value="{{$karyawan->Masa_kerja}}" disabled="">
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="bmd-label-floating">Grade</label>
                                        <input type="text"
                                        class="form-control" value="{{$karyawan->Grade}}" disabled="">
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="bmd-label-floating">Tambahan 3 Bulan</label>
                                        <input name="tambahan3bln" type="text"
                                        class="form-control" value="{{$karyawan->tambahan3bln}}" disabled="">
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="bmd-label-floating">Gaji Pokok</label>
                                        <input type="text"
                                        class="form-control" value="{{$karyawan->Gaji_pokok}}" disabled="">
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="bmd-label-floating">Bonus Bulanan</label>
                                        <input type="text"
                                        class="form-control" value="{{$karyawan->Bonus_bulanan}}" disabled="">
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="bmd-label-floating">Hari Off </label>
                                        <input ntype="text"
                                        class="form-control" value="{{$karyawan->Hari_Off}}" maxlength="50" disabled="">
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label>Alamat </label>
                                        <textarea style="margin-bottom: -3px" rows="5" class="form-control" disabled="">{!! $karyawan->alamat !!}</textarea>
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="bmd-label-floating">Tanggal Lahir</label>
                                        <input type="date"
                                        class="form-control" value="{{$karyawan->Tgl_lahir}}" disabled="">
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="bmd-label-floating">No.HP </label>
                                        <input type="text"
                                        class="form-control" value="{{$karyawan->No_HP}}" maxlength="25" disabled="">
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="bmd-label-floating">No.Pihak Keluarga </label>
                                        <input type="text"
                                        class="form-control" value="{{$karyawan->no_pihak_keluarga}}" disabled="">
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="bmd-label-floating">Status</label>
                                        <input type="text"
                                        class="form-control" value="{{$karyawan->Status}}" disabled="">
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="bmd-label-floating">Bank </label>
                                        <input type="text"
                                        class="form-control" value="{{$karyawan->Bank}}" maxlength="50" disabled="">
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="bmd-label-floating">No. Rekening </label>
                                        <input type="text"
                                        class="form-control" value="{{$karyawan->No_Rek}}" maxlength="50" disabled="">
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="bmd-label-floating">Jam Kerja </label>
                                        <input type="text"
                                        class="form-control" value="{{$karyawan->Jam_Kerja}}" disabled="">
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="bmd-label-floating">Jam Masuk </label>
                                        <input type="time"
                                        class="form-control" value="{{$karyawan->Jam_masuk}}" disabled="">
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="bmd-label-floating">Jam Pulang </label>
                                        <input type="time"
                                        class="form-control" value="{{$karyawan->Jam_pulang}}" disabled>
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="bmd-label-floating">BPJS Kesehatan</label>
                                        <input type="text"
                                        class="form-control" value="{{$karyawan->bpjs_ksht}}" disabled="">
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="bmd-label-floating">BPJS Ketenagakerjaan</label>
                                        <input type="text"
                                        class="form-control" value="{{$karyawan->bpjs_tkrj}}" disabled="">
                                    </div>
                                    
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