@extends('layouts.app_admin')
@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Tugas Ticket</h1>
    </div>
    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 ">Edit Tugas Ticket <b>{{$ticket->code}}</b></h6>
                    <a href="{{ route('task-ticket.index') }}" class="btn btn-info btn-sm add">
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
                    <form method="POST" action="{{url('taskticketupdate/'.$ticket->id)}}" id="ticket_form">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4 bmd-form-group">
                                    <label class="bmd-label-floating">Department Asal<span class="red">*</span></label>
                                    <select class="select2" name="from_department" id="from_department" class="form-control" style="width: 100%" disabled>   
                                        @foreach($department as $d)
                                        @if($d->id == $ticket->from_departments->id)
                                        <option value="{{$d->id}}" selected>{{$d->name}}</option>
                                        @else
                                        <option value="{{$d->id}}">{{$d->name}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-4 bmd-form-group">
                                    <label class="bmd-label-floating">Department Tujuan<span class="red">*</span></label>
                                    <select class="select2" name="for_department" id="for_department" class="form-control" style="width: 100%" disabled>
                                        @foreach($department as $d)
                                        @if($d->id == $ticket->for_departments->id)
                                        <option value="{{$d->id}}" selected>{{$d->name}}</option>
                                        @else
                                        <option value="{{$d->id}}">{{$d->name}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-4 bmd-form-group">
                                    <label class="bmd-label-floating">Platform<span class="red">*</span></label>
                                    <select class="select2" name="platform" id="platform" class="form-control" style="width: 100%" disabled>
                                        @foreach($platform as $d)
                                        @if($d->id == $ticket->platforms->id)
                                        <option value="{{$d->id}}" selected>{{$d->name}}</option>
                                        @else
                                        <option value="{{$d->id}}">{{$d->name}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-4 bmd-form-group">
                                    <label class="bmd-label-floating">Kategori<span class="red">*</span></label>
                                    <select class="select2" name="category" id="category" class="form-control" style="width: 100%" disabled>
                                        @foreach($category as $d)
                                        @if($d->id == $ticket->categories->id)
                                        <option value="{{$d->id}}" selected>{{$d->name}}</option>
                                        @else
                                        <option value="{{$d->id}}">{{$d->name}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-4 bmd-form-group">
                                    <label class="bmd-label-floating">Prioritas<span class="red">*</span></label>
                                    <select class="select2" name="priority" id="priority" class="form-control" style="width: 100%">
                                        @foreach($priority as $d)
                                        @if($d->id == $ticket->priorities->id)
                                        <option value="{{$d->id}}" selected>{{$d->name}}</option>
                                        @else
                                        <option value="{{$d->id}}">{{$d->name}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-4 bmd-form-group">
                                    <label class="bmd-label-floating">Status<span class="red">*</span></label>
                                    <select class="select2" name="status" id="status" class="form-control" style="width: 100%">
                                        @foreach($status as $d)
                                        @if($d->id == $ticket->statuses->id)
                                        <option value="{{$d->id}}" selected>{{$d->name}}</option>
                                        @else
                                        <option value="{{$d->id}}">{{$d->name}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                
                                <div class="form-group mb-4 bmd-form-group">
                                    <label class="bmd-label-floating">Level<span class="red">*</span></label>
                                    <select class="select2" name="level" id="level" class="form-control" style="width: 100%" required>
                                    <option value="">Pilih Level</option>
                                        @foreach($level as $d)
                                        @if($ticket->levels !== null)
                                        @if($d->id == $ticket->levels->id)
                                        <option value="{{$d->id}}" selected>{{$d->name}}</option>
                                        @else
                                        <option value="{{$d->id}}">{{$d->name}}</option>
                                        @endif
                                        @else
                                        <option value="{{$d->id}}">{{$d->name}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-4 bmd-form-group">
                                    <label class="bmd-label-floating">Delegasi<span class="red">*</span></label>
                                    <select class="select2" name="for_user" id="for_user" class="form-control" style="width: 100%" disabled>
                                    <option value="">Pilih Delegasi</option>
                                        @foreach($userdata as $d)
                                        @if($ticket->for_users !== null)
                                        @if($d->departments->id === $ticket->for_departments->id)
                                        @if($d->id == $ticket->for_users->id)
                                        <option value="{{$d->id}}" selected>{{$d->fullname}}</option>
                                        @else
                                        <option value="{{$d->id}}">{{$d->fullname}}</option>
                                        @endif
                                        @endif
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-4 bmd-form-group">
                                    <label class="bmd-label-floating">Pembuat Ticket </label>
                                    <input name="nama" type="text" value="{{$ticket->from_users->fullname}}"
                                    class="form-control" id="nama" disabled>
                                </div>
                                <div class="form-group mb-4 bmd-form-group">
                                    <label class="bmd-label-floating">Judul Pengaduan <span class="red">*</span></label>
                                    <input name="title" type="text" class="form-control" id="title" value="{{$ticket->title}}" disabled>
                                </div>
                                <div class="form-group mb-4 bmd-form-group">
                                    <label class="bmd-label-floating">Deskripsi <span class="red">*</span></label>
                                    <textarea disabled name="description" type="text" rows="3" class="form-control" id="description">{!! $ticket->description !!}</textarea>
                                </div>
                            </div>
                            @if(count($attachment) > 0)
                            <div class="col-md-12">
                                <div class="form-group mb-4 bmd-form-group">
                                    <label class="bmd-label-floating">Attachment <span class="red">*</span></label>
                                    <div class="row">
                                    @foreach($attachment as $attach)
                                    <?php
                                        $file = explode(".",$attach->filename);
                                        $cek = 0;
                                        if($file[3] == 'png' ||$file[3] == 'jpg' || $file[3] == 'jpeg' || $file[3] == 'gif' ||$file[3] == 'PNG' ||$file[3] == 'JPG' || $file[3] == 'JPEG' || $file[3] == 'GIF'){
                                            $cek = 1;
                                        }
                                    ?>
                                    @if($cek == 1)
                                    <div class="col-md-2">
                                        <a href="{{$attach->filename}}" target="__blank" class="image-trigger">
                                            <img src="{{$attach->filename}}" alt="attach" class="img-thumbnail shadow" style="padding:10px" onerror="this.onerror=null;this.src='{{asset('images/fileicon.jpg')}}';">
                                        </a>
                                        <p style="zoom: 70%;text-align:center">{{$attach->filename}}</p>
                                    </div>
                                    @else
                                    <div class="col-md-2">
                                        <a href="{{$attach->filename}}" target="__blank">
                                            <img src="{{asset('images/fileicon.jpg')}}" alt="attach" class="img-thumbnail shadow" style="padding:10px">
                                        </a>
                                        <p style="zoom: 70%;text-align:center"><b>Download</b> {{$attach->filename}}</p>
                                    </div>
                                    @endif
                                    @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif
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
                    title: "Apakah anda yakin akan mengupdate ticket?",
                    text: 'Data ticket yang diajukan akan masuk ke database.', 
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
                        swal("Update Ticket Dibatalkan!", {
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
                    },
                    level: {
                        required: true
                    },
                    for_user: {
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
                    },
                    level: {
                        required : 'Data Level harus diisi',
                    },
                    for_user: {
                        required : 'Data Delegasi harus diisi',
                    }
                },
            })
        }

    });
</script>
@endpush