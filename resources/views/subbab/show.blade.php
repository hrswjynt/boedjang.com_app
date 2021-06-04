@extends('layouts.app_admin')
@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Sub Bab</h1>
    </div>
    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6><b>Detail Sub Bab</b></h6>
                    <a href="{{ route('subbab.index') }}" class="btn btn-primary btn-sm add">
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
                                <div class="card">
                                    <div class="card-body">
                                        <div class="container-fluid mt-3">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group mb-4 bmd-form-group">
                                                        <label>Judul <span class="red">*</span></label>
                                                        <input name="title" type="text"
                                                        class="form-control" value="{{$subbab->title}}" tabindex="1" id="title" maxlength="200" disabled="">
                                                    </div>
                                                    <div class="form-group mb-4 bmd-form-group">
                                                        <label>Slug <span class="red">*</span></label>
                                                        <input name="slug" type="text"
                                                        class="form-control" value="{{$subbab->slug}}" tabindex="1" id="slug" maxlength="200" disabled="">
                                                    </div>
                                                    <div class="form-group mb-4 bmd-form-group">
                                                        <label>Publish SOP <span class="red">*</span></label>
                                                        <select class="form-control" name="publish" disabled="">
                                                            @if($subbab->publish == 0)
                                                            <option value="1">Ya</option>
                                                            <option value="0" selected="">Tidak</option>
                                                            @endif

                                                            @if($subbab->publish == 1)
                                                            <option value="1" selected="">Ya</option>
                                                            <option value="0">Tidak</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                    <div class="form-group mb-4 bmd-form-group">
                                                        <label>Bab<span class="red">*</span></label>
                                                        <select class="select2" name="type" id="type" class="form-control" style="width: 100%" disabled>   
                                                            @foreach($bab as $b)
                                                            @if($b->id == $subbab->bab)
                                                            <option value="{{$b->id}}" selected="">{{$b->name}}</option>
                                                            @else
                                                            <option value="{{$b->id}}">{{$b->name}}</option>
                                                            @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                
                                                    <div class="form-group mb-4 bmd-form-group">
                                                        <label>Konten <span class="red">*</span></label>
                                                        <div class="bg-gray-200" style="padding: 10px">
                                                            {!! $subbab->content !!}
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('other-script')
<script type="text/javascript">
var url_delete = "{{url('subbab-delete')}}";
var base_url = "{{ url('/') }}";
</script>
@endpush