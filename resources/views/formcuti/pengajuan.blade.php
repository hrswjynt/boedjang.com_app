@extends('layouts.app_admin')
@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Form Pengajuan Cuti</h1>
    </div>
    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 ">Form Cuti <b>{{Auth::user()->name}}</b></h6>
                    <a href="{{ route('formcuti.index') }}" class="btn btn-info btn-sm add">
                        <i class="fa fa-arrow-left "></i>
                        <span class="span-display">Kembali</span>
                    </a>
                </div>
                <div class="card-body">
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
                    <form method="POST" action="{{route('formcuti.pengajuanpost')}}" id="cuti_form">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4 bmd-form-group">
                                    <label class="bmd-label-floating">Cabang </label>
                                    <input name="cabang" type="text" value="{{$karyawan->Cabang}} {{$karyawan->region}}"
                                    class="form-control" id="cabang" disabled>
                                </div>
                                <div class="form-group mb-4 bmd-form-group">
                                    <label class="bmd-label-floating">NIP </label>
                                    <input name="nip" type="text" value="{{$karyawan->NIP}}"
                                    class="form-control" id="nip" disabled>
                                </div>
                                <div class="form-group mb-4 bmd-form-group">
                                    <label class="bmd-label-floating">Nama </label>
                                    <input name="nama" type="text" value="{{$karyawan->NAMA}}"
                                    class="form-control" id="nama" disabled>
                                </div>
                                <div class="form-group mb-4 bmd-form-group">
                                    <label class="bmd-label-floating">Tanggal Mulai <span class="red">*</span></label>
                                    <input name="tanggal_mulai" type="date" value="{{date('Y-m-d')}}"
                                    class="form-control" id="tanggal_mulai" required="">
                                </div>
                                <div class="form-group mb-4 bmd-form-group">
                                    <label class="bmd-label-floating">Tanggal Akhir <span class="red">*</span></label>
                                    <input name="tanggal_akhir" type="date" value="{{date('Y-m-d')}}"
                                    class="form-control" id="tanggal_akhir" required="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4 bmd-form-group">
                                    <label class="bmd-label-floating">Jumlah Off <span class="red">*</span></label>
                                    <input name="jumlah_off" type="number" value="0"
                                    class="form-control" id="jumlah_off" required="">
                                </div>
                                <div class="form-group mb-4 bmd-form-group">
                                    <label class="bmd-label-floating">Sisa Cuti Tersedia</label>
                                    <input name="sisa_cuti" type="text" value="{{$karyawan->sisa_cuti}}"
                                    class="form-control" id="sisa_cuti" readonly="">
                                </div>
                                <div class="form-group mb-4 bmd-form-group">
                                    <label class="bmd-label-floating">Jumlah Cuti Diambil</label>
                                    <input name="cuti_diambil" type="text" value="0"
                                    class="form-control" id="cuti_diambil" readonly="">
                                </div>
                                <div class="form-group mb-4 bmd-form-group">
                                    <label class="bmd-label-floating">Sisa Cuti Akhir</label>
                                    <input name="cuti_akhir" type="text" value="0"
                                    class="form-control" id="cuti_akhir" readonly="">
                                </div>
                                <div class="form-group mb-4 bmd-form-group">
                                    <label class="bmd-label-floating">Lokasi Cuti <span class="red">*</span></label>
                                    <textarea name="lokasi_cuti" type="text" rows="3" class="form-control" id="lokasi_cuti" required=""></textarea>
                                </div>
                            </div>
                        </div>
                        
                            
                        <div class="row" style="margin-top: 10px">
                            <div class="col-md-12">
                                <button class="btn btn-primary save pull-right mb-3" type="button" id="btn-submit">
                                <i class="fas fa-file-upload"></i>
                                <span>Ajukan</span>
                                </button>
                                <button class="btn btn-primary save pull-right mb-3" id="btn-submit-loading" disabled="">
                                <i class="fa fa-spinner fa-spin fa-fw"></i>
                                <span> Ajukan</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('other-script')
<script type="text/javascript">
    var now = "{{date('Y-m-d')}}";
    $(document).ready(function(){
        hitung();
        $('#btn-submit').show();
        $('#btn-submit-loading').hide();

        $("#tanggal_mulai").change(function(){
            hitung();
        });

        $("#tanggal_akhir").change(function(){
            hitung();
        });

        $("#jumlah_off").change(function(){
            hitung();
        });

        function hitung(){
            if($("#tanggal_mulai").val() > $("#tanggal_akhir").val()){
                $("#tanggal_mulai").val(now);
                $("#tanggal_akhir").val(now);
                hitung();
                return swal("Tanggal mulai tidak boleh lebih dari tanggal akhir", {
                  icon: "error",
                });
            }else{
                date1 = new Date($("#tanggal_mulai").val());
                date2 = new Date($("#tanggal_akhir").val());
                diffTime = (date2 - date1);
                diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                $('#cuti_diambil').val(diffDays+1-$("#jumlah_off").val());
                $('#cuti_akhir').val($('#sisa_cuti').val()-$('#cuti_diambil').val());
                if($('#cuti_diambil').val() < 1){
                    $("#jumlah_off").val(0);
                    hitung();
                    return swal("Harap isi jumlah hari off dengan benar!", {
                      icon: "error",
                    });
                }
            }
        }

        $("#btn-submit").click(function(){
            if($('#cuti_akhir').val() < 0){
                return swal("Sisa cuti akhir tidak boleh kurang dari 0", {
                  icon: "error",
                });
            }else{
                if($("#cuti_form").valid()){
                    $('#btn-submit').hide();
                    $('#btn-submit-loading').show();
                    swal({
                        title: "Apakah anda yakin akan mengajukan Cuti?",
                        text: 'Data cuti yang diajukan tidak dapat dirubah.', 
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                      if (willDelete) {
                        $("#cuti_form").submit()
                      } else {
                        $('#btn-submit').show();
                        $('#btn-submit-loading').hide();
                        swal("Proses Pengajuan Cuti Dibatalkan!", {
                          icon: "error",
                        });
                      }
                    });
                }else{
                    return swal("Pengisian form tidak valid", {
                      icon: "error",
                    });
                }
            }
        });

        if($("#cuti_form").length > 0) {
            $("#cuti_form").validate({
                rules: {
                    tanggal_mulai: {
                        required: true,
                    },
                    tanggal_akhir: {
                        required: true,
                    },
                    jumlah_off: {
                        required: true
                    },
                    lokasi_cuti: {
                        required: true
                    }
                },
                messages: {
                    tanggal_mulai: {
                        required : 'Data Tanggal Mulai harus diisi',
                    },
                    tanggal_akhir: {
                        required : 'Data Tanggal Akhir harus diisi',
                    },
                    jumlah_off: {
                        required : 'Data Jumlah Off harus diisi',
                    },
                    lokasi_cuti: {
                        required : 'Data Lokasi Cuti harus diisi',
                    }
                },
            })
        }

    });
</script>
@endpush