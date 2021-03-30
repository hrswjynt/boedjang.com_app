@extends('layouts.app_admin')
@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">POS</h1>
    </div>
    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6><b>Detail POS</b></h6>
                    <a href="{{ route('sop.index') }}" class="btn btn-primary btn-sm add">
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
                                                        class="form-control" value="{{$sop->title}}" tabindex="1" id="title" maxlength="200" disabled="">
                                                    </div>
                                                    <div class="form-group mb-4 bmd-form-group">
                                                        <label>Slug <span class="red">*</span></label>
                                                        <input name="slug" type="text"
                                                        class="form-control" value="{{$sop->slug}}" tabindex="1" id="slug" maxlength="200" disabled="">
                                                    </div>
                                                    <div class="form-group mb-4 bmd-form-group">
                                                        <label>Publish SOP <span class="red">*</span></label>
                                                        <select class="form-control" name="publish" disabled="">
                                                            @if($sop->publish == 0)
                                                            <option value="1">Ya</option>
                                                            <option value="0" selected="">Tidak</option>
                                                            @endif

                                                            @if($sop->publish == 1)
                                                            <option value="1" selected="">Ya</option>
                                                            <option value="0">Tidak</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                    <div class="form-group mb-4 bmd-form-group">
                                                        <label>Gambar Thumbnail </label>
                                                        @if($sop->gambar == null)
                                                        <img id="img" src="{{asset('images/noimage.png')}}" alt="your image" height="100%" style="margin-top: 10px;width: 100%;height: auto;" />
                                                        @else
                                                        <img id="img" src="{{asset('images/sop/'.$sop->gambar)}}" alt="your image" height="100%" style="margin-top: 10px;width: 100%;height: auto;"/>
                                                        @endif
                                                    </div>
                                                    <div class="form-group mb-4 bmd-form-group">
                                                        <label>Kategori<span class="red">*</span></label>
                                                        <select class="select2" multiple="multiple" name="category[]" id="category" class="form-control" style="width: 100%" disabled="">   
                                                            @foreach($category as $c)
                                                            @if(in_array($c->id,$sopcategory))
                                                            <option value="{{$c->id}}" selected="">{{$c->name}}</option>
                                                            @else
                                                            <option value="{{$c->id}}">{{$c->name}}</option>
                                                            @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group mb-4 bmd-form-group">
                                                        <label>Jenis<span class="red">*</span></label>
                                                        <select class="select2" name="type" id="type" class="form-control" style="width: 100%" disabled>   
                                                            @foreach($type as $t)
                                                            @if($t->id == $sop->type)
                                                            <option value="{{$t->id}}" selected="">{{$t->name}}</option>
                                                            @else
                                                            <option value="{{$t->id}}">{{$t->name}}</option>
                                                            @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group mb-4 bmd-form-group">
                                                        <label>Jabatan<span class="red">*</span></label>
                                                        <select class="select2" multiple="multiple"  name="jabatan[]" id="jabatan" class="form-control" style="width: 100%" disabled="">   
                                                            @foreach($jabatan as $j)
                                                            @if(in_array($j->id,$sopjabatan))
                                                            <option value="{{$j->id}}" selected="">{{$j->name}}</option>
                                                            @else
                                                            <option value="{{$j->id}}">{{$j->name}}</option>
                                                            @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group mb-4 bmd-form-group">
                                                        <label>Konten <span class="red">*</span></label>
                                                        <div class="bg-gray-200" style="padding: 10px">
                                                            {!! $sop->content !!}
                                                        </div>
                                                    </div>
                                                    <div class="form-group mb-4 bmd-form-group">
                                                        <label>Google Drive ID File </label>
                                                        <input name="google_drive" type="text"
                                                            class="form-control" value="{{old('google_drive')}}" id="google_drive" maxlength="250" disabled="">
                                                    </div>
                                                    <div class="form-group mb-4 bmd-form-group">
                                                        <label>Youtube Embed </label>
                                                        <input name="youtube" type="text"
                                                            class="form-control" value="{{old('youtube')}}" id="youtube" maxlength="250" disabled="">
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
var url_delete = "{{url('sop-delete')}}";
var base_url = "{{ url('/') }}";
</script>
@endpush