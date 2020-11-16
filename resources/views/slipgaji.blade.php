@extends('layouts.app_admin')
@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Slip Gaji</h1>
    </div>
    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 ">Slip Gaji <b>{{Auth::user()->name}}</b> ( {{$karyawan->NAMA}} - {{$karyawan->NIP}} ) </h6>
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
                    <form method="POST" action="{{route('slipgaji.store')}}" id="slipgaji_form">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="container-fluid mt-3">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group mb-4 bmd-form-group">
                                                        <label class="bmd-label-floating">Periode Bulan <span class="red">*</span></label>
                                                        <input name="sdate" type="month" value="{{$date1}}"
                                                        class="form-control" id="sdate" required="">
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
                                <i class="fas fa-file-upload"></i>
                                <span>Proses</span>
                                </button>
                                <button class="btn btn-success save pull-right mb-3" id="btn-submit-loading" disabled="">
                                <i class="fa fa-spinner fa-spin fa-fw"></i>
                                <span> Proses</span>
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
            // if($("#sdate").val().split('-')[2] != '16' || $("#sdate").val() > $("#edate").val()){
            //     return swal("Tanggal awal harus bertanggal 16 dan tidak boleh melebihi tanggal akhir!", {
            //           icon: "error",
            //         });
            // }
            // else if($("#edate").val().split('-')[2] != '15' || $("#sdate").val() > $("#edate").val()){
            //     return swal("Tanggal akhir harus bertanggal 15 dan tidak boleh kurang dari tanggal awal!", {
            //           icon: "error",
            //         });
            // }else{

                $('#btn-submit').hide();
                $('#btn-submit-loading').show();
                $("#slipgaji_form").submit();
            // }
            
        });

    });
</script>
@endpush