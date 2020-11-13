@extends('layouts.app_admin')
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Social Media</h1>
        <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
    </div>
    <!-- Content Row -->
    <div class="row">
        <!-- Area Chart -->
        <div class="col-xl-12">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Edit Social Media</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-dismissible" id="success-alert">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <p>{{ $message }}</p>
                    </div>
                    @endif
                    <form method="POST" action="{{route('socialmedia.store')}}" id="sc_form">
                        @csrf
                        <div class="container-fluid mt-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="container-fluid mt-3">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group mb-4 bmd-form-group">
                                                            <label class="bmd-label-floating">Instagram<span class="red">*</span></label>
                                                            <input name="instagram" type="text"
                                                                class="form-control" value="{!! DB::table('social_media')->where('type','instagram')->first()->url !!}" required="">
                                                        </div>
                                                        <div class="form-group mb-4 bmd-form-group">
                                                            <label class="bmd-label-floating">Facebook<span class="red">*</span></label>
                                                            <input name="facebook" type="text"
                                                                class="form-control" value="{!! DB::table('social_media')->where('type','facebook')->first()->url !!}" required="">
                                                        </div>
                                                        <div class="form-group mb-4 bmd-form-group">
                                                            <label class="bmd-label-floating">Tiktok<span class="red">*</span></label>
                                                            <input name="tiktok" type="text"
                                                                class="form-control" value="{!! DB::table('social_media')->where('type','tiktok')->first()->url !!}" required="">
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
<!-- /.container-fluid -->
@endsection

@push('other-script')

<script type="text/javascript">
    var base_url = "{{ url('') }}";
    $(document).ready(function(){
        $('#btn-submit').show();
        $('#btn-submit-loading').hide();

        $("#btn-submit").click(function(){
            swal({
                title: "Apakah anda yakin akan mengedit data social media?",
                text: 'Data yang ditambahkan dapat merubah data pada database.', 
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
                $("#sc_form").submit()
              } else {
                swal("Proses Pengeditan Data Social Media Dibatalkan!", {
                  icon: "error",
                });
              }
            });
        });

        $("#sc_form").submit(function(){
            if($("#sc_form").valid()){
                $('#btn-submit').hide();
                $('#btn-submit-loading').show();
            }else{
                return false;
            }
        });

        if($("#sc_form").length > 0) {
            $("#sc_form").validate({
                rules: {
                    instagram: {
                        required: true,
                    },
                    facebook: {
                        required: true,
                    },
                    tiktok: {
                        required: true,
                    },
                },
                messages: {
                    instagram: {
                        required : 'Data URL Instagram harus diisi',
                    },
                    facebook: {
                        required : 'Data URL Facebook Menu harus diisi',
                    },
                    tiktok: {
                        required : 'Data URL Tiktok harus diisi',
                    },
                },
            })
        }
    });
</script>
@endpush