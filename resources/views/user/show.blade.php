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
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="container-fluid mt-3">
                                                <div class="row">
                                                    <div class="col-md-12">
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
                                                            <label class="bmd-label-floating">Role <span class="red">*</span></label>
                                                            <select class="form-control select2" name="role" disabled="">
                                                                @if($user->role == 5) 
                                                                <option value="5" selected="">Karyawan</option>
                                                                <option value="3">Manager</option>
                                                                <option value="2">SPV</option>
                                                                <option value="1">Admin</option>
                                                                @endif

                                                                @if($user->role == 3) 
                                                                <option value="5">Karyawan</option>
                                                                <option value="3" selected="">Manager</option>
                                                                <option value="2">SPV</option>
                                                                <option value="1">Admin</option>
                                                                @endif

                                                                @if($user->role == 2) 
                                                                <option value="5">Karyawan</option>
                                                                <option value="3">Manager</option>
                                                                <option value="2" selected="">SPV</option>
                                                                <option value="1">Admin</option>
                                                                @endif

                                                                @if($user->role == 1)
                                                                @if(Auth::user()->role == '1')
                                                                <option value="5">Karyawan</option>
                                                                <option value="3">Manager</option>
                                                                <option value="2">SPV</option>
                                                                <option value="1" selected="">Admin</option>
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