@extends('layouts.app_admin')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Pengguna</h1>
    </div>
    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6><b>Detail Pengguna</b></h6>
                    <a href="{{ route('user.index') }}" class="btn btn-info btn-sm add">
                        <i class="fa fa-arrow-left "></i>
                        <span>Kembali</span>
                    </a>
                </div>
                <div class="card-body">
                    <div id="success-delete">
                  </div>
                  @if ($message = Session::get('success'))
                  <div class="alert alert-success alert-dismissible" id="success-alert">
                      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                      <p>{{ $message }}</p>
                  </div>
                  @endif
                  @if ($message = Session::get('danger'))
                  <div class="alert alert-danger alert-dismissible" id="danger-alert">
                      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                      <p>{{ $message }}</p>
                  </div>
                  @endif
                        <div class="container-fluid mt-3">
                            <div class="row">
                                <div class="card-body">
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="bmd-label-floating">Foto Profil </label><br>
                                        @if($user->gambar == null)
                                        <img id="img" src="{{asset('admin/img/default.png')}}" alt="your image" style="margin-top: 10px;margin-left: auto;margin-right: auto;width: 100%;max-width: 300px" />
                                        @else
                                        <img id="img" src="{{asset('images/profile/'.$user->gambar)}}" alt="your image"style="margin-top: 10px;margin-left: auto;margin-right: auto;width: 100%;max-width: 300px"/>
                                        @endif
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="bmd-label-floating">Nama Pengguna <span class="red">*</span></label>
                                        <input name="name" type="text"
                                            class="form-control" value="{{$user->name}}" id="name" maxlength="100" disabled="">
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="bmd-label-floating">Username <span class="red">*</span></label>
                                        <input name="username" type="text"
                                            class="form-control" value="{{$user->username}}" id="username" maxlength="20" disabled="">
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="bmd-label-floating">Email </label>
                                        <input name="email" type="email"
                                            class="form-control" value="{{$user->email}}" maxlength="100" disabled="">
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="bmd-label-floating">NIK </label>
                                        <input name="nik" type="text"
                                            class="form-control" value="{{$karyawan->NIK}}" disabled>
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="bmd-label-floating">No.HP </label>
                                        <input name="no_hp" type="text"
                                            class="form-control" value="{{$karyawan->No_HP}}" disabled>
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="bmd-label-floating">Alamat </label>
                                        <textarea name="alamat" class="form-control" id="alamat" rows="2" disabled>{!! $karyawan->alamat !!}</textarea>
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label class="bmd-label-floating">Role <span class="red">*</span></label>
                                        <select class="form-control select2" name="role" disabled="" style="width: 100%">
                                            @if($user->role == 5) 
                                            <option value="5" selected="">Karyawan</option>
                                            <option value="3">Manager</option>
                                            <option value="2">SPV</option>
                                            <option value="4">GA</option>
                                            <option value="1">Admin</option>
                                            <option value="6">Mitra Bakso</option>
                                            @endif

                                            @if($user->role == 3) 
                                            <option value="5">Karyawan</option>
                                            <option value="3" selected="">Manager</option>
                                            <option value="2">SPV</option>
                                            <option value="4">GA</option>
                                            <option value="1">Admin</option>
                                            <option value="6">Mitra Bakso</option>
                                            @endif

                                            @if($user->role == 4) 
                                            <option value="5">Karyawan</option>
                                            <option value="3">Manager</option>
                                            <option value="2">SPV</option>
                                            <option value="4" selected="">GA</option>
                                            <option value="1">Admin</option>
                                            <option value="6">Mitra Bakso</option>
                                            @endif

                                            @if($user->role == 2) 
                                            <option value="5">Karyawan</option>
                                            <option value="3">Manager</option>
                                            <option value="2" selected="">SPV</option>
                                            <option value="4">GA</option>
                                            <option value="1">Admin</option>
                                            <option value="6">Mitra Bakso</option>
                                            @endif

                                            @if($user->role == 6) 
                                            <option value="5">Karyawan</option>
                                            <option value="3">Manager</option>
                                            <option value="2">SPV</option>
                                            <option value="4">GA</option>
                                            <option value="1">Admin</option>
                                            <option value="6" selected="">Mitra Bakso</option>
                                            @endif

                                            @if($user->role == 1)
                                            @if(Auth::user()->role == '1')
                                            <option value="5">Karyawan</option>
                                            <option value="3">Manager</option>
                                            <option value="2">SPV</option>
                                            <option value="4">GA</option>
                                            <option value="1" selected="">Admin</option>
                                            <option value="6">Mitra Bakso</option>
                                            @endif
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var base_url = "{{ url('/') }}";
</script>

@endsection

@push('other-script')

@endpush