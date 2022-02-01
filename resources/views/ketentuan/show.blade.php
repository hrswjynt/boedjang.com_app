@extends('layouts.app_admin')
@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Ketentuan</h1>
    </div>
    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6><b>Detail Ketentuan</b></h6>
                    <a href="{{ route('ketentuan.index') }}" class="btn btn-primary btn-sm add">
                        <i class="fa fa-arrow-left"></i>
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
                    <div class="container-fluid mt-3">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-4 bmd-form-group">
                                    <label>Judul <span class="red">*</span></label>
                                    <input name="title" type="text"
                                    class="form-control" value="{{$ketentuan->title}}" tabindex="1" id="title" maxlength="200" disabled="">
                                </div>
                                <div class="form-group mb-4 bmd-form-group">
                                    <label>Slug <span class="red">*</span></label>
                                    <input name="slug" type="text"
                                    class="form-control" value="{{$ketentuan->slug}}" tabindex="1" id="slug" maxlength="200" disabled="">
                                </div>
                                <div class="form-group mb-4 bmd-form-group">
                                    <label>Publish Ketentuan <span class="red">*</span></label>
                                    <select class="form-control" name="publish" disabled="">
                                        @if($ketentuan->publish == 0)
                                        <option value="1">Ya</option>
                                        <option value="0" selected="">Tidak</option>
                                        @endif

                                        @if($ketentuan->publish == 1)
                                        <option value="1" selected="">Ya</option>
                                        <option value="0">Tidak</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group mb-4 bmd-form-group">
                                <label class="bmd-label-floating">Urutan <span class="red">*</span></label>
                                    <input name="sequence" type="number"
                                        class="form-control" value="{{$ketentuan->sequence}}" id="name" disabled="">
                                </div>
                            
                                <div class="form-group mb-4 bmd-form-group">
                                    <label>Konten <span class="red">*</span></label>
                                    <div class="bg-gray-200" style="padding: 10px">
                                        {!! $ketentuan->content !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('other-script')
<script type="text/javascript">
var url_delete = "{{url('ketentuan-delete')}}";
var base_url = "{{ url('/') }}";
</script>
@endpush