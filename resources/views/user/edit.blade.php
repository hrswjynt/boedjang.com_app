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
                    <h6><b>Tambah Pengguna</b></h6>
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
                  <form method="POST" action="{{route('user.update',$user->id)}}" id="user_form">
                        @csrf
                        @method('PUT')
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
                                                                class="form-control" value="{{$user->name}}" id="name" maxlength="100" >
                                                        </div>
                                                        <div class="form-group mb-4 bmd-form-group">
                                                            <label class="bmd-label-floating">Username <span class="red">*</span></label>
                                                            <input name="username" type="text"
                                                                class="form-control" value="{{$user->username}}" id="username" maxlength="20" >
                                                        </div>
                                                        <div class="form-group mb-4 bmd-form-group">
                                                            <label class="bmd-label-floating">Email </label>
                                                            <input name="email" type="email"
                                                                class="form-control" value="{{$user->email}}" maxlength="100" >
                                                        </div>
                                                        <div class="form-group mb-4 bmd-form-group">
                                                            <label class="bmd-label-floating">Role <span class="red">*</span></label>
                                                            <select class="form-control select2" name="role">
                                                                @if($user->role == 5) 
                                                                <option value="5" selected="">Karyawan</option>
                                                                <option value="1">Admin</option>
                                                                @endif

                                                                @if($user->role == 1)
                                                                @if(Auth::user()->role == '1')
                                                                <option value="5">Karyawan</option>
                                                                <option value="1" selected="">Admin</option>
                                                                @endif
                                                                @endif
                                                            </select>
                                                        </div>
                                                        <div class="form-group mb-4 bmd-form-group">
                                                            <label class="bmd-label-floating">Password <span class="red">*</span><span class="red" style="font-size: 12px"> Hanya diisi jika ingin mengganti password</span></label>
                                                            <input name="password" type="password"
                                                                class="form-control" value="{{old('password')}}"maxlength="100">
                                                        </div>
                                                        <div class="form-group mb-4 bmd-form-group">
                                                            <label class="bmd-label-floating">Konfirmasi Password<span class="red">*</span><span class="red" style="font-size: 12px"> Hanya diisi jika ingin mengganti password</span></label>
                                                            <input name="password_confirm" type="password"
                                                                class="form-control" value="{{old('password_confirm')}}" maxlength="100">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 10px">
                                <div class="col-md-12">
                                    <button class="btn btn-success save pull-right mb-3" type="button" id="btn-submit">
                                        <i class="fa fa-save"></i>
                                        <span>Simpan</span>
                                    </button>
                                    <button class="btn btn-success save pull-right mb-3" id="btn-submit-loading" disabled="">
                                        <i class="fa fa-spinner fa-spin fa-fw"></i>
                                        <span> Simpan</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
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
<script type="text/javascript">
    $(document).ready(function () {
    
        $('#btn-submit').show();
        $('#btn-submit-loading').hide();

        $("#btn-submit").click(function(){
            swal({
                title: "Apakah anda yakin akan mengupdate data pengguna?",
                text: 'Data yang dirubah dapat merubah data pada database.', 
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
                $("#user_form").submit()
              } else {
                swal("Proses Perubahan Data Pengguna Dibatalkan!", {
                  icon: "error",
                });
              }
            });
        });

        $("#user_form").submit(function(){
            if($("#user_form").valid()){
                $('#btn-submit').hide();
                $('#btn-submit-loading').show();
            }else{
                return false;
            }
        });

        if($("#user_form").length > 0) {
            $("#user_form").validate({
                rules: {
                    name: {
                        required: true,
                        maxlength: 100,
                    },
                    username: {
                        required: true,
                        maxlength: 20,
                    },
                    role: {
                        required: true
                    },
                },
                messages: {
                    name: {
                        required : 'Data Nama harus diisi',
                        maxlength: "Data Nama tidak boleh lebih dari 100 kata",
                    },
                    username: {
                        required : 'Data username harus diisi',
                        maxlength: "Data username tidak boleh lebih dari 20 kata ",
                    },
                    role: {
                        required : 'Data Role harus diisi',
                    },
                },
            })
        }

    });

</script>
@endpush