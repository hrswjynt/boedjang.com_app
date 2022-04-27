@extends('layouts.app_admin')
@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <style>
        .select2-selection__rendered {
            line-height: 31px !important;
        }
        .select2-container .select2-selection--single {
            height: 35px !important;
        }
        .select2-selection__arrow {
            height: 34px !important;
        }
    </style>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Buat Ticket</h1>
    </div>
    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 ">Ticket <b>{{Auth::user()->name}}</b></h6>
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
                    <div class="progress" style="margin-bottom: 10px">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 50%" id="progress"></div>
                    </div>
                    <form method="POST" action="{{route('ticket.pengajuanpost')}}" id="ticket_form" enctype="multipart/form-data">
                        @csrf
                        <div id="department-process">
                            <div class="col-md-12">
                                <div class="form-group mb-4 bmd-form-group">
                                    <label class="bmd-label-floating">Kemana Tujuan Ticket Anda?</label>
                                    <select class="select2" name="for_department" id="for_department" class="form-control" style="width: 100%" required>   
                                        @foreach($department as $d)
                                        <option value="{{$d->id}}">{{$d->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row" style="text-align: center;">
                                <div class="col-md-12">
                                    <button class="btn btn-primary save pull-right mb-3" type="button" id="btn-process">
                                        <i class="fas fa-arrow-right"></i>
                                        <span>Proses</span>
                                    </button>
                                    <button class="btn btn-primary save pull-right mb-3" id="btn-process-loading" disabled="" style="display: none">
                                        <i class="fa fa-spinner fa-spin fa-fw"></i>
                                        <span> Proses</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" id="data-process" style="display: none">
                            <div class="row form-group mb-4 bmd-form-group">
                                <div class="col">
                                    <label class="bmd-label-floating">Nama </label>
                                    <input name="nama" type="text" value="{{$karyawan->NAMA}}"
                                    class="form-control" id="nama" disabled>
                                </div>
                                <div class="col">
                                    <label class="bmd-label-floating">Prioritas<span class="red">*</span></label>
                                    <select class="select2" name="priority" id="priority" class="form-control" style="width: 100%">
                                        @foreach($priority as $d)
                                        <option value="{{$d->id}}">{{$d->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                              </div>
                            <div class="form-group mb-4 bmd-form-group">
                                <label class="bmd-label-floating">Subject <span class="red">*</span></label>
                                <input name="title" type="text" class="form-control" id="title" required="">
                            </div>
                            <div class="form-group mb-4 bmd-form-group">
                                <label class="bmd-label-floating">Deskripsi <span class="red">*</span></label>
                                <textarea name="description" type="text" rows="3" class="form-control" id="description" required=""></textarea>
                            </div>
                            <h5> 
                                Attachment
                                <button class="btn btn-success btn-sm" type="button" id="btn-add-attc">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" type="button" id="btn-delete-attc">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </h5>
                            <div id="attachment-data"></div>
                            
                            <div style="margin-top: 10px">
                                <button class="btn btn-success save pull-right mb-3" type="button" id="btn-process-back">
                                    <i class="fas fa-arrow-left"></i>
                                    <span>Kembali</span>
                                </button>
                                <button class="btn btn-success save pull-right mb-3" id="btn-process-back-loading" disabled="">
                                    <i class="fa fa-spinner fa-spin fa-fw"></i>
                                    <span> Kembali</span>
                                </button>
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
    $(document).ready(function(){
        $('#department-process').show();
        $('#btn-process').show();
        
        $('#btn-process-back').show();
        $('#btn-process-back-loading').hide();
        $('#btn-submit-loading').hide();
        $('#data-process').hide();
        $('#btn-process-loading').hide();

        $("#btn-add-attc").click(function(){
            var add = '<div class="form-group mb-4 bmd-form-group attachment"><label>Attachment</label>'
                    +'<input name="files[]" type="file" class="form-control" value="">'
                    +'</div>';
            $("#attachment-data").append(add);
        });

        $("#btn-delete-attc").click(function(){
            $("#attachment-data").html('');
        });

        $("#btn-process").click(function(){
            document.getElementById("progress").style.width = "100%";
            $('#btn-process-back').show();
            $('#btn-process').hide();
            $('#btn-process-loading').show();
            setTimeout(function () {
                $('#department-process').hide();
                $('#btn-process-loading').hide();
                $('#data-process').show();
            }, 500);
        });

        $("#btn-process-back").click(function(){
            document.getElementById("progress").style.width = "50%";
            $('#btn-process-back').hide();
            $('#btn-process-back-loading').show();
            $('#btn-process').show();
            setTimeout(function () {
                $('#data-process').hide();
                $('#department-process').show();
                $('#btn-process-back-loading').hide();
            }, 500);
        });

        $("#btn-submit").click(function(){
            if($("#ticket_form").valid()){
                $('#btn-submit').hide();
                $('#btn-submit-loading').show();
                swal({
                    title: "Apakah anda yakin akan membuat ticket?",
                    text: 'Data ticket yang diajukan tidak dapat dirubah.', 
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $("#ticket_form").submit()
                    } else {
                        $('#btn-submit').show();
                        $('#btn-submit-loading').hide();
                        swal("Proses Pembuatan Ticket Dibatalkan!", {
                            icon: "error",
                        });
                    }
                });
            }else{
                return swal("Pengisian form tidak valid", {
                    icon: "error",
                });
            }
        });

        if($("#ticket_form").length > 0) {
            $("#ticket_form").validate({
                rules: {
                    category: {
                        required: true,
                    },
                    priority: {
                        required: true,
                    },
                    platform: {
                        required: true
                    },
                    from_department: {
                        required: true
                    },
                    for_department: {
                        required: true
                    }
                },
                messages: {
                    category: {
                        required : 'Data Kategori harus diisi',
                    },
                    priority: {
                        required : 'Data Prioritas harus diisi',
                    },
                    platform: {
                        required : 'Data Platform harus diisi',
                    },
                    from_department: {
                        required : 'Data Departemen Asal harus diisi',
                    },
                    for_department: {
                        required : 'Data Departemen Tujuan harus diisi',
                    }
                },
            })
        }

    });
</script>
@endpush