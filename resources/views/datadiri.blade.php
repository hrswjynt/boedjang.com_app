@extends('layouts.app_admin')
@section('content')
<!-- Begin Page Content -->
@if($karyawan != null)
<div class="container-fluid">
    <!-- Page Heading -->
    <!-- Content Row -->
    <!-- Content Row -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Slip Gaji</h1>
    </div>
    <div class="row">
        <!-- Area Chart -->
        <div class="col-xl-12 col-lg-7">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 ">Data Diri <b>{{Auth::user()->name}}</b> ( {{$karyawan->NAMA}} - {{$karyawan->NIP}} ) </h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="font-weight-bold ">NIP </label>
                                        <input name="NIP" type="text"
                                        class="form-control" value="{{$karyawan->NIP}}" id="NIP" maxlength="50" disabled="">
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="font-weight-bold">NIK </label>
                                        <input name="NIK" type="text"
                                        class="form-control" value="{{$karyawan->NIK}}" id="NIK" maxlength="50" disabled="">
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="font-weight-bold">Nama</label>
                                        <input name="NAMA" type="text"
                                        class="form-control" value="{{$karyawan->NAMA}}" maxlength="50" disabled="">
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="font-weight-bold">Cabang</label>
                                        <input type="text"
                                        class="form-control" value="{{$karyawan->Cabang}} - {{$karyawan->region}}" maxlength="50" disabled="">
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="font-weight-bold">Jabatan</label>
                                        <input type="text"
                                        class="form-control" value="{{$karyawan->Jabatan}}" maxlength="50" disabled="">
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="font-weight-bold">Agama</label>
                                        <input type="text"
                                        class="form-control" value="{{$karyawan->Agama}}" disabled="">
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="font-weight-bold">Tanggal Masuk</label>
                                        <input type="date"
                                        class="form-control" value="{{$karyawan->Tanggal_Masuk}}" disabled="">
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="font-weight-bold">Masa Kerja</label>
                                        <input type="text"
                                        class="form-control" value="{{$karyawan->Masa_kerja}}" disabled="">
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="font-weight-bold">Grade</label>
                                        <input type="text"
                                        class="form-control" value="{{$karyawan->Grade}}" disabled="">
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="font-weight-bold">Tambahan 3 Bulan</label>
                                        <input name="tambahan3bln" type="text"
                                        class="form-control" value="{{$karyawan->tambahan3bln}}" disabled="">
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="font-weight-bold">Gaji Pokok</label>
                                        <input type="text"
                                        class="form-control" value="{{$karyawan->Gaji_pokok}}" disabled="">
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="font-weight-bold">Bonus Bulanan</label>
                                        <input type="text"
                                        class="form-control" value="{{$karyawan->Bonus_bulanan}}" disabled="">
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="font-weight-bold">Hari Off </label>
                                        <input ntype="text"
                                        class="form-control" value="{{$karyawan->Hari_Off}}" maxlength="50" disabled="">
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="font-weight-bold">Alamat </label>
                                        <textarea style="margin-bottom: -3px" rows="5" class="form-control" disabled="">{!! $karyawan->alamat !!}</textarea>
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="font-weight-bold">Tanggal Lahir</label>
                                        <input type="date"
                                        class="form-control" value="{{$karyawan->Tgl_lahir}}" disabled="">
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="font-weight-bold">No.HP </label>
                                        <input type="text"
                                        class="form-control" value="{{$karyawan->No_HP}}" maxlength="25" disabled="">
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="font-weight-bold">No.Pihak Keluarga </label>
                                        <input type="text"
                                        class="form-control" value="{{$karyawan->no_pihak_keluarga}}" disabled="">
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="font-weight-bold">Status</label>
                                        <input type="text"
                                        class="form-control" value="{{$karyawan->Status}}" disabled="">
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="font-weight-bold">Bank </label>
                                        <input type="text"
                                        class="form-control" value="{{$karyawan->Bank}}" maxlength="50" disabled="">
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="font-weight-bold">No. Rekening </label>
                                        <input type="text"
                                        class="form-control" value="{{$karyawan->No_Rek}}" maxlength="50" disabled="">
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="font-weight-bold">Jam Kerja </label>
                                        <input type="text"
                                        class="form-control" value="{{$karyawan->Jam_Kerja}}" disabled="">
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="font-weight-bold">Jam Masuk </label>
                                        <input type="time"
                                        class="form-control" value="{{$karyawan->Jam_masuk}}" disabled="">
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="font-weight-bold">Jam Pulang </label>
                                        <input type="time"
                                        class="form-control" value="{{$karyawan->Jam_pulang}}" disabled>
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="font-weight-bold">BPJS Kesehatan</label>
                                        <input type="text"
                                        class="form-control" value="{{$karyawan->bpjs_ksht}}" disabled="">
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="font-weight-bold">BPJS Ketenagakerjaan</label>
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