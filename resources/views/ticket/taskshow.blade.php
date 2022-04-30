@extends('layouts.app_admin')
@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Tugas Ticket</h1>
    </div>
    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 ">Detail Tugas Ticket <b>{{$ticket->code}}</b></h6>
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
                    <form method="POST" action="#" id="ticket_form">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4 bmd-form-group">
                                    <label class="bmd-label-floating">Department Asal </label>
                                    <select class="select2" name="from_department" id="from_department" class="form-control" style="width: 100%" disabled>   
                                        <option value="{{$ticket->from_departments->id}}" selected>{{$ticket->from_departments->name}}</option>
                                    </select>
                                </div>
                                <div class="form-group mb-4 bmd-form-group">
                                    <label class="bmd-label-floating">Department Tujuan </label>
                                    <select class="select2" name="for_department" id="for_department" class="form-control" style="width: 100%" disabled>   
                                    <option value="{{$ticket->for_departments->id}}" selected>{{$ticket->for_departments->name}}</option>
                                    </select>
                                </div>
                                <div class="form-group mb-4 bmd-form-group">
                                    <label class="bmd-label-floating">Platform </label>
                                    <select class="select2" name="platform" id="platform" class="form-control" style="width: 100%" disabled>
                                    <option value="{{$ticket->platforms->id}}" selected>{{$ticket->platforms->name}}</option>
                                    </select>
                                </div>
                                <div class="form-group mb-4 bmd-form-group">
                                    <label class="bmd-label-floating">Kategori </label>
                                    <select class="select2" name="category" id="category" class="form-control" style="width: 100%" disabled>
                                        <option value="{{$ticket->categories->id}}" selected>{{$ticket->categories->name}}</option>
                                    </select>
                                </div>
                                <div class="form-group mb-4 bmd-form-group">
                                    <label class="bmd-label-floating">Prioritas </label>
                                    <select class="select2" name="priority" id="priority" class="form-control" style="width: 100%" disabled>
                                        <option value="{{$ticket->priorities->id}}" selected>{{$ticket->priorities->name}}</option>
                                    </select>
                                </div>
                                <div class="form-group mb-4 bmd-form-group">
                                    <label class="bmd-label-floating">Status </label>
                                    <select class="select2" name="status" id="status" class="form-control" style="width: 100%" disabled>
                                        <option value="{{$ticket->statuses->id}}" selected>{{$ticket->statuses->name}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4 bmd-form-group">
                                    <label class="bmd-label-floating">Level </label>
                                    <select class="select2" name="level" id="level" class="form-control" style="width: 100%" disabled>
                                        @if($ticket->levels !== null)
                                        <option value="{{$ticket->levels->id}}" selected>{{$ticket->levels->name}}</option>
                                        @else
                                        <option value="" selected>Belum Ditentukan</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group mb-4 bmd-form-group">
                                    <label class="bmd-label-floating">Delegasi </label>
                                    <select class="select2" name="from_user" id="from_user" class="form-control" style="width: 100%" disabled>
                                        @if($ticket->for_users !== null)
                                        <option value="{{$ticket->for_users->id}}" selected>{{$ticket->for_users->fullname}}</option>
                                        @else
                                        <option value="" selected>Belum Ditentukan</option>
                                        @endif
                                    </select>
                                </div>
                                
                                <div class="form-group mb-4 bmd-form-group">
                                    <label class="bmd-label-floating">Pembuat Ticket </label>
                                    <input name="nama" type="text" value="{{$ticket->from_users->fullname}}"
                                    class="form-control" id="nama" disabled>
                                </div>
                                <div class="form-group mb-4 bmd-form-group">
                                    <label class="bmd-label-floating">Judul Pengaduan  </label>
                                    <input name="title" type="text" class="form-control" id="title" disabled="" value="{{$ticket->title}}">
                                </div>
                                <div class="form-group mb-4 bmd-form-group">
                                    <label class="bmd-label-floating">Deskripsi  </label>
                                    <textarea name="description" type="text" rows="3" class="form-control" id="description" disabled="">{!! $ticket->description !!}</textarea>
                                </div>
                            </div>
                            @if(count($ticket->attachments) > 0)
                            <div class="col-md-12">
                                <div class="form-group mb-4 bmd-form-group">
                                    <label class="bmd-label-floating">Attachment <span class="red">*</span></label>
                                    <div class="row">
                                    @foreach($ticket->attachments as $attach)
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
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('other-script')
<script type="text/javascript">
</script>
@endpush