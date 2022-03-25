@extends('layouts.app_admin')
@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Buat Ticket</h1>
    </div>
    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 ">Ticket <b>{{Auth::user()->name}}</b></h6>
                    <a href="{{ route('ticket.index') }}" class="btn btn-info btn-sm add">
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
                    <form method="POST" action="{{route('ticket.pengajuanpost')}}" id="ticket_form">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4 bmd-form-group">
                                    <label class="bmd-label-floating">Department Asal<span class="red">*</span></label>
                                    <select class="select2" name="from_department" id="from_department" class="form-control" style="width: 100%" required>   
                                        @foreach($department as $d)
                                        <option value="{{$d->id}}">{{$d->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-4 bmd-form-group">
                                    <label class="bmd-label-floating">Department Tujuan<span class="red">*</span></label>
                                    <select class="select2" name="for_department" id="for_department" class="form-control" style="width: 100%" required>   
                                        @foreach($department as $d)
                                        <option value="{{$d->id}}">{{$d->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-4 bmd-form-group">
                                    <label class="bmd-label-floating">Platform<span class="red">*</span></label>
                                    <select class="select2" name="platform" id="platform" class="form-control" style="width: 100%" required>   
                                        @foreach($platform as $d)
                                        <option value="{{$d->id}}">{{$d->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-4 bmd-form-group">
                                    <label class="bmd-label-floating">Kategori<span class="red">*</span></label>
                                    <select class="select2" name="category" id="category" class="form-control" style="width: 100%" required>   
                                        @foreach($category as $d)
                                        <option value="{{$d->id}}">{{$d->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-4 bmd-form-group">
                                    <label class="bmd-label-floating">Prioritas<span class="red">*</span></label>
                                    <select class="select2" name="priority" id="priority" class="form-control" style="width: 100%" required>   
                                        @foreach($priority as $d)
                                        <option value="{{$d->id}}">{{$d->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4 bmd-form-group">
                                    <label class="bmd-label-floating">Nama </label>
                                    <input name="nama" type="text" value="{{$karyawan->NAMA}}"
                                    class="form-control" id="nama" disabled>
                                </div>
                                <div class="form-group mb-4 bmd-form-group">
                                    <label class="bmd-label-floating">Judul Pengaduan <span class="red">*</span></label>
                                    <input name="title" type="text" class="form-control" id="title" required="">
                                </div>
                                <div class="form-group mb-4 bmd-form-group">
                                    <label class="bmd-label-floating">Deskripsi <span class="red">*</span></label>
                                    <textarea name="description" type="text" rows="3" class="form-control" id="description" required=""></textarea>
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
    $(document).ready(function(){
        $('#btn-submit').show();
        $('#btn-submit-loading').hide();

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