@extends('layouts.app_admin')
@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Buku Pedoman</h1>
    </div>
    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6><b>Detail Buku Pedoman</b></h6>
                    <a href="{{ route('bukupedoman.index') }}" class="btn btn-primary btn-sm add">
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
                                                        class="form-control" value="{{$bukupedoman->title}}" tabindex="1" id="title" maxlength="200" disabled="">
                                                    </div>
                                                    <div class="form-group mb-4 bmd-form-group">
                                                        <label>Slug <span class="red">*</span></label>
                                                        <input name="slug" type="text"
                                                        class="form-control" value="{{$bukupedoman->slug}}" tabindex="1" id="slug" maxlength="200" disabled="">
                                                    </div>
                                                    <div class="form-group mb-4 bmd-form-group">
                                                        <label>Publish Buku Pedoman <span class="red">*</span></label>
                                                        <select class="form-control" name="publish" disabled="">
                                                            @if($bukupedoman->publish == 0)
                                                            <option value="1">Ya</option>
                                                            <option value="0" selected="">Tidak</option>
                                                            @endif

                                                            @if($bukupedoman->publish == 1)
                                                            <option value="1" selected="">Ya</option>
                                                            <option value="0">Tidak</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                    <div class="form-group mb-4 bmd-form-group">
                                                        <label>Gambar </label>
                                                        @if($bukupedoman->gambar == null)
                                                        <img id="img" src="{{asset('images/noimage.png')}}" alt="your image" height="100%" style="margin-top: 10px;width: 100%;height: auto;" />
                                                        @else
                                                        <img id="img" src="{{asset('images/bukupedoman/'.$bukupedoman->gambar)}}" alt="your image" height="100%" style="margin-top: 10px;width: 100%;height: auto;"/>
                                                        @endif
                                                    </div>
                                                    
                                                    <div class="form-group mb-4 bmd-form-group">
                                                        <label>Divisi<span class="red">*</span></label>
                                                            <select class="select2" multiple="multiple"  name="division[]" id="division" class="form-control" style="width: 100%" disabled="">   
                                                                @foreach($division as $j)
                                                                @if(in_array($j->id,$bukupedomandivision))
                                                                <option value="{{$j->id}}" selected="">{{$j->name}}</option>
                                                                @else
                                                                <option value="{{$j->id}}">{{$j->name}}</option>
                                                                @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    <div class="form-group mb-4 bmd-form-group">
                                                        <label class="bmd-label-floating">Reader <span class="red">*</span></label>
                                                        <select class="form-control select2" name="reader" style="width: 100%" disabled="">
                                                            @if($bukupedoman->reader == 0)
                                                            <option value="1">Karyawan Umum</option>
                                                            <option value="0" selected="">Karyawan Khusus</option>
                                                            @endif

                                                            @if($bukupedoman->reader == 1)
                                                            <option value="1" selected="">Karyawan Umum</option>
                                                            <option value="0">Karyawan Khuss</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                    <div class="form-group mb-4 bmd-form-group">
                                                        <label>Konten <span class="red">*</span></label>
                                                        <div class="bg-gray-200" style="padding: 10px">
                                                            {!! $bukupedoman->content !!}
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
var url_delete = "{{url('bukupedoman-delete')}}";
var base_url = "{{ url('/') }}";
</script>
@endpush