@extends('layouts.app_admin')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-3">
            <h1 class="h3 mb-0 text-gray-800">Pengguna</h1>
        </div>
        <!-- Content Row -->
        <div class="col-md-12">
            <div class="card shadow mb-3">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    @if (Auth::user()->role == 1)
                        <h6><b>Edit Pengguna</b></h6>
                        <a href="{{ route('user.index') }}" class="btn btn-info btn-sm add">
                            <i class="fa fa-arrow-left "></i>
                            <span>Kembali</span>
                        </a>
                    @else
                        <h6><b>Edit Data</b></h6>
                        <a href="{{ route('dashboard') }}" class="btn btn-info btn-sm add">
                            <i class="fa fa-arrow-left "></i>
                            <span>Kembali</span>
                        </a>
                    @endif
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
                    <form method="POST" action="{{ route('user.update', $user->id) }}" id="user_form"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="container-fluid">
                            <div class="col-md-12">
                                <div class="form-group mb-3 bmd-form-group">
                                    <label>Foto Profil </label>
                                    <input name="gambar" type="file" class="form-control" value="" id="gambar"
                                        accept="image/*">
                                    @if ($user->gambar == null)
                                        <img id="img" src="{{ asset('admin/img/default.png') }}" alt="your image"
                                            height="100%"
                                            style="margin-top: 10px;margin-left: auto;margin-right: auto;width: 100%;max-width: 300px"
                                            accept="image/*" />
                                    @else
                                        <img id="img" src="{{ asset('images/profile/' . $user->gambar) }}"
                                            alt="your image" height="100%"
                                            style="margin-top: 10px;margin-left: auto;margin-right: auto;width: 100%;max-width: 300px"
                                            accept="image/*" />
                                    @endif
                                </div>
                                <div class="form-group mb-3 bmd-form-group">
                                    <label class="bmd-label-floating">Nama Pengguna <span class="red">*</span></label>
                                    <input name="name" type="text" class="form-control" value="{{ $user->name }}"
                                        id="name" maxlength="100">
                                </div>
                                <div class="form-group mb-3 bmd-form-group">
                                    <label class="bmd-label-floating">Username <span class="red">*</span></label>
                                    <input name="username" type="text" class="form-control" value="{{ $user->username }}"
                                        id="username" maxlength="20" @if (Auth::user()->role !== 1) readonly @endif>
                                </div>
                                <div class="form-group mb-3 bmd-form-group">
                                    <label class="bmd-label-floating">Email </label>
                                    <input name="email" type="email" class="form-control" value="{{ $user->email }}"
                                        maxlength="100">
                                </div>
                                <div class="form-group mb-3 bmd-form-group">
                                    <label class="bmd-label-floating">NIK </label>
                                    <input name="nik" type="text" class="form-control" value="{{ $karyawan->NIK }}" maxlength="30" required>
                                </div>
                                <div class="form-group mb-3 bmd-form-group">
                                    <label class="bmd-label-floating">No.HP </label>
                                    <input name="no_hp" type="text" class="form-control"
                                        value="{{ $karyawan->No_HP }}" maxlength="20" required>
                                </div>
                                <div class="form-group mb-3 bmd-form-group">
                                    <label class="bmd-label-floating">Alamat </label>
                                    <textarea name="alamat" class="form-control" id="alamat" rows="2">{!! $karyawan->alamat !!}</textarea>
                                </div>
                                @if (Auth::user()->role == 1)
                                    <div class="form-group mb-3 bmd-form-group">
                                        <label class="bmd-label-floating">Presensi Online <span class="red">*</span></label>
                                        <select class="form-control select2" name="presensi_online" style="width: 100%">
                                            <option value="1" @if($user->presensi_online) selected="" 
                                                @endif>Aktif</option>
                                            <option value="0" @if(!$user->presensi_online) selected="" 
                                                @endif>Tidak Aktif</option>
                                        </select>
                                    </div>
                                @else
                                    <div class="form-group mb-3 bmd-form-group">
                                        <label class="bmd-label-floating">Presensi Online <span class="red">*</span></label>
                                        <input type="text" class="form-control" disabled @if($user->presensi_online) value="Aktif" @else value="Tidak Aktif" @endif>
                                    </div>
                                @endif
                                @if (Auth::user()->role == 1)
                                    <div class="form-group mb-3 bmd-form-group">
                                        <label class="bmd-label-floating">Role <span class="red">*</span></label>
                                        <select class="form-control select2" name="role" style="width: 100%">
                                            @if ($user->role == 5)
                                                <option value="5" selected="">Karyawan</option>
                                                <option value="3">Manager</option>
                                                <option value="2">SPV</option>
                                                <option value="4">GA</option>
                                                <option value="1">Admin</option>
                                                <option value="6">Mitra Bakso</option>
                                            @endif

                                            @if ($user->role == 3)
                                                <option value="5">Karyawan</option>
                                                <option value="3" selected="">Manager</option>
                                                <option value="2">SPV</option>
                                                <option value="4">GA</option>
                                                <option value="1">Admin</option>
                                                <option value="6">Mitra Bakso</option>
                                            @endif

                                            @if ($user->role == 4)
                                                <option value="5">Karyawan</option>
                                                <option value="3">Manager</option>
                                                <option value="2">SPV</option>
                                                <option value="4" selected="">GA</option>
                                                <option value="1">Admin</option>
                                                <option value="6">Mitra Bakso</option>
                                            @endif

                                            @if ($user->role == 2)
                                                <option value="5">Karyawan</option>
                                                <option value="3">Manager</option>
                                                <option value="2" selected="">SPV</option>
                                                <option value="4">GA</option>
                                                <option value="1">Admin</option>
                                                <option value="6">Mitra Bakso</option>
                                            @endif

                                            @if ($user->role == 6)
                                                <option value="5">Karyawan</option>
                                                <option value="3">Manager</option>
                                                <option value="2">SPV</option>
                                                <option value="4">GA</option>
                                                <option value="1">Admin</option>
                                                <option value="6" selected="">Mitra Bakso</option>
                                            @endif

                                            @if ($user->role == 1)
                                                @if (Auth::user()->role == '1')
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
                                @else
                                    <input type="hidden" name="role" value="5">
                                @endif
                                <div class="form-group mb-3 bmd-form-group">
                                    <label class="bmd-label-floating">Password <span class="red">*</span><span
                                            class="red" style="font-size: 12px"> Hanya diisi jika ingin mengganti
                                            password</span></label>
                                    <input name="password" type="password" class="form-control"
                                        value="{{ old('password') }}"maxlength="100">
                                </div>
                                <div class="form-group mb-3 bmd-form-group">
                                    <label class="bmd-label-floating">Konfirmasi Password<span
                                            class="red">*</span><span class="red" style="font-size: 12px">
                                            Hanya diisi jika ingin mengganti password</span></label>
                                    <input name="password_confirm" type="password" class="form-control"
                                        value="{{ old('password_confirm') }}" maxlength="100">
                                </div>
                            </div>
                            <div class="row" style="margin-top: 10px">
                                <div class="col-md-12">
                                    <button class="btn btn-success save pull-right mb-3" type="button" id="btn-submit">
                                        <i class="fa fa-save"></i>
                                        <span>Simpan</span>
                                    </button>
                                    <button class="btn btn-success save pull-right mb-3" id="btn-submit-loading"
                                        disabled="">
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
    <script type="text/javascript">
        var base_url = "{{ url('/') }}";
    </script>

