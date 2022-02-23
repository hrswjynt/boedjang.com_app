@extends('layouts.app_admin')
@section('content')
<style type="text/css">
    input[type='radio'] { 
        transform: scale(1.7);
    }
    .form-check-input{
        margin: 5px;
    }
    .form-check-label{
        zoom: 70%;
    }
    .form-check-header{
        zoom: 90%;
    }
    .bmd-label-floating{
        zoom: 90%;
    }
    
</style>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Feedback Atasan</h1>
    </div>
    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 ">Feedback Atasan <b>{{Auth::user()->name}}</b></h6>
                    <a href="{{ route('feedback.index') }}" class="btn btn-info btn-sm add">
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
                    <form method="POST" action="{{route('feedback.pengajuanpost')}}" id="feedback_form">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group bmd-form-group">
                                    <label>Atasan<span class="red">*</span></label>
                                    <select class="select2" name="atasan" id="atasan" class="form-control" style="width: 100%" required>
                                        <option value="">Pilih Atasan</option>
                                        @foreach($atasan as $t)
                                        <option value="{{$t->NIP}}">{{$t->NAMA}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @php $i=0;$j=1; @endphp
                            @foreach($feedback as $data)
                            <div class="col-md-12">
                                <hr>
                                @if($i==0)
                                <div class="form-group form-check-header text-center">
                                    <b>{{$data->kategori_nama}}</b>
                                </div>
                                <hr>
                                @elseif($feedback[$i]->kategori !== $feedback[$i-1]->kategori)
                                <div class="form-group form-check-header text-center">
                                    <b>{{$data->kategori_nama}}</b>
                                </div>
                                <hr>
                                @endif
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="radio[{{$data->id}}-0" name="radio[{{$data->id}}]" value="" required style="zoom:1%">
                                </div>
                                <div class="form-group form-check-header">
                                    {{$j}}. {{$data->isi}}
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="radio[{{$data->id}}-1" name="radio[{{$data->id}}]" value="4">
                                    <label class="form-check-label" for="radio[{{$data->id}}-1">Sangat setuju</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="radio[{{$data->id}}-2" name="radio[{{$data->id}}]" value="3">
                                    <label class="form-check-label" for="radio[{{$data->id}}-2">Setuju</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="radio[{{$data->id}}-3" name="radio[{{$data->id}}]" value="2">
                                    <label class="form-check-label" for="radio[{{$data->id}}-3">Tidak Setuju</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="radio[{{$data->id}}-4" name="radio[{{$data->id}}]" value="1">
                                    <label class="form-check-label" for="radio[{{$data->id}}-4">Sangat Tidak Setuju</label>
                                </div>
                            </div>
                            @php $i++;$j++; @endphp
                            @endforeach

                            <div class="col-md-12">
                                <hr>
                                <div class="form-group form-check-header text-center">
                                    <b>PENILAIAN PRIBADI</b>
                                </div>
                                <hr>
                                <div class="form-group mb-4 bmd-form-group">
                                    <label>1. Apa hal yang paling anda sukai dari atasan anda? Jelaskan... </label>
                                    <textarea rows="5" name="alasan1" id="alasan1" class="form-control" required="">{!! old('alasan1') !!}</textarea>
                                    <label>2. Apa hal yang paling tidak anda sukai dari atasan anda? Jelaskan... </label>
                                    <textarea rows="5" name="alasan2" id="alasan2" class="form-control" required="">{!! old('alasan2') !!}</textarea>
                                    <label>3. Apa saran yang bisa anda berikan untuk atasan anda agar bisa menjadi lebih baik lagi kedepannya? </label>
                                    <textarea rows="5" name="alasan3" id="alasan3" class="form-control" required="">{!! old('alasan3') !!}</textarea>
                                    
                                    <hr>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="puas0" name="puas" value="" required style="zoom:1%">
                                    </div>
                                    <div class="form-group form-check-header">
                                    4. Secara garis besar seberapa puas anda dengan kinerja atasan anda?
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="puas1" name="puas" value="Sangat Puas">
                                        <label class="form-check-label" for="puas1">Sangat Puas</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="puas2" name="puas" value="Puas">
                                        <label class="form-check-label" for="puas2">Puas</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="puas3" name="puas" value="Tidak Puas">
                                        <label class="form-check-label" for="puas3">Tidak Puas</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="puas4" name="puas" value="Sangat Tidak Puas">
                                        <label class="form-check-label" for="puas4">Sangat Tidak Puas</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                            
                        <div class="row" style="margin-top: 10px">
                            <div class="col-md-12">
                                <button class="btn btn-primary save pull-right mb-3" type="button" id="btn-submit">
                                <i class="fas fa-file-upload"></i>
                                <span>Submit</span>
                                </button>
                                <button class="btn btn-primary save pull-right mb-3" id="btn-submit-loading" disabled="">
                                <i class="fa fa-spinner fa-spin fa-fw"></i>
                                <span> Submit</span>
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
            if($("#feedback_form").valid()){
                $('#btn-submit').hide();
                $('#btn-submit-loading').show();
                swal({
                    title: "Apakah anda yakin akan melakukan Feedback Atasan?",
                    text: 'Data Feedback Atasan yang sudah terinput tidak dapat dirubah.', 
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                    $("#feedback_form").submit()
                    } else {
                    $('#btn-submit').show();
                    $('#btn-submit-loading').hide();
                    swal("Proses  Feedback Atasan Dibatalkan!", {
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

        if($("#feedback_form").length > 0) {
            $("#feedback_form").validate({
                rules: {
                    alasan1: {
                        required: true,
                    },
                    alasan2: {
                        required: true,
                    },
                    alasan3: {
                        required: true
                    },
                    atasan: {
                        required: true
                    },
                },
                messages: {
                    alasan1: {
                        required : 'Data jawaban tidak boleh kosong!',
                    },
                    alasan2: {
                        required : 'Data jawaban tidak boleh kosong!',
                    },
                    alasan3: {
                        required : 'Data jawaban tidak boleh kosong!',
                    },
                    atasan: {
                        required : 'Data Atasan tidak boleh kosong!',
                    },
                },
            })
        }

    });
</script>
@endpush