@endsection

@push('other-script')
    <script type="text/javascript">
        $(document).ready(function() {

            $('#btn-submit').show();
            $('#btn-submit-loading').hide();

            $("#btn-submit").click(function() {
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

            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $('#img').attr('src', e.target.result);
                    }

                    reader.readAsDataURL(input.files[0]); // convert to base64 string
                }
            }

            $("#gambar").change(function() {
                $('#img').show();
                readURL(this);
            });

            var uploadField = document.getElementById("gambar");

            uploadField.onchange = function() {
                if (this.files[0].size > 2097152) {
                    swal("Data Gambar terlalu besar! Maksimal ukuran adalah 2 MB", {
                        icon: "error",
                    });
                    this.value = "";
                    $('#img').hide();
                };
            };

            $("#user_form").submit(function() {
                if ($("#user_form").valid()) {
                    $('#btn-submit').hide();
                    $('#btn-submit-loading').show();
                } else {
                    return false;
                }
            });

            if ($("#user_form").length > 0) {
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
                        nik: {
                            required: true,
                            maxlength: 30,
                        },
                        no_hp: {
                            required: true,
                            maxlength: 20,
                        },
                    },
                    messages: {
                        name: {
                            required: 'Data Nama harus diisi',
                            maxlength: "Data Nama tidak boleh lebih dari 100 kata",
                        },
                        username: {
                            required: 'Data username harus diisi',
                            maxlength: "Data username tidak boleh lebih dari 20 kata ",
                        },
                        role: {
                            required: 'Data Role harus diisi',
                        },
                        nik: {
                            required: 'Data NIK harus diisi',
                            maxlength: "Data NIK tidak boleh lebih dari 30 kata ",
                        },
                        no_hp: {
                            required: 'Data No.HP harus diisi',
                            maxlength: "Data No.HP tidak boleh lebih dari 20 kata ",
                        },
                    },
                })
            }

        });
    </script>
@endpush
